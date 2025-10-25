<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class AdminTest extends TestCase
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
        ]);

        // クラス
        DB::table('classrooms')->insert([
            ['id' => 1, 'grade_id' => 1, 'name' => '1年A組', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 2, 'grade_id' => 1, 'name' => '1年B組', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 3, 'grade_id' => 2, 'name' => '2年A組', 'created_at' => now(), 'updated_at' => now()],
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
    public function admin_login_redirects_to_users_index()
    {
        $response = $this->post('/login', [
            'email' => 'admin@example.com',
            'password' => 'Passw0rd!',
        ]);

        $response->assertRedirect('/admin/users');
    }

    /** @test */
    public function admin_can_view_users_index()
    {
        $user = User::where('email', 'admin@example.com')->first();
        $this->actingAs($user);

        $response = $this->get('/admin/users');

        $response->assertStatus(200);
        $response->assertSee('ユーザー一覧');
        $response->assertSee('Admin');
        $response->assertSee('田中先生');
        $response->assertSee('山田太郎');
        $response->assertSee('小林未提出');
    }

    /** @test */
    public function admin_can_view_user_create_form()
    {
        $user = User::where('email', 'admin@example.com')->first();
        $this->actingAs($user);

        $response = $this->get('/admin/users/create');

        $response->assertStatus(200);
        $response->assertSee('新規ユーザー作成');
        $response->assertSee('名前');
        $response->assertSee('メール');
        $response->assertSee('ロール');
        $response->assertSee('パスワード');
    }

    /** @test */
    public function admin_can_create_new_user()
    {
        $user = User::where('email', 'admin@example.com')->first();
        $this->actingAs($user);

        $response = $this->post('/admin/users', [
            'name' => 'テスト生徒',
            'email' => 'test@example.com',
            'role' => 'student',
            'password' => 'TestPass123!',
        ]);

        $response->assertRedirect('/admin/users');
        $response->assertSessionHas('status', 'ユーザーを作成しました。');

        $this->assertDatabaseHas('users', [
            'name' => 'テスト生徒',
            'email' => 'test@example.com',
            'role' => 'student',
        ]);
    }

    /** @test */
    public function admin_cannot_create_user_with_duplicate_email()
    {
        $user = User::where('email', 'admin@example.com')->first();
        $this->actingAs($user);

        $response = $this->post('/admin/users', [
            'name' => '重複テスト',
            'email' => 'student001@example.com', // 既存のメール
            'role' => 'student',
            'password' => 'TestPass123!',
        ]);

        $response->assertSessionHasErrors(['email']);
    }

    /** @test */
    public function guest_can_create_user_without_login()
    {
        // ログインなしでアクセスできること
        $response = $this->get('/admin/users/create-without-auth');
        $response->assertStatus(200);
        $response->assertSee('新規ユーザー作成（ログイン不要）');

        // ログインなしでユーザーを作成できること
        $response = $this->post('/admin/users/create-without-auth', [
            'name' => 'ゲスト作成ユーザー',
            'email' => 'guest@example.com',
            'role' => 'student',
            'password' => 'GuestPass123!',
        ]);

        $response->assertRedirect('/admin/users/create-without-auth');
        $response->assertSessionHas('status', 'ユーザーを作成しました。');

        // データベースにユーザーが作成されたことを確認
        $this->assertDatabaseHas('users', [
            'name' => 'ゲスト作成ユーザー',
            'email' => 'guest@example.com',
            'role' => 'student',
        ]);
    }

    /** @test */
    public function guest_can_create_admin_user_without_login()
    {
        // ログインなしで管理者ユーザーも作成できることを確認
        $response = $this->post('/admin/users/create-without-auth', [
            'name' => 'ゲスト作成管理者',
            'email' => 'guest-admin@example.com',
            'role' => 'admin',
            'password' => 'GuestAdmin123!',
        ]);

        $response->assertRedirect('/admin/users/create-without-auth');

        $this->assertDatabaseHas('users', [
            'name' => 'ゲスト作成管理者',
            'email' => 'guest-admin@example.com',
            'role' => 'admin',
        ]);
    }

    /** @test */
    public function admin_can_view_enrollment_assignment_form()
    {
        $user = User::where('email', 'admin@example.com')->first();
        $this->actingAs($user);

        $response = $this->get('/admin/assign/enrollment?student_id=3');

        $response->assertStatus(200);
        $response->assertSee('在籍割当');
        $response->assertSee('山田太郎');
    }

    /** @test */
    public function admin_can_assign_student_to_classroom()
    {
        $user = User::where('email', 'admin@example.com')->first();
        $this->actingAs($user);

        $response = $this->post('/admin/assign/enrollment', [
            'student_id' => 3,
            'classroom_id' => 2, // 1年B組に変更
        ]);

        $response->assertRedirect('/admin/users');

        $this->assertDatabaseHas('enrollments', [
            'student_id' => 3,
            'classroom_id' => 2,
            'is_active' => true,
        ]);
    }

    /** @test */
    public function admin_can_view_homeroom_assignment_form()
    {
        $user = User::where('email', 'admin@example.com')->first();
        $this->actingAs($user);

        $response = $this->get('/admin/assign/homeroom?teacher_id=2');

        $response->assertStatus(200);
        $response->assertSee('担任割当');
        $response->assertSee('田中先生');
    }

    /** @test */
    public function admin_can_assign_teacher_to_classroom()
    {
        $user = User::where('email', 'admin@example.com')->first();
        $this->actingAs($user);

        $response = $this->post('/admin/assign/homeroom', [
            'teacher_id' => 2,
            'classroom_id' => 2, // 1年B組に変更
        ]);

        $response->assertRedirect('/admin/users');

        $this->assertDatabaseHas('homeroom_assignments', [
            'teacher_id' => 2,
            'classroom_id' => 2,
        ]);
    }

    /** @test */
    public function non_admin_cannot_access_admin_pages()
    {
        $user = User::where('email', 'student001@example.com')->first();
        $this->actingAs($user);

        $response = $this->get('/admin/users');

        $response->assertStatus(403);
    }

    /** @test */
    public function admin_can_see_assigned_classes_in_user_list()
    {
        $user = User::where('email', 'admin@example.com')->first();
        $this->actingAs($user);

        $response = $this->get('/admin/users');

        $response->assertStatus(200);
        $response->assertSee('1年A組'); // 山田太郎の在籍クラス
    }

    /** @test */
    public function admin_can_see_teacher_assignments_in_user_list()
    {
        $user = User::where('email', 'admin@example.com')->first();
        $this->actingAs($user);

        $response = $this->get('/admin/users');

        $response->assertStatus(200);
        $response->assertSee('1年A組'); // 田中先生の担当クラス
    }

    /** @test */
    public function admin_can_see_assignment_buttons_for_students()
    {
        $user = User::where('email', 'admin@example.com')->first();
        $this->actingAs($user);

        $response = $this->get('/admin/users');

        $response->assertStatus(200);
        $response->assertSee('在籍を変更');
    }

    /** @test */
    public function admin_can_see_assignment_buttons_for_teachers()
    {
        $user = User::where('email', 'admin@example.com')->first();
        $this->actingAs($user);

        $response = $this->get('/admin/users');

        $response->assertStatus(200);
        $response->assertSee('担任割当を変更');
    }
}


