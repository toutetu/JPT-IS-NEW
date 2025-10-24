<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;           
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AssignmentController extends Controller
{
       // 1) $request を引数で受け取る
    public function enrollmentForm(Request $request)
    {
        abort_if(Auth::user()->role !== 'admin', 403);

        $students = DB::table('users')->where('role', 'student')->orderBy('name')->get(['id','name','email']);
        $grades   = DB::table('grades')->orderBy('id')->get();
        $classes  = DB::table('classrooms')
            ->join('grades','grades.id','=','classrooms.grade_id')
            ->select('classrooms.id','classrooms.name as cname','grades.name as gname')
            ->orderBy('grades.id')->orderBy('classrooms.name')
            ->get();

        $selectedStudentId = $request->query('student_id');
        
        // 選択された生徒の現在の在籍クラスを取得
        $currentEnrollment = null;
        if ($selectedStudentId) {
            $currentEnrollment = DB::table('enrollments')
                ->join('classrooms', 'enrollments.classroom_id', '=', 'classrooms.id')
                ->join('grades', 'classrooms.grade_id', '=', 'grades.id')
                ->where('enrollments.student_id', $selectedStudentId)
                ->where('enrollments.is_active', true)
                ->select('enrollments.classroom_id', 'classrooms.name as classroom_name', 'grades.name as grade_name')
                ->first();
        }
        
        return view('admin.assign.enrollment', compact('students','grades','classes','selectedStudentId','currentEnrollment'));
    }

    public function enrollmentStore(Request $request)
    {
        abort_if(Auth::user()->role !== 'admin', 403);

        $data = $request->validate([
            'student_id'   => ['required','integer','exists:users,id'],
            'classroom_id' => ['required','integer','exists:classrooms,id'],
        ]);

        DB::table('enrollments')
            ->where('student_id', $data['student_id'])
            ->where('is_active', true)
            ->update(['is_active' => false, 'until_date' => now()->toDateString(), 'updated_at' => now()]);

        DB::table('enrollments')->insert([
            'student_id'   => $data['student_id'],
            'classroom_id' => $data['classroom_id'],
            'is_active'    => true,
            'since_date'   => now()->toDateString(),
            'until_date'   => null,
            'created_at'   => now(), 'updated_at' => now(),
        ]);

        return back()->with('status', '生徒の在籍を割り当てました。');
    }

     // 2) こちらも同様に Request を受け取る
    public function homeroomForm(Request $request)
    {
        abort_if(Auth::user()->role !== 'admin', 403);

        $teachers = DB::table('users')->where('role', 'teacher')->orderBy('name')->get(['id','name','email']);
        $grades   = DB::table('grades')->orderBy('id')->get();
        $classes  = DB::table('classrooms')
            ->join('grades','grades.id','=','classrooms.grade_id')
            ->select('classrooms.id','classrooms.name as cname','grades.name as gname')
            ->orderBy('grades.id')->orderBy('classrooms.name')
            ->get();

        $selectedTeacherId = $request->query('teacher_id');
        
        // 選択された担任の現在の担当クラスを取得
        $currentHomeroom = null;
        if ($selectedTeacherId) {
            $currentHomeroom = DB::table('homeroom_assignments')
                ->join('classrooms', 'homeroom_assignments.classroom_id', '=', 'classrooms.id')
                ->join('grades', 'classrooms.grade_id', '=', 'grades.id')
                ->where('homeroom_assignments.teacher_id', $selectedTeacherId)
                ->whereNull('homeroom_assignments.until_date')
                ->select('homeroom_assignments.classroom_id', 'classrooms.name as classroom_name', 'grades.name as grade_name')
                ->first();
        }
        
        return view('admin.assign.homeroom', compact('teachers','grades','classes','selectedTeacherId','currentHomeroom'));
    }

    public function homeroomStore(Request $request)
    {
        abort_if(Auth::user()->role !== 'admin', 403);

        $data = $request->validate([
            'teacher_id'   => ['required','integer','exists:users,id'],
            'classroom_id' => ['required','integer','exists:classrooms,id'],
        ]);

        DB::table('homeroom_assignments')
            ->where('teacher_id', $data['teacher_id'])
            ->whereNull('until_date')
            ->update(['until_date' => now()->toDateString(), 'updated_at' => now()]);

        DB::table('homeroom_assignments')->insert([
            'teacher_id'   => $data['teacher_id'],
            'classroom_id' => $data['classroom_id'],
            'since_date'   => now()->toDateString(),
            'until_date'   => null,
            'created_at'   => now(), 'updated_at' => now(),
        ]);

        return back()->with('status', '担任のクラス割当を実施しました。');
    }
}