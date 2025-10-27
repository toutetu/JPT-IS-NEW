<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index(Request $request)
    {
        abort_if(\Auth::user()->role !== 'admin', 403);

        // 検索パラメータを取得
        $search = $request->get('search');
        $role = $request->get('role');

        // 生徒の現在在籍クラス（is_active = true）
        $studentSub = \DB::table('enrollments')
            ->join('classrooms', 'classrooms.id', '=', 'enrollments.classroom_id')
            ->select('enrollments.student_id', 'classrooms.name as s_class_name')
            ->where('enrollments.is_active', true);

        // 担任の現在担当クラス（until_date が NULL）
        $teacherSub = \DB::table('homeroom_assignments')
            ->join('classrooms', 'classrooms.id', '=', 'homeroom_assignments.classroom_id')
            ->select('homeroom_assignments.teacher_id', 'classrooms.name as t_class_name')
            ->whereNull('homeroom_assignments.until_date');

        $query = \DB::table('users')
            ->leftJoinSub($studentSub, 'stu', function ($join) {
                $join->on('stu.student_id', '=', 'users.id');
            })
            ->leftJoinSub($teacherSub, 'tea', function ($join) {
                $join->on('tea.teacher_id', '=', 'users.id');
            })
            ->select([
                'users.id', 'users.name', 'users.email', 'users.role', 'users.created_at',
                \DB::raw('COALESCE(stu.s_class_name, tea.t_class_name) as assigned_class'),
            ]);

        // 検索条件を適用
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('users.name', 'like', "%{$search}%")
                  ->orWhere('users.email', 'like', "%{$search}%");
            });
        }

        if ($role) {
            $query->where('users.role', $role);
        }

        $users = $query->orderBy('users.id', 'desc')->paginate(10);

        return view('admin.users.index', compact('users', 'search', 'role'));
    }


    public function create()
    {
        abort_if(Auth::user()->role !== 'admin', 403);
        return view('admin.users.create');
    }

    public function store(Request $request)
    {
        abort_if(Auth::user()->role !== 'admin', 403);

        $data = $request->validate([
            'name'     => ['required','string','max:255'],
            'email'    => ['required','email','max:255','unique:users,email'],
            'role'     => ['required','in:student,teacher,admin'],
            'password' => ['required','string','min:8'],
        ]);

        DB::table('users')->insert([
            'name' => $data['name'],
            'email' => $data['email'],
            'role' => $data['role'],
            'password' => Hash::make($data['password']),
            'email_verified_at' => now(),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect()->route('admin.users.index')->with('status','ユーザーを作成しました。');
    }

    /**
     * ログインなしで新規ユーザー作成フォームを表示
     */
    public function createWithoutAuth()
    {
        return view('admin.users.create_without_auth');
    }

    /**
     * ログインなしで新規ユーザーを作成
     */
    public function storeWithoutAuth(Request $request)
    {
        try {
            $data = $request->validate([
                'name'     => ['required','string','max:255'],
                'email'    => ['required','email','max:255','unique:users,email'],
                'role'     => ['required','in:student,teacher,admin'],
                'password' => ['required','string','min:8'],
            ]);

            DB::table('users')->insert([
                'name' => $data['name'],
                'email' => $data['email'],
                'role' => $data['role'],
                'password' => Hash::make($data['password']),
                'email_verified_at' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // デバッグ用ログ
            \Log::info('ユーザー作成成功: ' . $data['email']);

            return redirect()->route('admin.users.create_without_auth')
                ->with('status', 'ユーザーを作成しました。')
                ->with('success', true);
        } catch (\Illuminate\Validation\ValidationException $e) {
            // バリデーションエラーの場合、エラーメッセージと共にリダイレクト
            return redirect()->route('admin.users.create_without_auth')
                ->withErrors($e->validator)
                ->withInput();
        } catch (\Exception $e) {
            // その他のエラーの場合
            return redirect()->route('admin.users.create_without_auth')
                ->with('error', 'ユーザー作成中にエラーが発生しました: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function destroy($id)
    {
        abort_if(Auth::user()->role !== 'admin', 403);

        // 自分自身は削除できないようにする
        if (Auth::id() == $id) {
            return redirect()->route('admin.users.index')
                ->with('error', '自分自身を削除することはできません。');
        }

        // ユーザーが存在するか確認
        $user = DB::table('users')->where('id', $id)->first();
        
        if (!$user) {
            return redirect()->route('admin.users.index')
                ->with('error', 'ユーザーが見つかりません。');
        }

        // 関連データも削除（enrollments, homeroom_assignments, daily_logs）
        DB::table('enrollments')->where('student_id', $id)->delete();
        DB::table('homeroom_assignments')->where('teacher_id', $id)->delete();
        
        // daily_logsの削除（作成者として、または既読者として）
        DB::table('daily_logs')->where('student_id', $id)->delete();

        // ユーザーを削除
        DB::table('users')->where('id', $id)->delete();

        return redirect()->route('admin.users.index')
            ->with('status', 'ユーザーを削除しました。');
    }
}