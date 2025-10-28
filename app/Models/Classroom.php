<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Builder;

class Classroom extends Model
{
    protected $fillable = [
        'name',
        'grade_id',
    ];

    /**
     * リレーション: 学年
     */
    public function grade(): BelongsTo
    {
        return $this->belongsTo(Grade::class);
    }

    /**
     * リレーション: 在籍情報
     */
    public function enrollments(): HasMany
    {
        return $this->hasMany(Enrollment::class);
    }

    /**
     * リレーション: 担任割り当て
     */
    public function homeroomAssignments(): HasMany
    {
        return $this->hasMany(HomeroomAssignment::class);
    }

    /**
     * スコープ: 特定の学年のクラス
     */
    public function scopeForGrade(Builder $query, int $gradeId): Builder
    {
        return $query->where('grade_id', $gradeId);
    }

    /**
     * スコープ: 名前で検索
     */
    public function scopeByName(Builder $query, string $name): Builder
    {
        return $query->where('name', $name);
    }

    /**
     * クラス名で検索して取得
     */
    public static function findByName(string $name): ?static
    {
        return static::byName($name)->first();
    }

    /**
     * 現在の在籍生徒数を取得
     */
    public function getCurrentStudentCountAttribute(): int
    {
        return $this->enrollments()->active()->count();
    }

    /**
     * 現在の担任を取得
     */
    public function getCurrentTeacherAttribute(): ?User
    {
        return $this->homeroomAssignments()
            ->current()
            ->with('teacher')
            ->first()
            ?->teacher;
    }
}