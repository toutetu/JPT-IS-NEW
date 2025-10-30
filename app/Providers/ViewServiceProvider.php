<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Services\DailyLogService;

class ViewServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        // ナビゲーションバー用のデータを提供
        View::composer('layouts.app', function ($view) {
            if (Auth::check() && Auth::user()->role === 'teacher') {
                // この先生の担当クラス情報を取得
                $assignedClasses = DB::table('homeroom_assignments')
                    ->join('classrooms', 'classrooms.id', '=', 'homeroom_assignments.classroom_id')
                    ->join('grades', 'grades.id', '=', 'classrooms.grade_id')
                    ->where('homeroom_assignments.teacher_id', Auth::id())
                    ->whereNull('homeroom_assignments.until_date') // 現在有効な担当のみ
                    ->select(
                        'classrooms.name as classroom_name',
                        'grades.name as grade_name'
                    )
                    ->orderBy('grades.id')
                    ->orderBy('classrooms.name')
                    ->get();

                // クラス名を結合して表示用文字列を作成
                $classNames = $assignedClasses->map(function ($class) {
                    return $class->classroom_name; // 学年名は削除（クラス名に既に含まれている）
                })->implode('・');

                $view->with('teacherAssignedClasses', $classNames);
            }
        });

        // 生徒ビュー共通: 所属情報（学年・クラス・担任）を提供
        View::composer('student.*', function ($view) {
            if (Auth::check() && Auth::user()->role === 'student') {
                $service = app(DailyLogService::class);
                $aff = $service->getStudentAffiliation(Auth::id());
                $view->with([
                    'classroomName' => $aff['classroom_name'] ?? null,
                    'gradeName' => $aff['grade_name'] ?? null,
                    'homeroomTeacherName' => $aff['homeroom_teacher_name'] ?? null,
                ]);
            }
        });
    }
}
