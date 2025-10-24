<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DailyLogController extends Controller
{
    // 一覧（自分の提出のみ）
    public function index()
    {
        abort_if(Auth::user()->role !== 'student', 403);

        $logs = DB::table('daily_logs')
            ->where('student_id', Auth::id())
            ->orderByDesc('target_date')
            ->paginate(10);

        return view('student.daily_logs.index', compact('logs'));
    }

    // カレンダー表示
    public function calendar(Request $request)
    {
        abort_if(Auth::user()->role !== 'student', 403);

        // 年月の取得（デフォルトは現在の年月）
        $year = $request->get('year', now()->year);
        $month = $request->get('month', now()->month);
        
        // カレンダーの開始日と終了日を計算
        $startDate = Carbon::create($year, $month, 1)->startOfMonth();
        $endDate = $startDate->copy()->endOfMonth();
        
        // 提出済みの日付を取得
        $submittedDates = DB::table('daily_logs')
            ->where('student_id', Auth::id())
            ->whereBetween('target_date', [$startDate->toDateString(), $endDate->toDateString()])
            ->pluck('target_date')
            ->toArray();

        return view('student.calendar', compact('year', 'month', 'submittedDates', 'startDate', 'endDate'));
    }

    // 作成フォーム（target_date は前日を自動セット）
    public function create()
    {
        abort_if(Auth::user()->role !== 'student', 403);

        // 前登校日の簡易ロジック：
        // 月曜なら前の金曜、それ以外は前日（祝日考慮は課題2以降）
        $today = \Illuminate\Support\Carbon::today();
        $targetDate = $today->isMonday()
            ? $today->subDays(3)->toDateString()  // 月曜→金曜
            : $today->subDay()->toDateString();   // それ以外→前日

        return view('student.daily_logs.create', compact('targetDate'));
    }


    // 保存
    public function store(Request $request)
    {
        abort_if(Auth::user()->role !== 'student', 403);

        $data = $request->validate([
            'target_date'  => ['required','date'],
            'health_score' => ['required','integer','between:1,5'],
            'mental_score' => ['required','integer','between:1,5'],
            'body'         => ['required','string','max:4000'],
        ]);

        // 重複提出の防止（同一 student_id × target_date は一意）
        $exists = DB::table('daily_logs')
            ->where('student_id', Auth::id())
            ->where('target_date', $data['target_date'])
            ->exists();

        if ($exists) {
            return back()->withErrors(['target_date' => 'この対象日では既に提出済みです。'])->withInput();
        }

        DB::table('daily_logs')->insert([
            'student_id'   => Auth::id(),
            'target_date'  => $data['target_date'],
            'health_score' => $data['health_score'],
            'mental_score' => $data['mental_score'],
            'body'         => $data['body'],
            'submitted_at' => now(),
            'read_at'      => null,
            'read_by'      => null,
            'created_at'   => now(),
            'updated_at'   => now(),
        ]);

        return redirect()->route('student.daily_logs.index')->with('status', '提出しました。');
    }

        public function show($id)
        {
            abort_if(Auth::user()->role !== 'student', 403);

            $log = \DB::table('daily_logs')
                ->where('id', $id)
                ->where('student_id', Auth::id())
                ->first();

            abort_unless($log, 404);

            return view('student.daily_logs.show', compact('log'));
        }

        public function edit($id)
        {
            abort_if(Auth::user()->role !== 'student', 403);

            $log = \DB::table('daily_logs')
                ->where('id', $id)
                ->where('student_id', Auth::id())
                ->first();

            abort_unless($log, 404);

            // 既読になっていたら編集不可
            if ($log->read_at) {
                return redirect()->route('student.daily_logs.show', $id)
                    ->withErrors(['edit' => '既読の記録は修正できません。']);
            }

            // 編集フォームは target_date も編集可（要件2の一部）
            return view('student.daily_logs.edit', compact('log'));
        }

        public function update(Request $request, $id)
        {
            abort_if(Auth::user()->role !== 'student', 403);

            $log = \DB::table('daily_logs')
                ->where('id', $id)
                ->where('student_id', Auth::id())
                ->first();

            abort_unless($log, 404);

            // 既読は編集禁止
            if ($log->read_at) {
                return redirect()->route('student.daily_logs.show', $id)
                    ->withErrors(['edit' => '既読の記録は修正できません。']);
            }

            $data = $request->validate([
                'target_date'  => ['required','date'],
                'health_score' => ['required','integer','between:1,5'],
                'mental_score' => ['required','integer','between:1,5'],
                'body'         => ['required','string','max:4000'],
            ]);

            // 同一 student × target_date の重複を自分以外でチェック
            $exists = \DB::table('daily_logs')
                ->where('student_id', Auth::id())
                ->where('target_date', $data['target_date'])
                ->where('id', '!=', $id)
                ->exists();

            if ($exists) {
                return back()->withErrors(['target_date' => 'この対象日では既に別の提出があります。'])->withInput();
            }

            \DB::table('daily_logs')
                ->where('id', $id)
                ->update([
                    'target_date'  => $data['target_date'],
                    'health_score' => $data['health_score'],
                    'mental_score' => $data['mental_score'],
                    'body'         => $data['body'],
                    'updated_at'   => now(),
                ]);

            return redirect()->route('student.daily_logs.show', $id)->with('status', '修正して保存しました。');
        }

}