<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Builder;

class Enrollment extends Model
{
    protected $fillable = [
        'student_id',
        'classroom_id',
        'is_active',
        'since_date',
        'until_date',
    ];

    protected $casts = [
        'since_date' => 'date',
        'until_date' => 'date',
        'is_active' => 'boolean',
    ];

    /**
     * リレーション: 生徒
     */
    public function student(): BelongsTo
    {
        return $this->belongsTo(User::class, 'student_id');
    }

    /**
     * リレーション: クラス
     */
    public function classroom(): BelongsTo
    {
        return $this->belongsTo(Classroom::class);
    }

    /**
     * スコープ: アクティブな在籍のみ
     */
    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    /**
     * スコープ: 非アクティブな在籍のみ
     */
    public function scopeInactive(Builder $query): Builder
    {
        return $query->where('is_active', false);
    }

    /**
     * スコープ: 特定の生徒の在籍
     */
    public function scopeForStudent(Builder $query, int $studentId): Builder
    {
        return $query->where('student_id', $studentId);
    }

    /**
     * スコープ: 特定のクラスの在籍
     */
    public function scopeForClassroom(Builder $query, int $classroomId): Builder
    {
        return $query->where('classroom_id', $classroomId);
    }

    /**
     * 生徒の現在の在籍を取得
     */
    public static function getCurrentForStudent(int $studentId): ?static
    {
        return static::forStudent($studentId)->active()->first();
    }

    /**
     * クラスの現在の在籍生徒を取得
     */
    public static function getCurrentStudentsForClassroom(int $classroomId)
    {
        return static::forClassroom($classroomId)
            ->active()
            ->with('student')
            ->get()
            ->pluck('student');
    }

    /**
     * 生徒の在籍を変更（既存を非アクティブにして新規作成）
     */
    public static function changeStudentEnrollment(int $studentId, int $classroomId): static
    {
        // 既存のアクティブな在籍を非アクティブにする
        static::forStudent($studentId)
            ->active()
            ->update([
                'is_active' => false,
                'until_date' => now()->toDateString(),
            ]);

        // 新しい在籍を作成
        return static::create([
            'student_id' => $studentId,
            'classroom_id' => $classroomId,
            'is_active' => true,
            'since_date' => now()->toDateString(),
        ]);
    }
}