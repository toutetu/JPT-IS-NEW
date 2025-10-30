<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\ImportCsvRequest;
use App\Models\User;
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

        // ユーザー情報とクラス割り当て情報を取得
        $user = $this->userService->getUserWithAssignment($id);
        
        if (!$user) {
            return redirect()->route('admin.users.index')
                ->with('error', 'ユーザーが見つかりません。');
        }

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
        $user = User::find($id);
        
        if (!$user) {
            return redirect()->route('admin.users.index')
                ->with('error', 'ユーザーが見つかりません。');
        }

        $this->userService->deleteUser($id);

        return redirect()->route('admin.users.index')
            ->with('status', 'ユーザーを削除しました。');
    }
}