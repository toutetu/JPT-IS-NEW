<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

use App\Http\Controllers\Student\DailyLogController;

Route::middleware('auth')->group(function () {
    // 生徒の提出：作成フォーム & 保存 & 一覧
    Route::get('/student/daily-logs/create', [DailyLogController::class, 'create'])->name('student.daily_logs.create');
    Route::post('/student/daily-logs',        [DailyLogController::class, 'store'])->name('student.daily_logs.store');
    Route::get('/student/daily-logs',         [DailyLogController::class, 'index'])->name('student.daily_logs.index');
    Route::get('/student/calendar',           [DailyLogController::class, 'calendar'])->name('student.calendar');

    Route::get('/student/daily-logs/{id}', [DailyLogController::class, 'show'])->name('student.daily_logs.show');
    Route::get('/student/daily-logs/{id}/edit', [DailyLogController::class, 'edit'])->name('student.daily_logs.edit');
    Route::post('/student/daily-logs/{id}', [DailyLogController::class, 'update'])->name('student.daily_logs.update'); // 最小構成なのでPOSTで更新
});


use App\Http\Controllers\Teacher\DailyLogReviewController;

Route::middleware('auth')->group(function () {
    // 先生用：担当クラスの提出一覧（直近7日）
    Route::get('/teacher/daily-logs', [DailyLogReviewController::class, 'index'])
        ->name('teacher.daily_logs.index');

    // 先生用：詳細表示
    Route::get('/teacher/daily-logs/{id}', [DailyLogReviewController::class, 'show'])
        ->name('teacher.daily_logs.show');
        
    // 先生用：既読処理
    Route::post('/teacher/daily-logs/{id}/read', [DailyLogReviewController::class, 'read'])
        ->name('teacher.daily_logs.read');

    // 先生用：担当クラスの生徒一覧（過去記録確認用）
    Route::get('/teacher/students', [DailyLogReviewController::class, 'students'])
        ->name('teacher.students.index');

    // 先生用：特定生徒の過去記録一覧
    Route::get('/teacher/students/{studentId}/logs', [DailyLogReviewController::class, 'studentLogs'])
        ->name('teacher.students.logs');
});
use App\Http\Controllers\Admin\UserController;

Route::middleware('auth')->group(function () {
    // 管理者：ユーザー一覧・作成・削除
    Route::get('/admin/users', [UserController::class, 'index'])->name('admin.users.index');
    Route::get('/admin/users/create', [UserController::class, 'create'])->name('admin.users.create');
    Route::post('/admin/users', [UserController::class, 'store'])->name('admin.users.store');
    Route::get('/admin/users/import', [UserController::class, 'importForm'])->name('admin.users.import');
    Route::post('/admin/users/import', [UserController::class, 'importCSV'])->name('admin.users.import.csv');
    Route::get('/admin/users/{id}/delete', [UserController::class, 'delete'])->name('admin.users.delete');
    Route::delete('/admin/users/{id}', [UserController::class, 'destroy'])->name('admin.users.destroy');
});

// ログイン不要で新規ユーザーを作成するルート
Route::get('/admin/users/create-without-auth', [UserController::class, 'createWithoutAuth'])->name('admin.users.create_without_auth');
Route::post('/admin/users/create-without-auth', [UserController::class, 'storeWithoutAuth'])->name('admin.users.store_without_auth');
use App\Http\Controllers\Admin\AssignmentController;

Route::middleware('auth')->group(function () {
    // 管理者：生徒の在籍割当（enrollments）
    Route::get('/admin/assign/enrollment', [AssignmentController::class, 'enrollmentForm'])->name('admin.assign.enrollment.form');
    Route::post('/admin/assign/enrollment', [AssignmentController::class, 'enrollmentStore'])->name('admin.assign.enrollment.store');
    Route::get('/admin/assign/enrollment/import', [AssignmentController::class, 'enrollmentImportForm'])->name('admin.assign.enrollment.import');
    Route::post('/admin/assign/enrollment/import', [AssignmentController::class, 'enrollmentImportCSV'])->name('admin.assign.enrollment.import.csv');

    // 管理者：担任のクラス割当（homeroom_assignments）
    Route::get('/admin/assign/homeroom', [AssignmentController::class, 'homeroomForm'])->name('admin.assign.homeroom.form');
    Route::post('/admin/assign/homeroom', [AssignmentController::class, 'homeroomStore'])->name('admin.assign.homeroom.store');
});

Route::get('/', function () {
    return redirect()->route('login');
});