<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class InternshipTestSeeder extends Seeder
{
    public function run(): void
    {
        // === 管理者アカウント ===
        DB::table('users')->insert([
            'name' => 'システム管理者',
            'email' => 'admin@example.com',
            'password' => Hash::make('Passw0rd!'),
            'role' => 'admin',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // === 担任アカウント（6名） ===
        $teachers = [
            ['name' => '田中先生', 'email' => 'teacher1@example.com', 'class' => '1年A組'],
            ['name' => '佐藤先生', 'email' => 'teacher2@example.com', 'class' => '1年B組'],
            ['name' => '鈴木先生', 'email' => 'teacher3@example.com', 'class' => '2年A組'],
            ['name' => '高橋先生', 'email' => 'teacher4@example.com', 'class' => '2年B組'],
            ['name' => '山田先生', 'email' => 'teacher5@example.com', 'class' => '3年A組'],
            ['name' => '渡辺先生', 'email' => 'teacher6@example.com', 'class' => '3年B組'],
        ];

        foreach ($teachers as $teacher) {
            DB::table('users')->insert([
                'name' => $teacher['name'],
                'email' => $teacher['email'],
                'password' => Hash::make('Passw0rd!'),
                'role' => 'teacher',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // === 学年データ ===
        for ($g = 1; $g <= 3; $g++) {
            DB::table('grades')->insert([
                'id' => $g,
                'name' => "{$g}年",
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // === クラスデータ ===
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

        // === 生徒アカウント（21名） ===
        $students = [
            // 1年A組
            ['name' => '山田太郎', 'email' => 'student001@example.com', 'class' => '1年A組'],
            ['name' => '佐藤花子', 'email' => 'student002@example.com', 'class' => '1年A組'],
            ['name' => '田中一郎', 'email' => 'student003@example.com', 'class' => '1年A組'],
            ['name' => '小林未提出', 'email' => 'student004@example.com', 'class' => '1年A組'],
            // 1年B組
            ['name' => '鈴木美咲', 'email' => 'student005@example.com', 'class' => '1年B組'],
            ['name' => '高橋健太', 'email' => 'student006@example.com', 'class' => '1年B組'],
            ['name' => '渡辺さくら', 'email' => 'student007@example.com', 'class' => '1年B組'],
            ['name' => '中島未提出', 'email' => 'student008@example.com', 'class' => '1年B組'],
            // 2年A組
            ['name' => '伊藤大輔', 'email' => 'student009@example.com', 'class' => '2年A組'],
            ['name' => '加藤由美', 'email' => 'student010@example.com', 'class' => '2年A組'],
            ['name' => '林直樹', 'email' => 'student011@example.com', 'class' => '2年A組'],
            ['name' => '西村未提出', 'email' => 'student012@example.com', 'class' => '2年A組'],
            // 2年B組
            ['name' => '森田あい', 'email' => 'student013@example.com', 'class' => '2年B組'],
            ['name' => '石川雄一', 'email' => 'student014@example.com', 'class' => '2年B組'],
            ['name' => '中村みどり', 'email' => 'student015@example.com', 'class' => '2年B組'],
            ['name' => '東田未提出', 'email' => 'student016@example.com', 'class' => '2年B組'],
            // 3年A組
            ['name' => '木村拓也', 'email' => 'student017@example.com', 'class' => '3年A組'],
            ['name' => '清水恵', 'email' => 'student018@example.com', 'class' => '3年A組'],
            ['name' => '松本慎一', 'email' => 'student019@example.com', 'class' => '3年A組'],
            ['name' => '北川未提出', 'email' => 'student020@example.com', 'class' => '3年A組'],
            // 3年B組
            ['name' => '井上麻衣', 'email' => 'student021@example.com', 'class' => '3年B組'],
            ['name' => '岡田翔太', 'email' => 'student022@example.com', 'class' => '3年B組'],
            ['name' => '小川優子', 'email' => 'student023@example.com', 'class' => '3年B組'],
            ['name' => '南野未提出', 'email' => 'student024@example.com', 'class' => '3年B組'],
        ];

        foreach ($students as $student) {
            DB::table('users')->insert([
                'name' => $student['name'],
                'email' => $student['email'],
                'password' => Hash::make('Passw0rd!'),
                'role' => 'student',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // === 在籍データ ===
        $studentIds = DB::table('users')->where('role', 'student')->pluck('id')->all();
        $classroomIds = DB::table('classrooms')->pluck('id')->all();
        
        foreach ($studentIds as $idx => $studentId) {
            DB::table('enrollments')->insert([
                'student_id' => $studentId,
                'classroom_id' => $classroomIds[$idx % count($classroomIds)],
                'is_active' => true,
                'since_date' => now()->subMonths(1),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // === 担任割当 ===
        $teacherIds = DB::table('users')->where('role', 'teacher')->pluck('id')->all();
        foreach ($classroomIds as $idx => $classroomId) {
            DB::table('homeroom_assignments')->insert([
                'teacher_id' => $teacherIds[$idx % count($teacherIds)],
                'classroom_id' => $classroomId,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // === テスト用連絡帳データ ===
        $this->createTestDailyLogs();
    }

    private function createTestDailyLogs(): void
    {
        $studentIds = DB::table('users')->where('role', 'student')->pluck('id')->all();
        
        // 各生徒に異なるパターンのデータを生成
        foreach ($studentIds as $index => $studentId) {
            $this->createStudentLogs($studentId, $index);
        }
    }

    private function createStudentLogs($studentId, $studentIndex): void
    {
        $patterns = [
            // パターン1: 完璧な生徒（毎日提出）
            'perfect' => ['frequency' => 1.0, 'health_range' => [4, 5], 'mental_range' => [4, 5]],
            // パターン2: 普通の生徒（80%提出）
            'normal' => ['frequency' => 0.8, 'health_range' => [3, 5], 'mental_range' => [3, 5]],
            // パターン3: 体調不良の生徒（60%提出、体調低め）
            'sick' => ['frequency' => 0.6, 'health_range' => [1, 3], 'mental_range' => [2, 4]],
            // パターン4: 未提出の生徒（0%提出）
            'no_submission' => ['frequency' => 0.0, 'health_range' => [1, 5], 'mental_range' => [1, 5]],
            // パターン5: メンタル不調の生徒（70%提出、メンタル低め）
            'mental' => ['frequency' => 0.7, 'health_range' => [2, 4], 'mental_range' => [1, 3]],
            // パターン6: 不規則な生徒（50%提出）
            'irregular' => ['frequency' => 0.5, 'health_range' => [2, 5], 'mental_range' => [2, 5]],
            // パターン7: 新入生（最近始めた）
            'new' => ['frequency' => 0.9, 'health_range' => [3, 5], 'mental_range' => [3, 5]],
        ];

        $patternKeys = array_keys($patterns);
        $pattern = $patterns[$patternKeys[$studentIndex % count($patternKeys)]];

        // 過去30日分の平日データを生成
        $dayOffset = 1;
        $submittedCount = 0;
        $targetCount = 30;

        while ($submittedCount < $targetCount) {
            $targetDate = Carbon::today()->subDays($dayOffset);
            
            // 平日のみ
            if ($targetDate->dayOfWeek >= 1 && $targetDate->dayOfWeek <= 5) {
                // 提出確率に基づいて決定
                if (rand(1, 100) <= $pattern['frequency'] * 100) {
                    $healthScore = rand($pattern['health_range'][0], $pattern['health_range'][1]);
                    $mentalScore = rand($pattern['mental_range'][0], $pattern['mental_range'][1]);
                    
                    // パターンに応じた本文を生成
                    $body = $this->generateBody($pattern, $healthScore, $mentalScore, $targetDate);
                    
                    DB::table('daily_logs')->insert([
                        'student_id' => $studentId,
                        'target_date' => $targetDate->toDateString(),
                        'health_score' => $healthScore,
                        'mental_score' => $mentalScore,
                        'body' => $body,
                        'submitted_at' => $targetDate->addHours(rand(8, 16)), // 提出時間をランダムに
                        'read_at' => rand(1, 3) <= 2 ? $targetDate->addHours(rand(1, 24)) : null, // 80%の確率で既読
                        'read_by' => rand(1, 3) <= 2 ? DB::table('users')->where('role', 'teacher')->inRandomOrder()->first()->id : null,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                    
                    $submittedCount++;
                }
            }
            
            $dayOffset++;
        }
    }

    private function generateBody($pattern, $healthScore, $mentalScore, $targetDate): string
    {
        $bodies = [
            'perfect' => [
                '今日も元気に学校に来ました。',
                '授業が楽しくて充実した一日でした。',
                '友達と楽しく過ごせました。',
                '新しいことを学べて嬉しいです。',
            ],
            'normal' => [
                '普通の一日でした。',
                '特に問題なく過ごせました。',
                '授業も集中して取り組めました。',
                '友達とも仲良く過ごせました。',
            ],
            'sick' => [
                '少し体調が優れませんでした。',
                '頭痛がして集中できませんでした。',
                '体調不良で早退しました。',
                '風邪気味で調子が悪いです。',
            ],
            'no_submission' => [
                'まだ一度も提出していません。',
                '連絡帳の提出を忘れています。',
                '提出方法が分かりません。',
                '提出する気がありません。',
            ],
            'mental' => [
                '気分が沈んでいます。',
                '最近元気が出ません。',
                '友達関係で悩んでいます。',
                '勉強のプレッシャーを感じています。',
            ],
            'irregular' => [
                '今日は学校を休みました。',
                '遅刻してしまいました。',
                '授業に集中できませんでした。',
                '最近学校に行くのが億劫です。',
            ],
            'new' => [
                '新しい学校生活に慣れてきました。',
                '友達ができて嬉しいです。',
                '授業が楽しいです。',
                '毎日が新鮮で充実しています。',
            ],
        ];

        $patternKey = array_search($pattern, [
            'perfect' => ['frequency' => 1.0, 'health_range' => [4, 5], 'mental_range' => [4, 5]],
            'normal' => ['frequency' => 0.8, 'health_range' => [3, 5], 'mental_range' => [3, 5]],
            'sick' => ['frequency' => 0.6, 'health_range' => [1, 3], 'mental_range' => [2, 4]],
            'mental' => ['frequency' => 0.7, 'health_range' => [2, 4], 'mental_range' => [1, 3]],
            'irregular' => ['frequency' => 0.5, 'health_range' => [2, 5], 'mental_range' => [2, 5]],
            'new' => ['frequency' => 0.9, 'health_range' => [3, 5], 'mental_range' => [3, 5]],
        ]);

        $patternBodies = $bodies[$patternKey];
        $baseBody = $patternBodies[array_rand($patternBodies)];
        
        return "{$baseBody} ({$targetDate->format('Y-m-d')})";
    }
}
