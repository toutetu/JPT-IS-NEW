<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth')->except('forceLogout');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $user = auth()->user();
        
        // ロールに応じて適切なページにリダイレクト
        switch ($user->role) {
            case 'student':
                return redirect()->route('student.daily_logs.index');
            case 'teacher':
                return redirect()->route('teacher.daily_logs.index');
            case 'admin':
                return redirect()->route('admin.users.index');
            default:
                return view('home');
        }
    }

    /**
     * 強制ログアウト（GETリクエスト対応）
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function forceLogout(Request $request)
    {
        \Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        
        return redirect('/login')->with('status', 'ログアウトしました');
    }
}