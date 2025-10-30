<?php

namespace App\Services;

use App\Models\Classroom;
use App\Models\Grade;
use Illuminate\Support\Facades\DB;

class ClassroomService
{
    /**
     * クラス一覧を取得（学年付き）
     */
    public function getClassroomsWithGrade()
    {
        return Classroom::with('grade')
            ->orderBy('grade_id')
            ->orderBy('name')
            ->paginate(20);
    }

    /**
     * クラスを作成
     */
    public function createClassroom(array $data): Classroom
    {
        // 同じ学年で同じクラス名が存在しないかチェック
        $exists = Classroom::where('grade_id', $data['grade_id'])
            ->where('name', $data['name'])
            ->exists();

        if ($exists) {
            throw new \Exception('この学年に同じ名前のクラスが既に存在します。');
        }

        return Classroom::create([
            'name' => $data['name'],
            'grade_id' => $data['grade_id'],
        ]);
    }

    /**
     * クラスを削除（関連データのチェック付き）
     */
    public function deleteClassroom(int $classroomId): void
    {
        $classroom = Classroom::findOrFail($classroomId);

        // 在籍中の生徒がいないかチェック
        $activeEnrollments = DB::table('enrollments')
            ->where('classroom_id', $classroomId)
            ->where('is_active', true)
            ->count();

        if ($activeEnrollments > 0) {
            throw new \Exception('このクラスには在籍中の生徒がいるため削除できません。');
        }

        // 現在担当中の教師がいないかチェック
        $activeAssignments = DB::table('homeroom_assignments')
            ->where('classroom_id', $classroomId)
            ->whereNull('until_date')
            ->count();

        if ($activeAssignments > 0) {
            throw new \Exception('このクラスには担当中の教師がいるため削除できません。');
        }

        DB::transaction(function () use ($classroom) {
            // 過去の在籍記録を削除
            DB::table('enrollments')->where('classroom_id', $classroom->id)->delete();
            
            // 過去の担任記録を削除
            DB::table('homeroom_assignments')->where('classroom_id', $classroom->id)->delete();
            
            // クラスを削除
            $classroom->delete();
        });
    }

    /**
     * クラス情報を取得（在籍数・担任付き）
     */
    public function getClassroomWithDetails(int $classroomId): ?object
    {
        $classroom = Classroom::with(['grade', 'enrollments', 'homeroomAssignments'])
            ->find($classroomId);

        if (!$classroom) {
            return null;
        }

        // 在籍中の生徒数
        $activeStudentCount = $classroom->enrollments()
            ->where('is_active', true)
            ->count();

        // 現在の担任
        $currentTeacher = $classroom->homeroomAssignments()
            ->whereNull('until_date')
            ->with('teacher')
            ->first()
            ?->teacher;

        return (object) [
            'id' => $classroom->id,
            'name' => $classroom->name,
            'grade_name' => $classroom->grade->name,
            'active_student_count' => $activeStudentCount,
            'current_teacher' => $currentTeacher,
        ];
    }

    /**
     * すべての学年を取得
     */
    public function getAllGrades()
    {
        return Grade::orderBy('id')->get();
    }
}

