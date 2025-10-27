@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-12">
            <h1 class="h3 mb-4">担任ダッシュボード</h1>
            <p class="text-muted mb-4">こんにちは、{{ Auth::user()->name }}さん</p>
        </div>
    </div>

    {{-- 今日の提出状況サマリー --}}
    <div class="row g-3 mb-4">
        <div class="col-md-3">
            <div class="card bg-primary text-white">
                <div class="card-body text-center">
                    <div class="text-muted small">担当クラス数</div>
                    <div class="fs-3 fw-bold">{{ count($assignedClasses) }}</div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-info text-white">
                <div class="card-body text-center">
                    <div class="text-muted small">対象生徒数</div>
                    <div class="fs-3 fw-bold">{{ $totalStudents }}</div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-success text-white">
                <div class="card-body text-center">
                    <div class="text-muted small">今日の提出済</div>
                    <div class="fs-3 fw-bold">{{ $submittedToday }}</div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-warning text-white">
                <div class="card-body text-center">
                    <div class="text-muted small">未読</div>
                    <div class="fs-3 fw-bold">{{ $unreadToday }}</div>
                </div>
            </div>
        </div>
    </div>

    {{-- 担当クラス一覧 --}}
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">担当クラス一覧</h5>
                </div>
                <div class="card-body">
                    @if($assignedClasses->count() > 0)
                        <div class="row g-3">
                            @foreach($assignedClasses as $class)
                                <div class="col-md-6 col-lg-4">
                                    <div class="card h-100 border-primary">
                                        <div class="card-body">
                                            <h6 class="card-title text-primary">{{ $class->grade_name }} {{ $class->classroom_name }}</h6>
                                            <p class="card-text">
                                                <small class="text-muted">
                                                    生徒数: {{ $class->student_count }}名<br>
                                                    担当開始: {{ \Carbon\Carbon::parse($class->since_date)->format('Y年m月d日') }}
                                                </small>
                                            </p>
                                        </div>
                                        <div class="card-footer bg-transparent">
                                            <div class="d-grid gap-2">
                                                <a href="{{ route('teacher.daily_logs.index') }}" class="btn btn-outline-primary btn-sm">
                                                    提出状況を確認
                                                </a>
                                                <a href="{{ route('teacher.students.index') }}" class="btn btn-outline-secondary btn-sm">
                                                    生徒一覧
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center text-muted py-4">
                            <p>現在担当しているクラスはありません。</p>
                            <p>管理者にお問い合わせください。</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    {{-- クイックアクション --}}
    <div class="row mt-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">クイックアクション</h5>
                </div>
                <div class="card-body">
                    <div class="row g-2">
                        <div class="col-md-3">
                            <a href="{{ route('teacher.daily_logs.index') }}" class="btn btn-primary w-100">
                                <i class="fas fa-clipboard-list me-2"></i>
                                今日の提出状況
                            </a>
                        </div>
                        <div class="col-md-3">
                            <a href="{{ route('teacher.students.index') }}" class="btn btn-secondary w-100">
                                <i class="fas fa-users me-2"></i>
                                担当生徒一覧
                            </a>
                        </div>
                        <div class="col-md-3">
                            <a href="{{ route('teacher.daily_logs.index', ['date' => \Carbon\Carbon::yesterday()->toDateString()]) }}" class="btn btn-outline-primary w-100">
                                <i class="fas fa-calendar-day me-2"></i>
                                昨日の提出状況
                            </a>
                        </div>
                        <div class="col-md-3">
                            <a href="{{ route('teacher.daily_logs.index', ['date' => \Carbon\Carbon::tomorrow()->toDateString()]) }}" class="btn btn-outline-secondary w-100">
                                <i class="fas fa-calendar-plus me-2"></i>
                                明日の予定
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
