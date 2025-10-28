<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Grade extends Model
{
    protected $fillable = [
        'name',
    ];

    /**
     * リレーション: クラス
     */
    public function classrooms(): HasMany
    {
        return $this->hasMany(Classroom::class);
    }
}