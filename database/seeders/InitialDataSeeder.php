<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class InitialDataSeeder extends Seeder
{
    public function run(): void
    {
        // === 管理者 ===
        DB::table('users')->insert([
            'name' => 'Admin',
            'email' => 'admin@example.com',
            'password' => Hash::make('Passw0rd!'),
            'role' => 'admin',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // === 担任 8名 ===
        for ($i = 1; $i <= 8; $i++) {
            DB::table('users')->insert([
                'name' => "担任{$i}",
                'email' => "teacher{$i}@example.com",
                'password' => Hash::make('Passw0rd!'),
                'role' => 'teacher',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // === 生徒 30名 ===
        for ($i = 1; $i <= 30; $i++) {
            $num = str_pad($i, 3, '0', STR_PAD_LEFT);
            DB::table('users')->insert([
                'name' => "生徒{$num}",
                'email' => "student{$num}@example.com",
                'password' => Hash::make('Passw0rd!'),
                'role' => 'student',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // === 学年 ===
        for ($g = 1; $g <= 3; $g++) {
            DB::table('grades')->insert([
                'id' => $g,
                'name' => "{$g}年",
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // === クラス（A/B） ===
        $classrooms = [];
        $id = 1;
        foreach ([1, 2, 3] as $gradeId) {
            foreach (['A', 'B'] as $sec) {
                $classrooms[] = [
                    'id' => $id++,
                    'grade_id' => $gradeId,
                    'name' => "{$gradeId}年{$sec}組",
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
        }
        DB::table('classrooms')->insert($classrooms);

        // === 在籍（1〜30人をクラスに均等配分） ===
        $students = DB::table('users')->where('role', 'student')->pluck('id')->all();
        $classes = DB::table('classrooms')->pluck('id')->all();
        foreach ($students as $idx => $sid) {
            DB::table('enrollments')->insert([
                'student_id' => $sid,
                'classroom_id' => $classes[$idx % count($classes)],
                'is_active' => true,
                'since_date' => now()->subMonths(1),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // === 担任割当（8人の担任を各クラスに順番で） ===
        $teachers = DB::table('users')->where('role', 'teacher')->pluck('id')->all();
        foreach ($classes as $idx => $cid) {
            DB::table('homeroom_assignments')->insert([
                'teacher_id' => $teachers[$idx % count($teachers)],
                'classroom_id' => $cid,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // === 過去20日分のダミー提出 ===
        $targetStudents = DB::table('users')
            ->where('role', 'student')
            ->inRandomOrder()
            ->limit(10)
            ->pluck('id')
            ->all();

        foreach ($targetStudents as $sid) {
            $logCount = 0;
            $dayOffset = 1;
            
            // 過去20日分の平日データを生成
            while ($logCount < 20) {
                $target = Carbon::today()->subDays($dayOffset);
                
                // 平日のみ（月曜日=1 から 金曜日=5）
                if ($target->dayOfWeek >= 1 && $target->dayOfWeek <= 5) {
                    DB::table('daily_logs')->insert([
                        'student_id' => $sid,
                        'target_date' => $target->toDateString(),
                        'health_score' => rand(3, 5),
                        'mental_score' => rand(2, 5),
                        'body' => "サンプル記録 {$target->format('Y-m-d')}：授業や家庭学習についてのコメント。",
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                    
                    $logCount++;
                }
                
                $dayOffset++;
            }
        }
    }
}
