<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;           
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AssignmentController extends Controller
{
       // 1) $request を引数で受け取る
    public function enrollmentForm(Request $request)
    {
        abort_if(Auth::user()->role !== 'admin', 403);

        $students = DB::table('users')->where('role', 'student')->orderBy('name')->get(['id','name','email']);
        $grades   = DB::table('grades')->orderBy('id')->get();
        $classes  = DB::table('classrooms')
            ->join('grades','grades.id','=','classrooms.grade_id')
            ->select('classrooms.id','classrooms.name as cname','grades.name as gname')
            ->orderBy('grades.id')->orderBy('classrooms.name')
            ->get();

        $selectedStudentId = $request->query('student_id');
        
        // 選択された生徒の現在の在籍クラスを取得
        $currentEnrollment = null;
        if ($selectedStudentId) {
            $currentEnrollment = DB::table('enrollments')
                ->join('classrooms', 'enrollments.classroom_id', '=', 'classrooms.id')
                ->join('grades', 'classrooms.grade_id', '=', 'grades.id')
                ->where('enrollments.student_id', $selectedStudentId)
                ->where('enrollments.is_active', true)
                ->select('enrollments.classroom_id', 'classrooms.name as classroom_name', 'grades.name as grade_name')
                ->first();
        }
        
        return view('admin.assign.enrollment', compact('students','grades','classes','selectedStudentId','currentEnrollment'));
    }

    public function enrollmentStore(Request $request)
    {
        abort_if(Auth::user()->role !== 'admin', 403);

        $data = $request->validate([
            'student_id'   => ['required','integer','exists:users,id'],
            'classroom_id' => ['required','integer','exists:classrooms,id'],
        ]);

        DB::table('enrollments')
            ->where('student_id', $data['student_id'])
            ->where('is_active', true)
            ->update(['is_active' => false, 'until_date' => now()->toDateString(), 'updated_at' => now()]);

        DB::table('enrollments')->insert([
            'student_id'   => $data['student_id'],
            'classroom_id' => $data['classroom_id'],
            'is_active'    => true,
            'since_date'   => now()->toDateString(),
            'until_date'   => null,
            'created_at'   => now(), 'updated_at' => now(),
        ]);

        return back()->with('status', '生徒の在籍を割り当てました。');
    }

     // 2) こちらも同様に Request を受け取る
    public function homeroomForm(Request $request)
    {
        abort_if(Auth::user()->role !== 'admin', 403);

        $teachers = DB::table('users')->where('role', 'teacher')->orderBy('name')->get(['id','name','email']);
        $grades   = DB::table('grades')->orderBy('id')->get();
        $classes  = DB::table('classrooms')
            ->join('grades','grades.id','=','classrooms.grade_id')
            ->select('classrooms.id','classrooms.name as cname','grades.name as gname')
            ->orderBy('grades.id')->orderBy('classrooms.name')
            ->get();

        $selectedTeacherId = $request->query('teacher_id');
        
        // 選択された担任の現在の担当クラスを取得
        $currentHomeroom = null;
        if ($selectedTeacherId) {
            $currentHomeroom = DB::table('homeroom_assignments')
                ->join('classrooms', 'homeroom_assignments.classroom_id', '=', 'classrooms.id')
                ->join('grades', 'classrooms.grade_id', '=', 'grades.id')
                ->where('homeroom_assignments.teacher_id', $selectedTeacherId)
                ->whereNull('homeroom_assignments.until_date')
                ->select('homeroom_assignments.classroom_id', 'classrooms.name as classroom_name', 'grades.name as grade_name')
                ->first();
        }
        
        return view('admin.assign.homeroom', compact('teachers','grades','classes','selectedTeacherId','currentHomeroom'));
    }

    public function homeroomStore(Request $request)
    {
        abort_if(Auth::user()->role !== 'admin', 403);

        $data = $request->validate([
            'teacher_id'   => ['required','integer','exists:users,id'],
            'classroom_id' => ['required','integer','exists:classrooms,id'],
        ]);

        DB::table('homeroom_assignments')
            ->where('teacher_id', $data['teacher_id'])
            ->whereNull('until_date')
            ->update(['until_date' => now()->toDateString(), 'updated_at' => now()]);

        DB::table('homeroom_assignments')->insert([
            'teacher_id'   => $data['teacher_id'],
            'classroom_id' => $data['classroom_id'],
            'since_date'   => now()->toDateString(),
            'until_date'   => null,
            'created_at'   => now(), 'updated_at' => now(),
        ]);

        return back()->with('status', '担任のクラス割当を実施しました。');
    }

    public function enrollmentImportForm()
    {
        abort_if(Auth::user()->role !== 'admin', 403);
        
        $grades = DB::table('grades')->orderBy('id')->get();
        $classes = DB::table('classrooms')
            ->join('grades', 'grades.id', '=', 'classrooms.grade_id')
            ->select('classrooms.id', 'classrooms.name as cname', 'grades.name as gname')
            ->orderBy('grades.id')->orderBy('classrooms.name')
            ->get();
        
        return view('admin.assign.enrollment_import', compact('grades', 'classes'));
    }

    public function enrollmentImportCSV(Request $request)
    {
        abort_if(Auth::user()->role !== 'admin', 403);

        $request->validate([
            'csv_file' => ['required', 'mimes:csv,txt', 'max:1024'],
        ]);

        $file = $request->file('csv_file');
        $filePath = $file->getRealPath();

        $handle = fopen($filePath, 'r');
        
        // BOMをスキップ
        $bom = fread($handle, 3);
        if ($bom !== chr(0xEF) . chr(0xBB) . chr(0xBF)) {
            rewind($handle);
        }

        $successCount = 0;
        $errorCount = 0;
        $errors = [];

        // ヘッダー行をスキップ
        fgetcsv($handle);

        DB::beginTransaction();
        try {
            while (($data = fgetcsv($handle)) !== FALSE) {
                if (count($data) < 2) {
                    continue;
                }

                $email = trim($data[0]);
                $classroomName = trim($data[1]);

                if (empty($email) || empty($classroomName)) {
                    $errorCount++;
                    $errors[] = "行 " . ($successCount + $errorCount) . ": 必須項目が不足しています";
                    continue;
                }

                // メールアドレスで生徒を検索
                $student = DB::table('users')
                    ->where('email', $email)
                    ->where('role', 'student')
                    ->first();

                if (!$student) {
                    $errorCount++;
                    $errors[] = "行 " . ($successCount + $errorCount) . ": 生徒が見つかりません: $email";
                    continue;
                }

                // クラス名でクラスを検索
                $classroom = DB::table('classrooms')
                    ->where('name', $classroomName)
                    ->first();

                if (!$classroom) {
                    $errorCount++;
                    $errors[] = "行 " . ($successCount + $errorCount) . ": クラスが見つかりません: $classroomName";
                    continue;
                }

                // 既存のアクティブな在籍を非アクティブにする
                DB::table('enrollments')
                    ->where('student_id', $student->id)
                    ->where('is_active', true)
                    ->update(['is_active' => false, 'until_date' => now()->toDateString(), 'updated_at' => now()]);

                // 新しい在籍を作成
                DB::table('enrollments')->insert([
                    'student_id' => $student->id,
                    'classroom_id' => $classroom->id,
                    'is_active' => true,
                    'since_date' => now()->toDateString(),
                    'until_date' => null,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);

                $successCount++;
            }

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('admin.assign.enrollment.import')
                ->with('error', 'CSVのインポート中にエラーが発生しました: ' . $e->getMessage());
        }

        fclose($handle);

        $message = "{$successCount}件のクラス割り当てを実施しました。";
        if ($errorCount > 0) {
            $message .= " {$errorCount}件のエラーがありました。";
        }

        return redirect()->route('admin.assign.enrollment.import')
            ->with('status', $message)
            ->with('errors', $errors);
    }
}