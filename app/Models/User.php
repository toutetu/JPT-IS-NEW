<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasMany;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * リレーション: 生徒の在籍情報
     */
    public function enrollments(): HasMany
    {
        return $this->hasMany(Enrollment::class, 'student_id');
    }

    /**
     * リレーション: 担任のクラス割り当て
     */
    public function homeroomAssignments(): HasMany
    {
        return $this->hasMany(HomeroomAssignment::class, 'teacher_id');
    }

    /**
     * リレーション: 生徒の日報
     */
    public function dailyLogs(): HasMany
    {
        return $this->hasMany(DailyLog::class, 'student_id');
    }

    /**
     * スコープ: ロールで絞り込み
     */
    public function scopeByRole($query, string $role)
    {
        return $query->where('role', $role);
    }

    /**
     * スコープ: 生徒のみ
     */
    public function scopeStudents($query)
    {
        return $query->where('role', 'student');
    }

    /**
     * スコープ: 教師のみ
     */
    public function scopeTeachers($query)
    {
        return $query->where('role', 'teacher');
    }

    /**
     * スコープ: 管理者のみ
     */
    public function scopeAdmins($query)
    {
        return $query->where('role', 'admin');
    }

    /**
     * スコープ: 名前またはメールで検索
     */
    public function scopeSearch($query, string $search)
    {
        return $query->where(function ($q) use ($search) {
            $q->where('name', 'like', "%{$search}%")
              ->orWhere('email', 'like', "%{$search}%");
        });
    }

    /**
     * 現在の在籍クラス名を取得（生徒用）
     */
    public function getCurrentClassroomNameAttribute(): ?string
    {
        if ($this->role !== 'student') {
            return null;
        }

        return $this->enrollments()
            ->where('is_active', true)
            ->with('classroom')
            ->first()
            ?->classroom?->name;
    }

    /**
     * 現在の担当クラス名を取得（教師用）
     */
    public function getCurrentAssignedClassroomNameAttribute(): ?string
    {
        if ($this->role !== 'teacher') {
            return null;
        }

        return $this->homeroomAssignments()
            ->whereNull('until_date')
            ->with('classroom')
            ->first()
            ?->classroom?->name;
    }

    /**
     * 自分自身かどうかをチェック
     */
    public function isSelf(): bool
    {
        return $this->id === auth()->id();
    }

    /**
     * 管理者かどうかをチェック
     */
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    /**
     * 生徒かどうかをチェック
     */
    public function isStudent(): bool
    {
        return $this->role === 'student';
    }

    /**
     * 教師かどうかをチェック
     */
    public function isTeacher(): bool
    {
        return $this->role === 'teacher';
    }
}