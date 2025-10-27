<?php

require 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

try {
    // テスト用ユーザーを作成
    $userId = DB::table('users')->insertGetId([
        'name' => 'テストユーザー',
        'email' => 'test@example.com',
        'role' => 'student',
        'password' => Hash::make('TestPass123!'),
        'email_verified_at' => now(),
        'created_at' => now(),
        'updated_at' => now(),
    ]);
    
    echo "ユーザー作成成功！ID: " . $userId . "\n";
    
    // 最新の5ユーザーを表示
    $users = DB::table('users')->orderBy('created_at', 'desc')->limit(5)->get(['id', 'name', 'email', 'role', 'created_at']);
    
    echo "\n最新のユーザー一覧:\n";
    foreach ($users as $user) {
        echo "ID: {$user->id}, 名前: {$user->name}, メール: {$user->email}, ロール: {$user->role}, 作成日: {$user->created_at}\n";
    }
    
} catch (Exception $e) {
    echo "エラー: " . $e->getMessage() . "\n";
}
