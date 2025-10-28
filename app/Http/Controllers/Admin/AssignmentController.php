<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreEnrollmentRequest;
use App\Http\Requests\StoreHomeroomAssignmentRequest;
use App\Http\Requests\ImportCsvRequest;
use App\Services\AssignmentService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AssignmentController extends Controller
{
    public function __construct(
        private AssignmentService $assignmentService
    ) {}

    public function enrollmentForm(Request $request)
    {
        abort_if(Auth::user()->role !== 'admin', 403);

        $students = \App\Models\User::students()->orderBy('name')->get(['id', 'name', 'email']);
        $grades = \App\Models\Grade::orderBy('id')->get();
        $classes = \App\Models\Classroom::with('grade')
            ->orderBy('grade_id')
            ->orderBy('name')
            ->get();

        $selectedStudentId = $request->query('student_id');
        
        // 選択された生徒の現在の在籍クラスを取得
        $currentEnrollment = null;
        if ($selectedStudentId) {
            $currentEnrollment = $this->assignmentService->getCurrentEnrollmentForStudent($selectedStudentId);
        }
        
        return view('admin.assign.enrollment', compact('students', 'grades', 'classes', 'selectedStudentId', 'currentEnrollment'));
    }

    public function enrollmentStore(StoreEnrollmentRequest $request)
    {
        abort_if(Auth::user()->role !== 'admin', 403);

        $this->assignmentService->changeStudentEnrollment(
            $request->student_id,
            $request->classroom_id
        );

        return back()->with('status', '生徒の在籍を割り当てました。');
    }

    public function homeroomForm(Request $request)
    {
        abort_if(Auth::user()->role !== 'admin', 403);

        $teachers = \App\Models\User::teachers()->orderBy('name')->get(['id', 'name', 'email']);
        $grades = \App\Models\Grade::orderBy('id')->get();
        $classes = \App\Models\Classroom::with('grade')
            ->orderBy('grade_id')
            ->orderBy('name')
            ->get();

        $selectedTeacherId = $request->query('teacher_id');
        
        // 選択された担任の現在の担当クラスを取得
        $currentHomeroom = null;
        if ($selectedTeacherId) {
            $currentHomeroom = $this->assignmentService->getCurrentAssignmentForTeacher($selectedTeacherId);
        }
        
        return view('admin.assign.homeroom', compact('teachers', 'grades', 'classes', 'selectedTeacherId', 'currentHomeroom'));
    }

    public function homeroomStore(StoreHomeroomAssignmentRequest $request)
    {
        abort_if(Auth::user()->role !== 'admin', 403);

        $this->assignmentService->changeTeacherAssignment(
            $request->teacher_id,
            $request->classroom_id
        );

        return back()->with('status', '担任のクラス割当を実施しました。');
    }

    public function enrollmentImportForm()
    {
        abort_if(Auth::user()->role !== 'admin', 403);
        
        $grades = \App\Models\Grade::orderBy('id')->get();
        $classes = \App\Models\Classroom::with('grade')
            ->orderBy('grade_id')
            ->orderBy('name')
            ->get();
        
        return view('admin.assign.enrollment_import', compact('grades', 'classes'));
    }

    public function enrollmentImportCSV(ImportCsvRequest $request)
    {
        abort_if(Auth::user()->role !== 'admin', 403);

        $file = $request->file('csv_file');
        $filePath = $file->getRealPath();

        try {
            $result = $this->assignmentService->importEnrollmentsFromCsv($filePath);

            $message = "{$result['success_count']}件のクラス割り当てを実施しました。";
            if ($result['error_count'] > 0) {
                $message .= " {$result['error_count']}件のエラーがありました。";
            }

            return redirect()->route('admin.assign.enrollment.import')
                ->with('status', $message)
                ->with('errors', $result['errors']);
        } catch (\Exception $e) {
            return redirect()->route('admin.assign.enrollment.import')
                ->with('error', 'CSVのインポート中にエラーが発生しました: ' . $e->getMessage());
        }
    }
}