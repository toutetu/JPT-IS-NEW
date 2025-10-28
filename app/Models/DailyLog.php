<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Builder;

class DailyLog extends Model
{
    protected $fillable = [
        'student_id',
        'target_date',
        'health_score',
        'mental_score',
        'body',
        'submitted_at',
        'read_at',
        'read_by',
    ];

    protected $casts = [
        'target_date' => 'date',
        'submitted_at' => 'datetime',
        'read_at' => 'datetime',
    ];

    /**
     * リレーション: 生徒
     */
    public function student(): BelongsTo
    {
        return $this->belongsTo(User::class, 'student_id');
    }

    /**
     * リレーション: 既読した教師
     */
    public function readByTeacher(): BelongsTo
    {
        return $this->belongsTo(User::class, 'read_by');
    }

    /**
     * スコープ: 特定の生徒の日報
     */
    public function scopeForStudent(Builder $query, int $studentId): Builder
    {
        return $query->where('student_id', $studentId);
    }

    /**
     * スコープ: 特定の日付の日報
     */
    public function scopeForDate(Builder $query, string $date): Builder
    {
        return $query->where('target_date', $date);
    }

    /**
     * スコープ: 未読の日報
     */
    public function scopeUnread(Builder $query): Builder
    {
        return $query->whereNull('read_at');
    }

    /**
     * スコープ: 既読の日報
     */
    public function scopeRead(Builder $query): Builder
    {
        return $query->whereNotNull('read_at');
    }

    /**
     * スコープ: 日付範囲で絞り込み
     */
    public function scopeDateRange(Builder $query, string $from, string $to): Builder
    {
        return $query->whereBetween('target_date', [$from, $to]);
    }

    /**
     * スコープ: 過去N日間
     */
    public function scopePastDays(Builder $query, int $days): Builder
    {
        $fromDate = now()->subDays($days)->toDateString();
        return $query->where('target_date', '>=', $fromDate);
    }

    /**
     * 特定の生徒の特定日付の日報が存在するかチェック
     */
    public static function existsForStudentAndDate(int $studentId, string $date): bool
    {
        return static::forStudent($studentId)->forDate($date)->exists();
    }

    /**
     * 特定の生徒の特定日付の日報を取得（自分以外）
     */
    public static function findOtherForStudentAndDate(int $studentId, string $date, int $excludeId): ?static
    {
        return static::forStudent($studentId)
            ->forDate($date)
            ->where('id', '!=', $excludeId)
            ->first();
    }

    /**
     * 既読済みかどうか
     */
    public function isRead(): bool
    {
        return !is_null($this->read_at);
    }

    /**
     * 編集可能かどうか（未読の場合のみ編集可能）
     */
    public function isEditable(): bool
    {
        return !$this->isRead();
    }

    /**
     * 既読にする
     */
    public function markAsRead(int $teacherId): void
    {
        $this->update([
            'read_at' => now(),
            'read_by' => $teacherId,
        ]);
    }
}