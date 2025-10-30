<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class UserService
{
    /**
     * ユーザーを作成
     */
    public function createUser(array $data): User
    {
        return User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'role' => $data['role'],
            'password' => Hash::make($data['password']),
            'email_verified_at' => now(),
        ]);
    }

    /**
     * ユーザーを削除（関連データも含む）
     */
    public function deleteUser(int $userId): void
    {
        DB::transaction(function () use ($userId) {
            // 関連データを削除
            DB::table('enrollments')->where('student_id', $userId)->delete();
            DB::table('homeroom_assignments')->where('teacher_id', $userId)->delete();
            DB::table('daily_logs')->where('student_id', $userId)->delete();
            
            // ユーザーを削除
            User::destroy($userId);
        });
    }

    /**
     * ユーザー一覧を取得（検索・フィルタ付き）
     */
    public function getUsersWithAssignments(string $search = null, string $role = null)
    {
        $query = User::query()
            ->with([
                'enrollments' => function ($query) {
                    $query->where('is_active', true)->with('classroom.grade');
                },
                'homeroomAssignments' => function ($query) {
                    $query->whereNull('until_date')->with('classroom.grade');
                }
            ])
            ->select(['id', 'name', 'email', 'role', 'created_at']);

        if ($search) {
            $query->search($search);
        }

        if ($role) {
            $query->byRole($role);
        }

        $users = $query->orderBy('id', 'desc')->paginate(10);

        // 各ユーザーにassigned_class属性を追加
        $users->getCollection()->transform(function ($user) {
            $assignedClass = null;

            if ($user->role === 'student' && $user->enrollments->isNotEmpty()) {
                $enrollment = $user->enrollments->first();
                $assignedClass = $enrollment->classroom->name ?? null;
            } elseif ($user->role === 'teacher' && $user->homeroomAssignments->isNotEmpty()) {
                $assignment = $user->homeroomAssignments->first();
                $assignedClass = $assignment->classroom->name ?? null;
            }

            $user->assigned_class = $assignedClass;
            return $user;
        });

        return $users;
    }

    /**
     * ユーザー情報をクラス割り当て情報と共に取得
     */
    public function getUserWithAssignment(int $userId): ?object
    {
        $user = User::with([
            'enrollments' => function ($query) {
                $query->where('is_active', true)->with('classroom');
            },
            'homeroomAssignments' => function ($query) {
                $query->whereNull('until_date')->with('classroom');
            }
        ])->find($userId);

        if (!$user) {
            return null;
        }

        // クラス割り当て情報を取得
        $assignedClass = null;
        if ($user->role === 'student' && $user->enrollments->isNotEmpty()) {
            $assignedClass = $user->enrollments->first()->classroom->name ?? null;
        } elseif ($user->role === 'teacher' && $user->homeroomAssignments->isNotEmpty()) {
            $assignedClass = $user->homeroomAssignments->first()->classroom->name ?? null;
        }

        // stdClassオブジェクトとして返す（Viewとの互換性のため）
        return (object) [
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'role' => $user->role,
            'created_at' => $user->created_at,
            'assigned_class' => $assignedClass,
        ];
    }

    /**
     * CSVからユーザーを一括作成
     */
    public function importUsersFromCsv(string $filePath): array
    {
        $handle = fopen($filePath, 'r');
        
        // BOMをスキップ
        $bom = fread($handle, 3);
        if ($bom !== chr(0xEF) . chr(0xBB) . chr(0xBF)) {
            rewind($handle);
        }

        $successCount = 0;
        $errorCount = 0;
        $errors = [];

        // ヘッダー行をスキップ
        fgetcsv($handle);

        DB::beginTransaction();
        try {
            while (($data = fgetcsv($handle)) !== FALSE) {
                if (count($data) < 4) {
                    continue;
                }

                $name = trim($data[0]);
                $email = trim($data[1]);
                $role = trim($data[2]);
                $password = trim($data[3]);

                // バリデーション
                $validationResult = $this->validateCsvUserData($name, $email, $role, $password, $successCount + $errorCount + 1);
                if (!$validationResult['valid']) {
                    $errorCount++;
                    $errors[] = $validationResult['error'];
                    continue;
                }

                // 重複チェック
                if (User::where('email', $email)->exists()) {
                    $errorCount++;
                    $errors[] = "行 " . ($successCount + $errorCount + 1) . ": メールアドレスが既に存在します: $email";
                    continue;
                }

                // ユーザー作成
                $this->createUser([
                    'name' => $name,
                    'email' => $email,
                    'role' => $role,
                    'password' => $password,
                ]);

                $successCount++;
            }

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }

        fclose($handle);

        return [
            'success_count' => $successCount,
            'error_count' => $errorCount,
            'errors' => $errors,
        ];
    }

    /**
     * CSVユーザーデータのバリデーション
     */
    private function validateCsvUserData(string $name, string $email, string $role, string $password, int $rowNumber): array
    {
        if (empty($name) || empty($email) || empty($role) || empty($password)) {
            return [
                'valid' => false,
                'error' => "行 $rowNumber: 必須項目が不足しています"
            ];
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return [
                'valid' => false,
                'error' => "行 $rowNumber: メールアドレスの形式が不正です: $email"
            ];
        }

        if (!in_array($role, ['student', 'teacher', 'admin'])) {
            return [
                'valid' => false,
                'error' => "行 $rowNumber: ロールが不正です: $role"
            ];
        }

        if (strlen($password) < 8) {
            return [
                'valid' => false,
                'error' => "行 $rowNumber: パスワードが8文字未満です"
            ];
        }

        return ['valid' => true];
    }
}
