<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class TeacherTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        
        // テスト用データの作成
        $this->createTestData();
    }

    private function createTestData(): void
    {
        // 管理者作成
        User::create([
            'name' => 'Admin',
            'email' => 'admin@example.com',
            'password' => bcrypt('Passw0rd!'),
            'role' => 'admin',
        ]);

        // 担任作成
        User::create([
            'name' => '田中先生',
            'email' => 'teacher1@example.com',
            'password' => bcrypt('Passw0rd!'),
            'role' => 'teacher',
        ]);

        // 生徒作成
        User::create([
            'name' => '山田太郎',
            'email' => 'student001@example.com',
            'password' => bcrypt('Passw0rd!'),
            'role' => 'student',
        ]);

        User::create([
            'name' => '小林未提出',
            'email' => 'student004@example.com',
            'password' => bcrypt('Passw0rd!'),
            'role' => 'student',
        ]);

        // 学年・クラス・在籍データの作成
        $this->createSchoolData();
    }

    private function createSchoolData(): void
    {
        // 学年
        DB::table('grades')->insert([
            ['id' => 1, 'name' => '1年', 'created_at' => now(), 'updated_at' => now()],
        ]);

        // クラス
        DB::table('classrooms')->insert([
            ['id' => 1, 'grade_id' => 1, 'name' => '1年A組', 'created_at' => now(), 'updated_at' => now()],
        ]);

        // 在籍
        DB::table('enrollments')->insert([
            ['student_id' => 3, 'classroom_id' => 1, 'is_active' => true, 'since_date' => now()->subMonths(1), 'created_at' => now(), 'updated_at' => now()],
            ['student_id' => 4, 'classroom_id' => 1, 'is_active' => true, 'since_date' => now()->subMonths(1), 'created_at' => now(), 'updated_at' => now()],
        ]);

        // 担任割当
        DB::table('homeroom_assignments')->insert([
            ['teacher_id' => 2, 'classroom_id' => 1, 'created_at' => now(), 'updated_at' => now()],
        ]);

        // 連絡帳データ
        DB::table('daily_logs')->insert([
            [
                'student_id' => 3,
                'target_date' => now()->toDateString(),
                'health_score' => 4,
                'mental_score' => 3,
                'body' => '今日の記録',
                'submitted_at' => now(),
                'read_at' => null,
                'read_by' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'student_id' => 3,
                'target_date' => now()->subDays(1)->toDateString(),
                'health_score' => 5,
                'mental_score' => 4,
                'body' => '昨日の記録',
                'submitted_at' => now()->subDays(1),
                'read_at' => now()->subDays(1)->addHours(2),
                'read_by' => 2,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }

    /** @test */
    public function teacher_login_redirects_to_daily_logs()
    {
        $response = $this->post('/login', [
            'email' => 'teacher1@example.com',
            'password' => 'Passw0rd!',
        ]);

        $response->assertRedirect('/teacher/daily-logs');
    }

    /** @test */
    public function teacher_can_view_daily_logs_index()
    {
        $user = User::where('email', 'teacher1@example.com')->first();
        $this->actingAs($user);

        $response = $this->get('/teacher/daily-logs');

        $response->assertStatus(200);
        $response->assertSee('提出状況（担当クラス）');
        $response->assertSee('対象生徒数');
        $response->assertSee('提出済');
        $response->assertSee('未提出');
        $response->assertSee('未読');
    }

    /** @test */
    public function teacher_can_filter_by_date()
    {
        $user = User::where('email', 'teacher1@example.com')->first();
        $this->actingAs($user);

        $response = $this->get('/teacher/daily-logs?date=' . now()->subDays(1)->toDateString());

        $response->assertStatus(200);
        $response->assertSee('昨日の記録');
    }

    /** @test */
    public function teacher_can_view_daily_log_detail()
    {
        $user = User::where('email', 'teacher1@example.com')->first();
        $this->actingAs($user);

        $log = DB::table('daily_logs')->where('student_id', 3)->first();

        $response = $this->get("/teacher/daily-logs/{$log->id}");

        $response->assertStatus(200);
        $response->assertSee('提出内容（詳細）');
        $response->assertSee('今日の記録');
    }

    /** @test */
    public function teacher_can_mark_log_as_read()
    {
        $user = User::where('email', 'teacher1@example.com')->first();
        $this->actingAs($user);

        $log = DB::table('daily_logs')
            ->where('student_id', 3)
            ->whereNull('read_at')
            ->first();

        $response = $this->post("/teacher/daily-logs/{$log->id}/read");

        $response->assertRedirect("/teacher/daily-logs/{$log->id}");

        $this->assertDatabaseHas('daily_logs', [
            'id' => $log->id,
            'read_at' => now()->format('Y-m-d H:i:s'),
            'read_by' => $user->id,
        ]);
    }

    /** @test */
    public function teacher_can_view_students_index()
    {
        $user = User::where('email', 'teacher1@example.com')->first();
        $this->actingAs($user);

        $response = $this->get('/teacher/students');

        $response->assertStatus(200);
        $response->assertSee('担当クラス生徒一覧');
        $response->assertSee('山田太郎');
        $response->assertSee('小林未提出');
    }

    /** @test */
    public function teacher_can_view_student_logs()
    {
        $user = User::where('email', 'teacher1@example.com')->first();
        $this->actingAs($user);

        $response = $this->get('/teacher/students/3/logs');

        $response->assertStatus(200);
        $response->assertSee('過去記録 - 山田太郎');
        $response->assertSee('今日の記録');
        $response->assertSee('昨日の記録');
    }

    /** @test */
    public function teacher_can_filter_student_logs_by_date()
    {
        $user = User::where('email', 'teacher1@example.com')->first();
        $this->actingAs($user);

        $startDate = now()->subDays(2)->toDateString();
        $endDate = now()->toDateString();

        $response = $this->get("/teacher/students/3/logs?start_date={$startDate}&end_date={$endDate}");

        $response->assertStatus(200);
        $response->assertSee('今日の記録');
        $response->assertSee('昨日の記録');
    }

    /** @test */
    public function teacher_cannot_access_other_class_logs()
    {
        // 他のクラスの担任を作成
        User::create([
            'name' => '佐藤先生',
            'email' => 'teacher2@example.com',
            'password' => bcrypt('Passw0rd!'),
            'role' => 'teacher',
        ]);

        $user = User::where('email', 'teacher2@example.com')->first();
        $this->actingAs($user);

        $response = $this->get('/teacher/daily-logs');

        $response->assertStatus(200);
        $response->assertSee('対象生徒数');
        $response->assertDontSee('山田太郎'); // 担当外の生徒は表示されない
    }

    /** @test */
    public function read_log_shows_read_mark()
    {
        $user = User::where('email', 'teacher1@example.com')->first();
        $this->actingAs($user);

        $log = DB::table('daily_logs')
            ->where('student_id', 3)
            ->whereNotNull('read_at')
            ->first();

        $response = $this->get("/teacher/daily-logs/{$log->id}");

        $response->assertStatus(200);
        $response->assertSee('👍 ✓ 既読');
    }

    /** @test */
    public function unread_log_shows_unread_mark()
    {
        $user = User::where('email', 'teacher1@example.com')->first();
        $this->actingAs($user);

        $log = DB::table('daily_logs')
            ->where('student_id', 3)
            ->whereNull('read_at')
            ->first();

        $response = $this->get("/teacher/daily-logs/{$log->id}");

        $response->assertStatus(200);
        $response->assertSee('未読');
        $response->assertSee('この記録を既読にする');
    }
}


