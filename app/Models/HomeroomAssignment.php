<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Builder;

class HomeroomAssignment extends Model
{
    protected $fillable = [
        'teacher_id',
        'classroom_id',
        'since_date',
        'until_date',
    ];

    protected $casts = [
        'since_date' => 'date',
        'until_date' => 'date',
    ];

    /**
     * リレーション: 教師
     */
    public function teacher(): BelongsTo
    {
        return $this->belongsTo(User::class, 'teacher_id');
    }

    /**
     * リレーション: クラス
     */
    public function classroom(): BelongsTo
    {
        return $this->belongsTo(Classroom::class);
    }

    /**
     * スコープ: 現在有効な担当のみ（until_date が null）
     */
    public function scopeCurrent(Builder $query): Builder
    {
        return $query->whereNull('until_date');
    }

    /**
     * スコープ: 過去の担当のみ
     */
    public function scopePast(Builder $query): Builder
    {
        return $query->whereNotNull('until_date');
    }

    /**
     * スコープ: 特定の教師の担当
     */
    public function scopeForTeacher(Builder $query, int $teacherId): Builder
    {
        return $query->where('teacher_id', $teacherId);
    }

    /**
     * スコープ: 特定のクラスの担当
     */
    public function scopeForClassroom(Builder $query, int $classroomId): Builder
    {
        return $query->where('classroom_id', $classroomId);
    }

    /**
     * 教師の現在の担当クラスを取得
     */
    public static function getCurrentForTeacher(int $teacherId): ?static
    {
        return static::forTeacher($teacherId)->current()->first();
    }

    /**
     * 教師の担当クラスの生徒ID一覧を取得
     */
    public static function getStudentIdsForTeacher(int $teacherId): \Illuminate\Support\Collection
    {
        return static::forTeacher($teacherId)
            ->current()
            ->with(['classroom.enrollments' => function ($query) {
                $query->where('is_active', true);
            }])
            ->get()
            ->flatMap(function ($assignment) {
                return $assignment->classroom->enrollments->pluck('student_id');
            })
            ->unique()
            ->values();
    }

    /**
     * 教師の担当クラス情報を取得
     */
    public static function getAssignedClassesForTeacher(int $teacherId)
    {
        return static::forTeacher($teacherId)
            ->current()
            ->with(['classroom.grade'])
            ->get()
            ->map(function ($assignment) {
                return [
                    'classroom_id' => $assignment->classroom_id,
                    'classroom_name' => $assignment->classroom->name,
                    'grade_name' => $assignment->classroom->grade->name,
                ];
            });
    }

    /**
     * 教師のクラス担当を変更（既存を終了して新規作成）
     */
    public static function changeTeacherAssignment(int $teacherId, int $classroomId): static
    {
        // 既存の現在の担当を終了する
        static::forTeacher($teacherId)
            ->current()
            ->update([
                'until_date' => now()->toDateString(),
            ]);

        // 新しい担当を作成
        return static::create([
            'teacher_id' => $teacherId,
            'classroom_id' => $classroomId,
            'since_date' => now()->toDateString(),
        ]);
    }

    /**
     * 特定の生徒が教師の担当クラスに属するかチェック
     */
    public static function isStudentUnderTeacher(int $studentId, int $teacherId): bool
    {
        return static::forTeacher($teacherId)
            ->current()
            ->whereHas('classroom.enrollments', function ($query) use ($studentId) {
                $query->where('student_id', $studentId)
                      ->where('is_active', true);
            })
            ->exists();
    }
}