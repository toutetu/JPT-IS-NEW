<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\ImportCsvRequest;
use App\Services\UserService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function __construct(
        private UserService $userService
    ) {}

    public function index(Request $request)
    {
        abort_if(Auth::user()->role !== 'admin', 403);

        $search = $request->get('search');
        $role = $request->get('role');

        $users = $this->userService->getUsersWithAssignments($search, $role);

        return view('admin.users.index', compact('users', 'search', 'role'));
    }


    public function create()
    {
        abort_if(Auth::user()->role !== 'admin', 403);
        return view('admin.users.create');
    }

    public function store(StoreUserRequest $request)
    {
        abort_if(Auth::user()->role !== 'admin', 403);

        $this->userService->createUser($request->validated());

        return redirect()->route('admin.users.index')->with('status', 'ユーザーを作成しました。');
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
    public function storeWithoutAuth(StoreUserRequest $request)
    {
        try {
            $this->userService->createUser($request->validated());

            \Log::info('ユーザー作成成功: ' . $request->email);

            return redirect()->route('admin.users.create_without_auth')
                ->with('status', 'ユーザーを作成しました。')
                ->with('success', true);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()->route('admin.users.create_without_auth')
                ->withErrors($e->validator)
                ->withInput();
        } catch (\Exception $e) {
            return redirect()->route('admin.users.create_without_auth')
                ->with('error', 'ユーザー作成中にエラーが発生しました: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function importForm()
    {
        abort_if(Auth::user()->role !== 'admin', 403);
        return view('admin.users.import');
    }

    public function importCSV(ImportCsvRequest $request)
    {
        abort_if(Auth::user()->role !== 'admin', 403);

        $file = $request->file('csv_file');
        $filePath = $file->getRealPath();

        try {
            $result = $this->userService->importUsersFromCsv($filePath);

            $message = "{$result['success_count']}件のユーザーを作成しました。";
            if ($result['error_count'] > 0) {
                $message .= " {$result['error_count']}件のエラーがありました。";
            }

            return redirect()->route('admin.users.import')
                ->with('status', $message)
                ->with('errors', $result['errors']);
        } catch (\Exception $e) {
            return redirect()->route('admin.users.import')
                ->with('error', 'CSVのインポート中にエラーが発生しました: ' . $e->getMessage());
        }
    }

    public function delete($id)
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

        // 削除確認画面で表示するため、ユーザー情報と関連データを取得
        $studentSub = \DB::table('enrollments')
            ->join('classrooms', 'classrooms.id', '=', 'enrollments.classroom_id')
            ->select('enrollments.student_id', 'classrooms.name as s_class_name')
            ->where('enrollments.student_id', $id)
            ->where('enrollments.is_active', true);

        $teacherSub = \DB::table('homeroom_assignments')
            ->join('classrooms', 'classrooms.id', '=', 'homeroom_assignments.classroom_id')
            ->select('homeroom_assignments.teacher_id', 'classrooms.name as t_class_name')
            ->where('homeroom_assignments.teacher_id', $id)
            ->whereNull('homeroom_assignments.until_date');

        $user = DB::table('users')
            ->leftJoinSub($studentSub, 'stu', function ($join) {
                $join->on('stu.student_id', '=', 'users.id');
            })
            ->leftJoinSub($teacherSub, 'tea', function ($join) {
                $join->on('tea.teacher_id', '=', 'users.id');
            })
            ->select([
                'users.id', 'users.name', 'users.email', 'users.role', 'users.created_at',
                \DB::raw('COALESCE(stu.s_class_name, tea.t_class_name) as assigned_class'),
            ])
            ->where('users.id', $id)
            ->first();

        return view('admin.users.delete', compact('user'));
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
        $user = \App\Models\User::find($id);
        
        if (!$user) {
            return redirect()->route('admin.users.index')
                ->with('error', 'ユーザーが見つかりません。');
        }

        $this->userService->deleteUser($id);

        return redirect()->route('admin.users.index')
            ->with('status', 'ユーザーを削除しました。');
    }
}