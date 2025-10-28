<?php

namespace App\Services;

use App\Models\Enrollment;
use App\Models\User;
use App\Models\Classroom;
use Illuminate\Support\Facades\DB;

class AssignmentService
{
    /**
     * 生徒の在籍を変更
     */
    public function changeStudentEnrollment(int $studentId, int $classroomId): Enrollment
    {
        return Enrollment::changeStudentEnrollment($studentId, $classroomId);
    }

    /**
     * 教師のクラス担当を変更
     */
    public function changeTeacherAssignment(int $teacherId, int $classroomId): \App\Models\HomeroomAssignment
    {
        return \App\Models\HomeroomAssignment::changeTeacherAssignment($teacherId, $classroomId);
    }

    /**
     * CSVから在籍情報を一括インポート
     */
    public function importEnrollmentsFromCsv(string $filePath): array
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
                if (count($data) < 2) {
                    continue;
                }

                $email = trim($data[0]);
                $classroomName = trim($data[1]);

                if (empty($email) || empty($classroomName)) {
                    $errorCount++;
                    $errors[] = "行 " . ($successCount + $errorCount + 1) . ": 必須項目が不足しています";
                    continue;
                }

                // メールアドレスで生徒を検索
                $student = User::students()->where('email', $email)->first();

                if (!$student) {
                    $errorCount++;
                    $errors[] = "行 " . ($successCount + $errorCount + 1) . ": 生徒が見つかりません: $email";
                    continue;
                }

                // クラス名でクラスを検索
                $classroom = Classroom::findByName($classroomName);

                if (!$classroom) {
                    $errorCount++;
                    $errors[] = "行 " . ($successCount + $errorCount + 1) . ": クラスが見つかりません: $classroomName";
                    continue;
                }

                // 在籍を変更
                $this->changeStudentEnrollment($student->id, $classroom->id);
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
     * 生徒の現在の在籍情報を取得
     */
    public function getCurrentEnrollmentForStudent(int $studentId): ?Enrollment
    {
        return Enrollment::getCurrentForStudent($studentId);
    }

    /**
     * 教師の現在の担当クラス情報を取得
     */
    public function getCurrentAssignmentForTeacher(int $teacherId): ?\App\Models\HomeroomAssignment
    {
        return \App\Models\HomeroomAssignment::getCurrentForTeacher($teacherId);
    }

    /**
     * 教師の担当クラスの生徒ID一覧を取得
     */
    public function getStudentIdsForTeacher(int $teacherId): \Illuminate\Support\Collection
    {
        return \App\Models\HomeroomAssignment::getStudentIdsForTeacher($teacherId);
    }

    /**
     * 教師の担当クラス情報を取得
     */
    public function getAssignedClassesForTeacher(int $teacherId)
    {
        return \App\Models\HomeroomAssignment::getAssignedClassesForTeacher($teacherId);
    }

    /**
     * 特定の生徒が教師の担当クラスに属するかチェック
     */
    public function isStudentUnderTeacher(int $studentId, int $teacherId): bool
    {
        return \App\Models\HomeroomAssignment::isStudentUnderTeacher($studentId, $teacherId);
    }
}
