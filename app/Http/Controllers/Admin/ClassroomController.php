<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\ClassroomService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ClassroomController extends Controller
{
    public function __construct(
        private ClassroomService $classroomService
    ) {}

    /**
     * クラス一覧
     */
    public function index()
    {
        abort_if(Auth::user()->role !== 'admin', 403);

        $classrooms = $this->classroomService->getClassroomsWithGrade();

        return view('admin.classrooms.index', compact('classrooms'));
    }

    /**
     * クラス作成フォーム
     */
    public function create()
    {
        abort_if(Auth::user()->role !== 'admin', 403);

        $grades = $this->classroomService->getAllGrades();

        return view('admin.classrooms.create', compact('grades'));
    }

    /**
     * クラス作成処理
     */
    public function store(Request $request)
    {
        abort_if(Auth::user()->role !== 'admin', 403);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'grade_id' => 'required|exists:grades,id',
        ], [
            'name.required' => 'クラス名は必須です。',
            'name.max' => 'クラス名は255文字以内で入力してください。',
            'grade_id.required' => '学年は必須です。',
            'grade_id.exists' => '選択された学年が存在しません。',
        ]);

        try {
            $this->classroomService->createClassroom($validated);

            return redirect()->route('admin.classrooms.index')
                ->with('status', 'クラスを作成しました。');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', $e->getMessage())
                ->withInput();
        }
    }

    /**
     * クラス削除確認画面
     */
    public function delete($id)
    {
        abort_if(Auth::user()->role !== 'admin', 403);

        $classroom = $this->classroomService->getClassroomWithDetails($id);

        if (!$classroom) {
            return redirect()->route('admin.classrooms.index')
                ->with('error', 'クラスが見つかりません。');
        }

        return view('admin.classrooms.delete', compact('classroom'));
    }

    /**
     * クラス削除処理
     */
    public function destroy($id)
    {
        abort_if(Auth::user()->role !== 'admin', 403);

        try {
            $this->classroomService->deleteClassroom($id);

            return redirect()->route('admin.classrooms.index')
                ->with('status', 'クラスを削除しました。');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', $e->getMessage());
        }
    }
}

