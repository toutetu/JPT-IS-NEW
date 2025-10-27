<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;

class DailyLogReviewController extends Controller
{
    // 担当クラスの提出一覧（直近7日）
   // app/Http/Controllers/Teacher/DailyLogReviewController.php

    public function index(\Illuminate\Http\Request $request)
    {
        abort_if(\Auth::user()->role !== 'teacher', 403);

        // 日付フィルタ（指定なければ前登校日）
        $date = $request->query('date');
        if ($date) {
            $selected = \Carbon\Carbon::parse($date)->toDateString();
        } else {
            // 前登校日を計算（平日の前日）
            $today = \Carbon\Carbon::today();
            $dayOfWeek = $today->dayOfWeek; // 0=日曜日, 1=月曜日, ..., 6=土曜日
            
            if ($dayOfWeek == 1) { // 月曜日
                $selected = $today->subDays(3)->toDateString(); // 前の金曜日
            } elseif ($dayOfWeek == 0) { // 日曜日
                $selected = $today->subDays(2)->toDateString(); // 前の金曜日
            } elseif ($dayOfWeek == 6) { // 土曜日
                $selected = $today->subDays(1)->toDateString(); // 前の金曜日
            } else { // 火曜日〜金曜日
                $selected = $today->subDay()->toDateString(); // 前日
            }
        }

        // この先生の担当クラスの生徒ID
        $studentIds = \DB::table('homeroom_assignments')
            ->join('classrooms', 'classrooms.id', '=', 'homeroom_assignments.classroom_id')
            ->join('enrollments', 'enrollments.classroom_id', '=', 'classrooms.id')
            ->where('homeroom_assignments.teacher_id', \Auth::id())
            ->where('enrollments.is_active', true)
            ->pluck('enrollments.student_id')
            ->unique()
            ->values();

        // KPI
        $totalStudents = $studentIds->count();

        $submittedCount = \DB::table('daily_logs')
            ->whereIn('student_id', $studentIds)
            ->where('target_date', $selected)
            ->count();

        $unreadCount = \DB::table('daily_logs')
            ->whereIn('student_id', $studentIds)
            ->where('target_date', $selected)
            ->whereNull('read_at')
            ->count();

        // 未提出者（選択日）
        $submittedStudentIds = \DB::table('daily_logs')
            ->whereIn('student_id', $studentIds)
            ->where('target_date', $selected)
            ->pluck('student_id')
            ->unique();

        $unsubmitted = \DB::table('users')
            ->whereIn('id', $studentIds->diff($submittedStudentIds))
            ->orderBy('name')
            ->get(['id','name','email']);

        // 一覧（選択日）＋生徒名
        $logs = \DB::table('daily_logs')
            ->join('users', 'users.id', '=', 'daily_logs.student_id')
            ->select('daily_logs.*', 'users.name as student_name')
            ->whereIn('daily_logs.student_id', $studentIds->all() ?: [0])
            ->where('daily_logs.target_date', $selected)
            ->orderBy('users.name')
            ->paginate(20)
            ->appends(['date' => $selected]); // ページング維持

        // 担当クラス情報を取得
        $assignedClasses = \DB::table('homeroom_assignments')
            ->join('classrooms', 'classrooms.id', '=', 'homeroom_assignments.classroom_id')
            ->join('grades', 'grades.id', '=', 'classrooms.grade_id')
            ->where('homeroom_assignments.teacher_id', \Auth::id())
            ->whereNull('homeroom_assignments.until_date') // 現在有効な担当のみ
            ->select(
                'classrooms.name as classroom_name',
                'grades.name as grade_name'
            )
            ->orderBy('grades.id')
            ->orderBy('classrooms.name')
            ->get();

        // クラス名を結合して表示用文字列を作成
        $teacherAssignedClasses = $assignedClasses->map(function ($class) {
            return $class->classroom_name; // 学年名は削除（クラス名に既に含まれている）
        })->implode('・');

        return view('teacher.daily_logs.index', compact(
            'logs', 'selected', 'totalStudents', 'submittedCount', 'unreadCount', 'unsubmitted', 'teacherAssignedClasses'
        ));
    }


    // 既読処理
    public function read($id)
    {
        abort_if(Auth::user()->role !== 'teacher', 403);

        // 指定レコードが自分の担当クラスの生徒かをチェック
        $log = DB::table('daily_logs')->where('id', $id)->first();
        abort_unless($log, 404);

        $isMyStudent = DB::table('homeroom_assignments')
            ->join('classrooms', 'classrooms.id', '=', 'homeroom_assignments.classroom_id')
            ->join('enrollments', 'enrollments.classroom_id', '=', 'classrooms.id')
            ->where('homeroom_assignments.teacher_id', Auth::id())
            ->where('enrollments.student_id', $log->student_id)
            ->where('enrollments.is_active', true)
            ->exists();

        abort_unless($isMyStudent, 403);

        DB::table('daily_logs')
            ->where('id', $id)
            ->update([
                'read_at' => now(),
                'read_by' => Auth::id(),
                'updated_at' => now(),
            ]);

        return back()->with('status', '既読にしました。');
    }
    
    public function show($id, \Illuminate\Http\Request $request)
    {
        abort_if(\Auth::user()->role !== 'teacher', 403);

        // レコード取得
        $log = \DB::table('daily_logs')
            ->join('users', 'users.id', '=', 'daily_logs.student_id')
            ->select('daily_logs.*', 'users.name as student_name')
            ->where('daily_logs.id', $id)
            ->first();

        abort_unless($log, 404);

        // その生徒が自分の担当クラスかチェック
        $isMyStudent = \DB::table('homeroom_assignments')
            ->join('classrooms', 'classrooms.id', '=', 'homeroom_assignments.classroom_id')
            ->join('enrollments', 'enrollments.classroom_id', '=', 'classrooms.id')
            ->where('homeroom_assignments.teacher_id', \Auth::id())
            ->where('enrollments.student_id', $log->student_id)
            ->where('enrollments.is_active', true)
            ->exists();
        abort_unless($isMyStudent, 403);

        // 一覧の date パラメータを戻り先として保持（無ければ target_date）
        $backDate = $request->query('date') ?: $log->target_date;

        return view('teacher.daily_logs.show', compact('log', 'backDate'));
    }

    // 担当クラスの生徒一覧（過去記録確認用）
    public function students()
    {
        abort_if(\Auth::user()->role !== 'teacher', 403);

        // この先生の担当クラスの生徒一覧
        $students = \DB::table('homeroom_assignments')
            ->join('classrooms', 'classrooms.id', '=', 'homeroom_assignments.classroom_id')
            ->join('enrollments', 'enrollments.classroom_id', '=', 'classrooms.id')
            ->join('users', 'users.id', '=', 'enrollments.student_id')
            ->join('grades', 'grades.id', '=', 'classrooms.grade_id')
            ->where('homeroom_assignments.teacher_id', \Auth::id())
            ->where('enrollments.is_active', true)
            ->select(
                'users.id', 'users.name', 'users.email',
                'classrooms.name as classroom_name', 'grades.name as grade_name'
            )
            ->orderBy('grades.id')
            ->orderBy('classrooms.name')
            ->orderBy('users.name')
            ->get();

        return view('teacher.students.index', compact('students'));
    }

    // 特定生徒の過去記録一覧
    public function studentLogs($studentId, \Illuminate\Http\Request $request)
    {
        abort_if(\Auth::user()->role !== 'teacher', 403);

        // その生徒が自分の担当クラスかチェック
        $isMyStudent = \DB::table('homeroom_assignments')
            ->join('classrooms', 'classrooms.id', '=', 'homeroom_assignments.classroom_id')
            ->join('enrollments', 'enrollments.classroom_id', '=', 'classrooms.id')
            ->where('homeroom_assignments.teacher_id', \Auth::id())
            ->where('enrollments.student_id', $studentId)
            ->where('enrollments.is_active', true)
            ->exists();
        abort_unless($isMyStudent, 403);

        // 生徒情報取得
        $student = \DB::table('users')
            ->join('enrollments', 'enrollments.student_id', '=', 'users.id')
            ->join('classrooms', 'classrooms.id', '=', 'enrollments.classroom_id')
            ->join('grades', 'grades.id', '=', 'classrooms.grade_id')
            ->where('users.id', $studentId)
            ->where('enrollments.is_active', true)
            ->select(
                'users.id', 'users.name', 'users.email',
                'classrooms.name as classroom_name', 'grades.name as grade_name'
            )
            ->first();

        abort_unless($student, 404);

        // 日付フィルタ（指定なければ過去30日）
        $dateFrom = $request->query('date_from');
        $dateTo = $request->query('date_to');
        
        $query = \DB::table('daily_logs')
            ->where('student_id', $studentId)
            ->orderByDesc('target_date');

        if ($dateFrom) {
            $query->where('target_date', '>=', $dateFrom);
        } else {
            // デフォルトは過去30日
            $query->where('target_date', '>=', \Carbon\Carbon::today()->subDays(30)->toDateString());
        }

        if ($dateTo) {
            $query->where('target_date', '<=', $dateTo);
        }

        $logs = $query->paginate(20)
            ->appends($request->query());

        return view('teacher.students.logs', compact('student', 'logs', 'dateFrom', 'dateTo'));
    }


}