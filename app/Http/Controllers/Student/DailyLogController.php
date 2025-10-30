<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreDailyLogRequest;
use App\Http\Requests\UpdateDailyLogRequest;
use App\Services\DailyLogService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Carbon;

class DailyLogController extends Controller
{
    public function __construct(
        private DailyLogService $dailyLogService
    ) {}

    // 一覧（自分の提出のみ）
    public function index(Request $request)
    {
        abort_if(Auth::user()->role !== 'student', 403);

        $dateFrom = $request->query('date_from');
        $dateTo = $request->query('date_to');

        $logs = $this->dailyLogService->getStudentDailyLogsFiltered(Auth::id(), $dateFrom, $dateTo);
        $logs->appends(['date_from' => $dateFrom, 'date_to' => $dateTo]);

        // 所属クラスと担任取得（サービスへ委譲）
        $affiliation = $this->dailyLogService->getStudentAffiliation(Auth::id());
        $classroomName = $affiliation['classroom_name'] ?? null;
        $gradeName = $affiliation['grade_name'] ?? null;
        $homeroomTeacherName = $affiliation['homeroom_teacher_name'] ?? null;

        return view('student.daily_logs.index', compact(
            'logs',
            'classroomName',
            'gradeName',
            'homeroomTeacherName',
            'dateFrom',
            'dateTo'
        ));
    }

    // カレンダー表示
    public function calendar(Request $request)
    {
        abort_if(Auth::user()->role !== 'student', 403);

        // 年月の取得（デフォルトは現在の年月）
        $year = $request->get('year', now()->year);
        $month = $request->get('month', now()->month);
        
        // カレンダーの開始日と終了日を計算
        $startDate = Carbon::create($year, $month, 1)->startOfMonth();
        $endDate = $startDate->copy()->endOfMonth();
        
        // 提出済みの日付を取得
        $submittedDates = $this->dailyLogService->getSubmittedDatesForCalendar(Auth::id(), $year, $month);

        return view('student.calendar', compact('year', 'month', 'submittedDates', 'startDate', 'endDate'));
    }

    // 作成フォーム（target_date は前日を自動セット）
    public function create()
    {
        abort_if(Auth::user()->role !== 'student', 403);

        // 前登校日の簡易ロジック：
        // 月曜なら前の金曜、それ以外は前日（祝日考慮は課題2以降）
        $today = \Illuminate\Support\Carbon::today();
        $targetDate = $today->isMonday()
            ? $today->subDays(3)->toDateString()  // 月曜→金曜
            : $today->subDay()->toDateString();   // それ以外→前日

        return view('student.daily_logs.create', compact('targetDate'));
    }


    // 保存
    public function store(StoreDailyLogRequest $request)
    {
        abort_if(Auth::user()->role !== 'student', 403);

        try {
            $this->dailyLogService->createDailyLog(Auth::id(), $request->validated());
            return redirect()->route('student.daily_logs.index')->with('status', '提出しました。');
        } catch (\Exception $e) {
            return back()->withErrors(['target_date' => $e->getMessage()])->withInput();
        }
    }

    public function show($id)
    {
        abort_if(Auth::user()->role !== 'student', 403);

        $log = \App\Models\DailyLog::forStudent(Auth::id())->findOrFail($id);

        return view('student.daily_logs.show', compact('log'));
    }

    public function edit($id)
    {
        abort_if(Auth::user()->role !== 'student', 403);

        $log = \App\Models\DailyLog::forStudent(Auth::id())->findOrFail($id);

        // 既読になっていたら編集不可
        if (!$log->isEditable()) {
            return redirect()->route('student.daily_logs.show', $id)
                ->withErrors(['edit' => '既読の記録は修正できません。']);
        }

        return view('student.daily_logs.edit', compact('log'));
    }

    public function update(UpdateDailyLogRequest $request, $id)
    {
        abort_if(Auth::user()->role !== 'student', 403);

        try {
            $log = $this->dailyLogService->updateDailyLog($id, Auth::id(), $request->validated());
            return redirect()->route('student.daily_logs.show', $id)->with('status', '修正して保存しました。');
        } catch (\Exception $e) {
            return back()->withErrors(['target_date' => $e->getMessage()])->withInput();
        }
    }

}