<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Services\DailyLogService;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function __construct(
        private DailyLogService $dailyLogService
    ) {}

    public function index()
    {
        abort_if(Auth::user()->role !== 'teacher', 403);

        // この先生の担当クラス情報を取得（サービスへ委譲）
        $assignedClasses = $this->dailyLogService->getAssignedClassesWithCounts(Auth::id());

        // 今日の提出状況のサマリーを取得
        $todayStats = $this->dailyLogService->getTeacherTodayStats(Auth::id());

        // ビューで期待されている変数名に合わせる
        $totalStudents = $todayStats['total_students'];
        $submittedToday = $todayStats['submitted_today'];
        $unreadToday = $todayStats['unread_today'];
        $today = $todayStats['today'];

        return view('teacher.dashboard', compact(
            'assignedClasses',
            'totalStudents',
            'submittedToday',
            'unreadToday',
            'today'
        ));
    }
}
