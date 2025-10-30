<?php

namespace App\Services;

use App\Models\DailyLog;
use App\Models\User;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class DailyLogService
{
    /**
     * 日報を作成
     */
    public function createDailyLog(int $studentId, array $data): DailyLog
    {
        // 重複チェック
        if (DailyLog::existsForStudentAndDate($studentId, $data['target_date'])) {
            throw new \Exception('この対象日では既に提出済みです。');
        }

        return DailyLog::create([
            'student_id' => $studentId,
            'target_date' => $data['target_date'],
            'health_score' => $data['health_score'],
            'mental_score' => $data['mental_score'],
            'body' => $data['body'],
            'submitted_at' => now(),
        ]);
    }
    /**
     * 日報を更新
     */
    public function updateDailyLog(int $logId, int $studentId, array $data): DailyLog
    {
        $log = DailyLog::forStudent($studentId)->findOrFail($logId);

        if (!$log->isEditable()) {
            throw new \Exception('既読の記録は修正できません。');
        }

        // 重複チェック（自分以外）
        $otherLog = DailyLog::findOtherForStudentAndDate($studentId, $data['target_date'], $logId);
        if ($otherLog) {
            throw new \Exception('この対象日では既に別の提出があります。');
        }

        $log->update([
            'target_date' => $data['target_date'],
            'health_score' => $data['health_score'],
            'mental_score' => $data['mental_score'],
            'body' => $data['body'],
        ]);

        return $log;
    }

    /**
     * 生徒の日報一覧を取得
     */
    public function getStudentDailyLogs(int $studentId, int $perPage = 10)
    {
        return DailyLog::forStudent($studentId)
            ->orderByDesc('target_date')
            ->paginate($perPage);
    }

    /**
     * 生徒の特定日付の日報を取得
     */
    public function getStudentDailyLogForDate(int $studentId, string $date): ?DailyLog
    {
        return DailyLog::forStudent($studentId)->forDate($date)->first();
    }

    /**
     * 教師の担当クラスの日報一覧を取得
     */
    public function getTeacherClassDailyLogs(int $teacherId, string $date = null)
    {
        $date = $date ?: $this->getPreviousSchoolDay();

        $studentIds = \App\Models\HomeroomAssignment::getStudentIdsForTeacher($teacherId);

        return DailyLog::whereIn('student_id', $studentIds)
            ->forDate($date)
            ->with('student')
            ->orderBy('student_id')
            ->paginate(20);
    }

    /**
     * 教師の担当クラスの提出状況を取得
     */
    public function getTeacherClassSubmissionStats(int $teacherId, string $date = null)
    {
        $date = $date ?: $this->getPreviousSchoolDay();
        $studentIds = \App\Models\HomeroomAssignment::getStudentIdsForTeacher($teacherId);

        $totalStudents = $studentIds->count();
        $submittedCount = DailyLog::whereIn('student_id', $studentIds)
            ->forDate($date)
            ->count();
        $unreadCount = DailyLog::whereIn('student_id', $studentIds)
            ->forDate($date)
            ->unread()
            ->count();

        // 未提出者
        $submittedStudentIds = DailyLog::whereIn('student_id', $studentIds)
            ->forDate($date)
            ->pluck('student_id')
            ->unique();

        $unsubmitted = User::whereIn('id', $studentIds->diff($submittedStudentIds))
            ->orderBy('name')
            ->get(['id', 'name', 'email']);

        return [
            'total_students' => $totalStudents,
            'submitted_count' => $submittedCount,
            'unread_count' => $unreadCount,
            'unsubmitted' => $unsubmitted,
        ];
    }

    /**
     * 日報を既読にする
     */
    public function markAsRead(int $logId, int $teacherId): DailyLog
    {
        $log = DailyLog::findOrFail($logId);
        $log->markAsRead($teacherId);
        return $log;
    }

    /**
     * 特定生徒の過去の日報一覧を取得
     */
    public function getStudentPastLogs(int $studentId, string $dateFrom = null, string $dateTo = null, int $perPage = 20)
    {
        $query = DailyLog::forStudent($studentId)->orderByDesc('target_date');

        if ($dateFrom) {
            $query->where('target_date', '>=', $dateFrom);
        } else {
            // デフォルトは過去30日
            $query->pastDays(30);
        }

        if ($dateTo) {
            $query->where('target_date', '<=', $dateTo);
        }

        return $query->paginate($perPage);
    }

    /**
     * 前登校日を計算
     */
    public function getPreviousSchoolDay(): string
    {
        $today = Carbon::today();
        $dayOfWeek = $today->dayOfWeek; // 0=日曜日, 1=月曜日, ..., 6=土曜日
        
        if ($dayOfWeek == 1) { // 月曜日
            return $today->subDays(3)->toDateString(); // 前の金曜日
        } elseif ($dayOfWeek == 0) { // 日曜日
            return $today->subDays(2)->toDateString(); // 前の金曜日
        } elseif ($dayOfWeek == 6) { // 土曜日
            return $today->subDays(1)->toDateString(); // 前の金曜日
        } else { // 火曜日〜金曜日
            return $today->subDay()->toDateString(); // 前日
        }
    }

    /**
     * 生徒の提出済み日付一覧を取得（カレンダー用）
     */
    public function getSubmittedDatesForCalendar(int $studentId, int $year, int $month): array
    {
        $startDate = Carbon::create($year, $month, 1)->startOfMonth();
        $endDate = $startDate->copy()->endOfMonth();

        // target_date は Model 側で date キャストされているため、pluck すると Carbon インスタンスになる。
        // カレンダー側では文字列比較（Y-m-d）を行うため、ここで日付文字列へ正規化する。
        return DailyLog::forStudent($studentId)
            ->dateRange($startDate->toDateString(), $endDate->toDateString())
            ->pluck('target_date')
            ->map(function ($date) {
                return \Carbon\Carbon::parse($date)->toDateString();
            })
            ->toArray();
    }

    /**
     * 教師の今日の提出状況サマリーを取得
     */
    public function getTeacherTodayStats(int $teacherId): array
    {
        $today = now()->toDateString();
        $studentIds = \App\Models\HomeroomAssignment::getStudentIdsForTeacher($teacherId);

        $totalStudents = $studentIds->count();
        $submittedToday = DailyLog::whereIn('student_id', $studentIds)
            ->forDate($today)
            ->count();
        $unreadToday = DailyLog::whereIn('student_id', $studentIds)
            ->forDate($today)
            ->unread()
            ->count();

        return [
            'total_students' => $totalStudents,
            'submitted_today' => $submittedToday,
            'unread_today' => $unreadToday,
            'today' => $today,
        ];
    }
}
