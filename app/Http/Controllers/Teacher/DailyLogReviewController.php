<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Services\DailyLogService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DailyLogReviewController extends Controller
{
    public function __construct(
        private DailyLogService $dailyLogService
    ) {}

    public function index(\Illuminate\Http\Request $request)
    {
        abort_if(\Auth::user()->role !== 'teacher', 403);

        // 日付フィルタ（指定なければ前登校日）
        $date = $request->query('date');
        $selected = $date ?: $this->dailyLogService->getPreviousSchoolDay();

        // 担当クラスの生徒ID一覧を取得
        $studentIds = \App\Models\HomeroomAssignment::getStudentIdsForTeacher(\Auth::id());

        // 提出状況のサマリーを取得
        $stats = $this->dailyLogService->getTeacherClassSubmissionStats(\Auth::id(), $selected);

        // 一覧を取得（ページネーション対応）
        $logs = \App\Models\DailyLog::whereIn('student_id', $studentIds)
            ->forDate($selected)
            ->with('student')
            ->orderBy('student_id')
            ->paginate(20)
            ->appends(['date' => $selected]);

        // 担当クラス情報を取得
        $assignedClasses = \App\Models\HomeroomAssignment::getAssignedClassesForTeacher(\Auth::id());
        $teacherAssignedClasses = $assignedClasses->pluck('classroom_name')->implode('・');

        // ビューで期待されている変数名に合わせる
        $totalStudents = $stats['total_students'];
        $submittedCount = $stats['submitted_count'];
        $unreadCount = $stats['unread_count'];
        $unsubmitted = $stats['unsubmitted'];

        return view('teacher.daily_logs.index', compact(
            'logs', 'selected', 'totalStudents', 'submittedCount', 'unreadCount', 'unsubmitted', 'teacherAssignedClasses'
        ));
    }


    // 既読処理
    public function read($id)
    {
        abort_if(Auth::user()->role !== 'teacher', 403);

        // 指定レコードが自分の担当クラスの生徒かをチェック
        $log = \App\Models\DailyLog::findOrFail($id);

        if (!\App\Models\HomeroomAssignment::isStudentUnderTeacher($log->student_id, Auth::id())) {
            abort(403);
        }

        $this->dailyLogService->markAsRead($id, Auth::id());

        return back()->with('status', '既読にしました。');
    }
    
    public function show($id, \Illuminate\Http\Request $request)
    {
        abort_if(\Auth::user()->role !== 'teacher', 403);

        // レコード取得
        $log = \App\Models\DailyLog::with('student')->findOrFail($id);

        // その生徒が自分の担当クラスかチェック
        if (!\App\Models\HomeroomAssignment::isStudentUnderTeacher($log->student_id, \Auth::id())) {
            abort(403);
        }

        // 一覧の date パラメータを戻り先として保持（無ければ target_date）
        $backDate = $request->query('date') ?: $log->target_date;

        return view('teacher.daily_logs.show', compact('log', 'backDate'));
    }

    // 担当クラスの生徒一覧（過去記録確認用）
    public function students()
    {
        abort_if(\Auth::user()->role !== 'teacher', 403);

        // この先生の担当クラスの生徒一覧
        $students = \App\Models\HomeroomAssignment::forTeacher(\Auth::id())
            ->current()
            ->with(['classroom.enrollments' => function ($query) {
                $query->where('is_active', true)->with('student');
            }, 'classroom.grade'])
            ->get()
            ->flatMap(function ($assignment) {
                return $assignment->classroom->enrollments->map(function ($enrollment) use ($assignment) {
                    $student = $enrollment->student;
                    $student->classroom_name = $assignment->classroom->name;
                    $student->grade_name = $assignment->classroom->grade->name;
                    return $student;
                });
            })
            ->sortBy('name')
            ->values();

        return view('teacher.students.index', compact('students'));
    }

    // 特定生徒の過去記録一覧
    public function studentLogs($studentId, \Illuminate\Http\Request $request)
    {
        abort_if(\Auth::user()->role !== 'teacher', 403);

        // その生徒が自分の担当クラスかチェック
        if (!\App\Models\HomeroomAssignment::isStudentUnderTeacher($studentId, \Auth::id())) {
            abort(403);
        }

        // 生徒情報取得
        $student = \App\Models\User::with(['enrollments.classroom.grade'])
            ->findOrFail($studentId);

        $currentEnrollment = $student->enrollments->where('is_active', true)->first();
        if (!$currentEnrollment) {
            abort(404);
        }

        $student->classroom_name = $currentEnrollment->classroom->name;
        $student->grade_name = $currentEnrollment->classroom->grade->name;

        // 日付フィルタ
        $dateFrom = $request->query('date_from');
        $dateTo = $request->query('date_to');

        $logs = $this->dailyLogService->getStudentPastLogs($studentId, $dateFrom, $dateTo);

        return view('teacher.students.logs', compact('student', 'logs', 'dateFrom', 'dateTo'));
    }


}