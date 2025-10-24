<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class SampleDataSeeder extends Seeder
{
    public function run(): void
    {
        // 生徒1～10のIDを取得
        $studentIds = DB::table('users')
            ->where('role', 'student')
            ->whereIn('name', [
                '生徒001', '生徒002', '生徒003', '生徒004', '生徒005',
                '生徒006', '生徒007', '生徒008', '生徒009', '生徒010'
            ])
            ->pluck('id')
            ->all();

        // 多様な連絡帳内容のテンプレート
        $bodyTemplates = [
            '今日は体調が良く、授業も集中して取り組めました。',
            '朝から少し疲れを感じましたが、午後は元気になりました。',
            '友達と楽しく過ごせて、とても良い一日でした。',
            'テスト勉強を頑張りました。明日も頑張ります。',
            '部活動で汗を流して、気分がスッキリしました。',
            '今日は少し体調が優れませんでしたが、授業には参加できました。',
            '新しい単元の学習が面白くて、もっと勉強したいと思いました。',
            '友達と一緒に昼食を食べて、楽しい時間を過ごしました。',
            '体育の授業で体を動かして、ストレス発散できました。',
            '今日は特に何も問題なく、普通の一日でした。',
            '数学の問題が解けて、達成感を感じました。',
            '先生に質問をして、理解が深まりました。',
            'グループワークで友達と協力して取り組みました。',
            '今日は少し眠気を感じましたが、頑張って授業に参加しました。',
            '放課後に友達と勉強して、充実した時間を過ごしました。',
            '今日は特に体調に問題はありませんでした。',
            '新しい友達と話ができて、嬉しかったです。',
            '部活動の練習が厳しかったですが、良い経験になりました。',
            '今日は家でゆっくり過ごしたい気分でした。',
            '学校生活に慣れてきて、楽しく過ごせています。'
        ];

        // 各生徒について過去50日分の平日データを生成
        foreach ($studentIds as $studentId) {
            $logCount = 0;
            $dayOffset = 1;
            
            // 過去50日分の平日データを生成
            while ($logCount < 50) {
                $targetDate = Carbon::today()->subDays($dayOffset);
                
                // 平日のみ（月曜日=1 から 金曜日=5）
                if ($targetDate->dayOfWeek >= 1 && $targetDate->dayOfWeek <= 5) {
                    // 体調スコア（1-5、通常は3-5が多い）
                    $healthScore = $this->getWeightedHealthScore();
                    
                    // メンタルスコア（1-5、体調と相関性を持たせる）
                    $mentalScore = $this->getCorrelatedMentalScore($healthScore);
                    
                    // 本文をランダムに選択
                    $body = $bodyTemplates[array_rand($bodyTemplates)];
                    
                    // 体調やメンタルに応じて本文を調整
                    if ($healthScore <= 2) {
                        $body = '今日は体調が優れませんでしたが、頑張って学校に来ました。';
                    } elseif ($healthScore >= 4 && $mentalScore >= 4) {
                        $body = '今日は体調も気分も良く、充実した一日でした。';
                    }
                    
                    DB::table('daily_logs')->insert([
                        'student_id' => $studentId,
                        'target_date' => $targetDate->toDateString(),
                        'health_score' => $healthScore,
                        'mental_score' => $mentalScore,
                        'body' => $body,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                    
                    $logCount++;
                }
                
                $dayOffset++;
            }
        }
    }

    /**
     * 重み付き体調スコアを生成（3-5が多く、1-2は少ない）
     */
    private function getWeightedHealthScore(): int
    {
        $weights = [1 => 5, 2 => 10, 3 => 30, 4 => 40, 5 => 15]; // 重み
        $total = array_sum($weights);
        $random = rand(1, $total);
        
        $cumulative = 0;
        foreach ($weights as $score => $weight) {
            $cumulative += $weight;
            if ($random <= $cumulative) {
                return $score;
            }
        }
        
        return 3; // デフォルト
    }

    /**
     * 体調スコアと相関性のあるメンタルスコアを生成
     */
    private function getCorrelatedMentalScore(int $healthScore): int
    {
        // 体調が良いほどメンタルも良い傾向
        $baseScore = $healthScore;
        
        // ±1の範囲でランダムに調整
        $adjustment = rand(-1, 1);
        $mentalScore = $baseScore + $adjustment;
        
        // 1-5の範囲に収める
        return max(1, min(5, $mentalScore));
    }
}

