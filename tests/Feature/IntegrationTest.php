<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class IntegrationTest extends TestCase
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
    }

    /** @test */
    public function complete_workflow_student_submits_teacher_reads()
    {
        // 1. 生徒が連絡帳を提出
        $student = User::where('email', 'student001@example.com')->first();
        $this->actingAs($student);

        $response = $this->post('/student/daily-logs', [
            'target_date' => now()->toDateString(),
            'health_score' => 4,
            'mental_score' => 3,
            'body' => '統合テスト用の記録',
        ]);

        $response->assertRedirect('/student/daily-logs');
        $response->assertSessionHas('status', '提出しました。');

        // 2. 担任が提出を確認
        $teacher = User::where('email', 'teacher1@example.com')->first();
        $this->actingAs($teacher);

        $response = $this->get('/teacher/daily-logs');
        $response->assertStatus(200);
        $response->assertSee('統合テスト用の記録');

        // 3. 担任が既読にする
        $log = DB::table('daily_logs')
            ->where('student_id', $student->id)
            ->where('body', '統合テスト用の記録')
            ->first();

        $response = $this->post("/teacher/daily-logs/{$log->id}/read");
        $response->assertRedirect("/teacher/daily-logs/{$log->id}");

        // 4. 既読状態の確認
        $this->assertDatabaseHas('daily_logs', [
            'id' => $log->id,
            'read_at' => now()->format('Y-m-d H:i:s'),
            'read_by' => $teacher->id,
        ]);

        // 5. 生徒が修正できないことを確認
        $this->actingAs($student);
        $response = $this->get("/student/daily-logs/{$log->id}/edit");
        $response->assertRedirect("/student/daily-logs/{$log->id}");
        $response->assertSessionHasErrors(['edit']);
    }

    /** @test */
    public function complete_workflow_admin_manages_users()
    {
        // 1. 管理者が新規ユーザーを作成
        $admin = User::where('email', 'admin@example.com')->first();
        $this->actingAs($admin);

        $response = $this->post('/admin/users', [
            'name' => '新規生徒',
            'email' => 'newstudent@example.com',
            'role' => 'student',
            'password' => 'NewPass123!',
        ]);

        $response->assertRedirect('/admin/users');
        $response->assertSessionHas('status', 'ユーザーを作成しました。');

        // 2. 新規ユーザーがログインできることを確認
        $newUser = User::where('email', 'newstudent@example.com')->first();
        $this->assertNotNull($newUser);

        // 3. 管理者が在籍割当を実行
        $response = $this->post('/admin/assign/enrollment', [
            'student_id' => $newUser->id,
            'classroom_id' => 1,
        ]);

        $response->assertRedirect('/admin/users');

        // 4. 在籍割当の確認
        $this->assertDatabaseHas('enrollments', [
            'student_id' => $newUser->id,
            'classroom_id' => 1,
            'is_active' => true,
        ]);
    }

    /** @test */
    public function complete_workflow_teacher_views_student_history()
    {
        // 1. 生徒が複数の記録を提出
        $student = User::where('email', 'student001@example.com')->first();
        $this->actingAs($student);

        // 複数の記録を提出
        for ($i = 1; $i <= 3; $i++) {
            $this->post('/student/daily-logs', [
                'target_date' => now()->subDays($i)->toDateString(),
                'health_score' => 3 + $i,
                'mental_score' => 2 + $i,
                'body' => "テスト記録{$i}",
            ]);
        }

        // 2. 担任が生徒の過去記録を確認
        $teacher = User::where('email', 'teacher1@example.com')->first();
        $this->actingAs($teacher);

        $response = $this->get('/teacher/students');
        $response->assertStatus(200);
        $response->assertSee('山田太郎');

        // 3. 特定生徒の過去記録を確認
        $response = $this->get('/teacher/students/3/logs');
        $response->assertStatus(200);
        $response->assertSee('テスト記録1');
        $response->assertSee('テスト記録2');
        $response->assertSee('テスト記録3');
    }

    /** @test */
    public function complete_workflow_calendar_functionality()
    {
        // 1. 生徒が複数の記録を提出
        $student = User::where('email', 'student001@example.com')->first();
        $this->actingAs($student);

        // 今日と昨日の記録を提出
        $this->post('/student/daily-logs', [
            'target_date' => now()->toDateString(),
            'health_score' => 4,
            'mental_score' => 3,
            'body' => '今日の記録',
        ]);

        $this->post('/student/daily-logs', [
            'target_date' => now()->subDays(1)->toDateString(),
            'health_score' => 5,
            'mental_score' => 4,
            'body' => '昨日の記録',
        ]);

        // 2. カレンダーで提出状況を確認
        $response = $this->get('/student/calendar');
        $response->assertStatus(200);
        $response->assertSee('提出カレンダー');
        $response->assertSee('済'); // 提出済みマーク

        // 3. 未提出の生徒のカレンダーを確認
        $noSubmissionStudent = User::where('email', 'student004@example.com')->first();
        $this->actingAs($noSubmissionStudent);

        $response = $this->get('/student/calendar');
        $response->assertStatus(200);
        $response->assertDontSee('済'); // 未提出なので「済」マークがない
    }

    /** @test */
    public function complete_workflow_pagination_and_ui_improvements()
    {
        // 1. 生徒のマイ連絡帳でページネーションを確認
        $student = User::where('email', 'student001@example.com')->first();
        $this->actingAs($student);

        $response = $this->get('/student/daily-logs');
        $response->assertStatus(200);
        $response->assertDontSee('＞'); // ページネーションの「＞」文字が表示されない
        $response->assertDontSee('＜'); // ページネーションの「＜」文字が表示されない

        // 2. 管理者のユーザー一覧でページネーションを確認
        $admin = User::where('email', 'admin@example.com')->first();
        $this->actingAs($admin);

        $response = $this->get('/admin/users');
        $response->assertStatus(200);
        $response->assertDontSee('＞'); // ページネーションの「＞」文字が表示されない
        $response->assertDontSee('＜'); // ページネーションの「＜」文字が表示されない
    }

    /** @test */
    public function complete_workflow_read_status_visual_improvements()
    {
        // 1. 生徒が記録を提出
        $student = User::where('email', 'student001@example.com')->first();
        $this->actingAs($student);

        $this->post('/student/daily-logs', [
            'target_date' => now()->toDateString(),
            'health_score' => 4,
            'mental_score' => 3,
            'body' => '視覚改善テスト用記録',
        ]);

        // 2. 担任が既読にする
        $teacher = User::where('email', 'teacher1@example.com')->first();
        $this->actingAs($teacher);

        $log = DB::table('daily_logs')
            ->where('student_id', $student->id)
            ->where('body', '視覚改善テスト用記録')
            ->first();

        $this->post("/teacher/daily-logs/{$log->id}/read");

        // 3. 既読マークの表示を確認
        $response = $this->get("/teacher/daily-logs/{$log->id}");
        $response->assertStatus(200);
        $response->assertSee('👍 ✓ 既読'); // いいねマークとチェックマーク
    }

    /** @test */
    public function complete_workflow_form_improvements()
    {
        // 1. 生徒が新規提出フォームを開く
        $student = User::where('email', 'student001@example.com')->first();
        $this->actingAs($student);

        $response = $this->get('/student/daily-logs/create');
        $response->assertStatus(200);
        $response->assertSee('体調');
        $response->assertSee('メンタル');
        $response->assertSee('悪い'); // ラジオボタンのラベル
        $response->assertSee('良い'); // ラジオボタンのラベル

        // 2. 初期値が3に設定されていることを確認
        $response->assertSee('value="3"'); // 体調・メンタルの初期値
    }

    /** @test */
    public function complete_workflow_error_handling()
    {
        // 1. 重複提出のエラーハンドリング
        $student = User::where('email', 'student001@example.com')->first();
        $this->actingAs($student);

        // 最初の提出
        $this->post('/student/daily-logs', [
            'target_date' => now()->toDateString(),
            'health_score' => 4,
            'mental_score' => 3,
            'body' => '最初の提出',
        ]);

        // 同じ日付での重複提出
        $response = $this->post('/student/daily-logs', [
            'target_date' => now()->toDateString(),
            'health_score' => 5,
            'mental_score' => 4,
            'body' => '重複提出',
        ]);

        $response->assertSessionHasErrors(['target_date']);

        // 2. 権限エラーのハンドリング
        $student = User::where('email', 'student001@example.com')->first();
        $this->actingAs($student);

        $response = $this->get('/admin/users');
        $response->assertStatus(403);
    }
}

