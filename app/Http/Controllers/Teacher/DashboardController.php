<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        abort_if(Auth::user()->role !== 'teacher', 403);

        // この先生の担当クラス情報を取得
        $assignedClasses = DB::table('homeroom_assignments')
            ->join('classrooms', 'classrooms.id', '=', 'homeroom_assignments.classroom_id')
            ->join('grades', 'grades.id', '=', 'classrooms.grade_id')
            ->where('homeroom_assignments.teacher_id', Auth::id())
            ->whereNull('homeroom_assignments.until_date') // 現在有効な担当のみ
            ->select(
                'classrooms.id as classroom_id',
                'classrooms.name as classroom_name',
                'grades.name as grade_name',
                'homeroom_assignments.since_date'
            )
            ->orderBy('grades.id')
            ->orderBy('classrooms.name')
            ->get();

        // 各クラスの生徒数を取得
        foreach ($assignedClasses as $class) {
            $class->student_count = DB::table('enrollments')
                ->where('classroom_id', $class->classroom_id)
                ->where('is_active', true)
                ->count();
        }

        // 今日の提出状況のサマリー
        $today = now()->toDateString();
        $totalStudents = $assignedClasses->sum('student_count');
        
        $submittedToday = DB::table('homeroom_assignments')
            ->join('classrooms', 'classrooms.id', '=', 'homeroom_assignments.classroom_id')
            ->join('enrollments', 'enrollments.classroom_id', '=', 'classrooms.id')
            ->join('daily_logs', 'daily_logs.student_id', '=', 'enrollments.student_id')
            ->where('homeroom_assignments.teacher_id', Auth::id())
            ->whereNull('homeroom_assignments.until_date')
            ->where('enrollments.is_active', true)
            ->where('daily_logs.target_date', $today)
            ->count();

        $unreadToday = DB::table('homeroom_assignments')
            ->join('classrooms', 'classrooms.id', '=', 'homeroom_assignments.classroom_id')
            ->join('enrollments', 'enrollments.classroom_id', '=', 'classrooms.id')
            ->join('daily_logs', 'daily_logs.student_id', '=', 'enrollments.student_id')
            ->where('homeroom_assignments.teacher_id', Auth::id())
            ->whereNull('homeroom_assignments.until_date')
            ->where('enrollments.is_active', true)
            ->where('daily_logs.target_date', $today)
            ->whereNull('daily_logs.read_at')
            ->count();

        return view('teacher.dashboard', compact(
            'assignedClasses',
            'totalStudents',
            'submittedToday',
            'unreadToday',
            'today'
        ));
    }
}
