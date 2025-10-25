<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class StudentTest extends TestCase
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
            ['id' => 2, 'name' => '2年', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 3, 'name' => '3年', 'created_at' => now(), 'updated_at' => now()],
        ]);

        // クラス
        DB::table('classrooms')->insert([
            ['id' => 1, 'grade_id' => 1, 'name' => '1年A組', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 2, 'grade_id' => 1, 'name' => '1年B組', 'created_at' => now(), 'updated_at' => now()],
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

        // 山田太郎の過去データ
        DB::table('daily_logs')->insert([
            [
                'student_id' => 3,
                'target_date' => now()->subDays(1)->toDateString(),
                'health_score' => 4,
                'mental_score' => 3,
                'body' => 'テスト記録1',
                'submitted_at' => now()->subDays(1),
                'read_at' => now()->subDays(1)->addHours(2),
                'read_by' => 2,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'student_id' => 3,
                'target_date' => now()->subDays(2)->toDateString(),
                'health_score' => 5,
                'mental_score' => 4,
                'body' => 'テスト記録2',
                'submitted_at' => now()->subDays(2),
                'read_at' => null,
                'read_by' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }

    /** @test */
    public function student_login_redirects_to_daily_logs()
    {
        $response = $this->post('/login', [
            'email' => 'student001@example.com',
            'password' => 'Passw0rd!',
        ]);

        $response->assertRedirect('/student/daily-logs');
    }

    /** @test */
    public function student_can_view_daily_logs_index()
    {
        $user = User::where('email', 'student001@example.com')->first();
        $this->actingAs($user);

        $response = $this->get('/student/daily-logs');

        $response->assertStatus(200);
        $response->assertSee('マイ連絡帳');
        $response->assertSee('新規提出');
        $response->assertSee('テスト記録1');
        $response->assertSee('テスト記録2');
    }

    /** @test */
    public function student_can_view_calendar()
    {
        $user = User::where('email', 'student001@example.com')->first();
        $this->actingAs($user);

        $response = $this->get('/student/calendar');

        $response->assertStatus(200);
        $response->assertSee('提出カレンダー');
        $response->assertSee('済');
    }

    /** @test */
    public function student_can_create_daily_log()
    {
        $user = User::where('email', 'student001@example.com')->first();
        $this->actingAs($user);

        $response = $this->get('/student/daily-logs/create');

        $response->assertStatus(200);
        $response->assertSee('連絡帳の提出');
        $response->assertSee('体調');
        $response->assertSee('メンタル');
    }

    /** @test */
    public function student_can_submit_daily_log()
    {
        $user = User::where('email', 'student001@example.com')->first();
        $this->actingAs($user);

        $response = $this->post('/student/daily-logs', [
            'target_date' => now()->toDateString(),
            'health_score' => 4,
            'mental_score' => 3,
            'body' => 'テスト提出内容',
        ]);

        $response->assertRedirect('/student/daily-logs');
        $response->assertSessionHas('status', '提出しました。');

        $this->assertDatabaseHas('daily_logs', [
            'student_id' => $user->id,
            'target_date' => now()->toDateString(),
            'health_score' => 4,
            'mental_score' => 3,
            'body' => 'テスト提出内容',
        ]);
    }

    /** @test */
    public function student_can_view_daily_log_detail()
    {
        $user = User::where('email', 'student001@example.com')->first();
        $this->actingAs($user);

        $log = DB::table('daily_logs')->where('student_id', $user->id)->first();

        $response = $this->get("/student/daily-logs/{$log->id}");

        $response->assertStatus(200);
        $response->assertSee('連絡帳 詳細');
        $response->assertSee('テスト記録1');
    }

    /** @test */
    public function student_can_edit_daily_log()
    {
        $user = User::where('email', 'student001@example.com')->first();
        $this->actingAs($user);

        $log = DB::table('daily_logs')
            ->where('student_id', $user->id)
            ->whereNull('read_at')
            ->first();

        $response = $this->get("/student/daily-logs/{$log->id}/edit");

        $response->assertStatus(200);
        $response->assertSee('連絡帳の修正');
    }

    /** @test */
    public function student_can_update_daily_log()
    {
        $user = User::where('email', 'student001@example.com')->first();
        $this->actingAs($user);

        $log = DB::table('daily_logs')
            ->where('student_id', $user->id)
            ->whereNull('read_at')
            ->first();

        $response = $this->post("/student/daily-logs/{$log->id}", [
            'target_date' => $log->target_date,
            'health_score' => 5,
            'mental_score' => 4,
            'body' => '修正されたテスト記録',
        ]);

        $response->assertRedirect("/student/daily-logs/{$log->id}");
        $response->assertSessionHas('status', '修正して保存しました。');

        $this->assertDatabaseHas('daily_logs', [
            'id' => $log->id,
            'health_score' => 5,
            'mental_score' => 4,
            'body' => '修正されたテスト記録',
        ]);
    }

    /** @test */
    public function student_cannot_edit_read_log()
    {
        $user = User::where('email', 'student001@example.com')->first();
        $this->actingAs($user);

        $log = DB::table('daily_logs')
            ->where('student_id', $user->id)
            ->whereNotNull('read_at')
            ->first();

        $response = $this->get("/student/daily-logs/{$log->id}/edit");

        $response->assertRedirect("/student/daily-logs/{$log->id}");
        $response->assertSessionHasErrors(['edit']);
    }

    /** @test */
    public function no_submission_student_has_empty_logs()
    {
        $user = User::where('email', 'student004@example.com')->first();
        $this->actingAs($user);

        $response = $this->get('/student/daily-logs');

        $response->assertStatus(200);
        $response->assertSee('まだ提出がありません。');
    }

    /** @test */
    public function no_submission_student_has_empty_calendar()
    {
        $user = User::where('email', 'student004@example.com')->first();
        $this->actingAs($user);

        $response = $this->get('/student/calendar');

        $response->assertStatus(200);
        $response->assertDontSee('済');
    }
}


