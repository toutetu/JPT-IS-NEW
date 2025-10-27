<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index(Request $request)
    {
        abort_if(\Auth::user()->role !== 'admin', 403);

        // 検索パラメータを取得
        $search = $request->get('search');
        $role = $request->get('role');

        // 生徒の現在在籍クラス（is_active = true）
        $studentSub = \DB::table('enrollments')
            ->join('classrooms', 'classrooms.id', '=', 'enrollments.classroom_id')
            ->select('enrollments.student_id', 'classrooms.name as s_class_name')
            ->where('enrollments.is_active', true);

        // 担任の現在担当クラス（until_date が NULL）
        $teacherSub = \DB::table('homeroom_assignments')
            ->join('classrooms', 'classrooms.id', '=', 'homeroom_assignments.classroom_id')
            ->select('homeroom_assignments.teacher_id', 'classrooms.name as t_class_name')
            ->whereNull('homeroom_assignments.until_date');

        $query = \DB::table('users')
            ->leftJoinSub($studentSub, 'stu', function ($join) {
                $join->on('stu.student_id', '=', 'users.id');
            })
            ->leftJoinSub($teacherSub, 'tea', function ($join) {
                $join->on('tea.teacher_id', '=', 'users.id');
            })
            ->select([
                'users.id', 'users.name', 'users.email', 'users.role', 'users.created_at',
                \DB::raw('COALESCE(stu.s_class_name, tea.t_class_name) as assigned_class'),
            ]);

        // 検索条件を適用
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('users.name', 'like', "%{$search}%")
                  ->orWhere('users.email', 'like', "%{$search}%");
            });
        }

        if ($role) {
            $query->where('users.role', $role);
        }

        $users = $query->orderBy('users.id', 'desc')->paginate(10);

        return view('admin.users.index', compact('users', 'search', 'role'));
    }


    public function create()
    {
        abort_if(Auth::user()->role !== 'admin', 403);
        return view('admin.users.create');
    }

    public function store(Request $request)
    {
        abort_if(Auth::user()->role !== 'admin', 403);

        $data = $request->validate([
            'name'     => ['required','string','max:255'],
            'email'    => ['required','email','max:255','unique:users,email'],
            'role'     => ['required','in:student,teacher,admin'],
            'password' => ['required','string','min:8'],
        ]);

        DB::table('users')->insert([
            'name' => $data['name'],
            'email' => $data['email'],
            'role' => $data['role'],
            'password' => Hash::make($data['password']),
            'email_verified_at' => now(),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect()->route('admin.users.index')->with('status','ユーザーを作成しました。');
    }

    /**
     * ログインなしで新規ユーザー作成フォームを表示
     */
    public function createWithoutAuth()
    {
        return view('admin.users.create_without_auth');
    }

    /**
     * ログインなしで新規ユーザーを作成
     */
    public function storeWithoutAuth(Request $request)
    {
        try {
            $data = $request->validate([
                'name'     => ['required','string','max:255'],
                'email'    => ['required','email','max:255','unique:users,email'],
                'role'     => ['required','in:student,teacher,admin'],
                'password' => ['required','string','min:8'],
            ]);

            DB::table('users')->insert([
                'name' => $data['name'],
                'email' => $data['email'],
                'role' => $data['role'],
                'password' => Hash::make($data['password']),
                'email_verified_at' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // デバッグ用ログ
            \Log::info('ユーザー作成成功: ' . $data['email']);

            return redirect()->route('admin.users.create_without_auth')
                ->with('status', 'ユーザーを作成しました。')
                ->with('success', true);
        } catch (\Illuminate\Validation\ValidationException $e) {
            // バリデーションエラーの場合、エラーメッセージと共にリダイレクト
            return redirect()->route('admin.users.create_without_auth')
                ->withErrors($e->validator)
                ->withInput();
        } catch (\Exception $e) {
            // その他のエラーの場合
            return redirect()->route('admin.users.create_without_auth')
                ->with('error', 'ユーザー作成中にエラーが発生しました: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function importForm()
    {
        abort_if(Auth::user()->role !== 'admin', 403);
        return view('admin.users.import');
    }

    public function importCSV(Request $request)
    {
        abort_if(Auth::user()->role !== 'admin', 403);

        $request->validate([
            'csv_file' => ['required', 'mimes:csv,txt', 'max:1024'],
        ]);

        $file = $request->file('csv_file');
        $filePath = $file->getRealPath();

        // CSVファイルを読み込む
        $handle = fopen($filePath, 'r');
        
        // BOMをスキップ（Excelで保存した場合の対応）
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
                if (empty($name) || empty($email) || empty($role) || empty($password)) {
                    $errorCount++;
                    $errors[] = "行 $successCount: 必須項目が不足しています";
                    continue;
                }

                if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                    $errorCount++;
                    $errors[] = "行 $successCount: メールアドレスの形式が不正です: $email";
                    continue;
                }

                if (!in_array($role, ['student', 'teacher', 'admin'])) {
                    $errorCount++;
                    $errors[] = "行 $successCount: ロールが不正です: $role";
                    continue;
                }

                if (strlen($password) < 8) {
                    $errorCount++;
                    $errors[] = "行 $successCount: パスワードが8文字未満です";
                    continue;
                }

                // 重複チェック
                if (DB::table('users')->where('email', $email)->exists()) {
                    $errorCount++;
                    $errors[] = "行 $successCount: メールアドレスが既に存在します: $email";
                    continue;
                }

                // ユーザー作成
                DB::table('users')->insert([
                    'name' => $name,
                    'email' => $email,
                    'role' => $role,
                    'password' => Hash::make($password),
                    'email_verified_at' => now(),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);

                $successCount++;
            }

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('admin.users.import')
                ->with('error', 'CSVのインポート中にエラーが発生しました: ' . $e->getMessage());
        }

        fclose($handle);

        $message = "{$successCount}件のユーザーを作成しました。";
        if ($errorCount > 0) {
            $message .= " {$errorCount}件のエラーがありました。";
        }

        return redirect()->route('admin.users.import')
            ->with('status', $message)
            ->with('errors', $errors);
    }

    public function delete($id)
    {
        abort_if(Auth::user()->role !== 'admin', 403);

        // 自分自身は削除できないようにする
        if (Auth::id() == $id) {
            return redirect()->route('admin.users.index')
                ->with('error', '自分自身を削除することはできません。');
        }

        // ユーザーが存在するか確認
        $user = DB::table('users')->where('id', $id)->first();
        
        if (!$user) {
            return redirect()->route('admin.users.index')
                ->with('error', 'ユーザーが見つかりません。');
        }

        // 削除確認画面で表示するため、ユーザー情報と関連データを取得
        $studentSub = \DB::table('enrollments')
            ->join('classrooms', 'classrooms.id', '=', 'enrollments.classroom_id')
            ->select('enrollments.student_id', 'classrooms.name as s_class_name')
            ->where('enrollments.student_id', $id)
            ->where('enrollments.is_active', true);

        $teacherSub = \DB::table('homeroom_assignments')
            ->join('classrooms', 'classrooms.id', '=', 'homeroom_assignments.classroom_id')
            ->select('homeroom_assignments.teacher_id', 'classrooms.name as t_class_name')
            ->where('homeroom_assignments.teacher_id', $id)
            ->whereNull('homeroom_assignments.until_date');

        $user = DB::table('users')
            ->leftJoinSub($studentSub, 'stu', function ($join) {
                $join->on('stu.student_id', '=', 'users.id');
            })
            ->leftJoinSub($teacherSub, 'tea', function ($join) {
                $join->on('tea.teacher_id', '=', 'users.id');
            })
            ->select([
                'users.id', 'users.name', 'users.email', 'users.role', 'users.created_at',
                \DB::raw('COALESCE(stu.s_class_name, tea.t_class_name) as assigned_class'),
            ])
            ->where('users.id', $id)
            ->first();

        return view('admin.users.delete', compact('user'));
    }

    public function destroy($id)
    {
        abort_if(Auth::user()->role !== 'admin', 403);

        // 自分自身は削除できないようにする
        if (Auth::id() == $id) {
            return redirect()->route('admin.users.index')
                ->with('error', '自分自身を削除することはできません。');
        }

        // ユーザーが存在するか確認
        $user = DB::table('users')->where('id', $id)->first();
        
        if (!$user) {
            return redirect()->route('admin.users.index')
                ->with('error', 'ユーザーが見つかりません。');
        }

        // 関連データも削除（enrollments, homeroom_assignments, daily_logs）
        DB::table('enrollments')->where('student_id', $id)->delete();
        DB::table('homeroom_assignments')->where('teacher_id', $id)->delete();
        
        // daily_logsの削除（作成者として、または既読者として）
        DB::table('daily_logs')->where('student_id', $id)->delete();

        // ユーザーを削除
        DB::table('users')->where('id', $id)->delete();

        return redirect()->route('admin.users.index')
            ->with('status', 'ユーザーを削除しました。');
    }
}