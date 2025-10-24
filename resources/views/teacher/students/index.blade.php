@extends('layouts.app')

@section('content')
<div class="container">
  <h1 class="h4 mb-3">担当クラス生徒一覧</h1>

  @if (session('status'))
    <div class="alert alert-success">{{ session('status') }}</div>
  @endif

  <div class="mb-3">
    <p class="text-muted">担当クラスの生徒の過去記録を確認できます。</p>
  </div>

  <div class="row g-3">
    @forelse($students as $student)
      <div class="col-md-6 col-lg-4">
        <div class="card h-100">
          <div class="card-body">
            <h5 class="card-title">{{ $student->name }}</h5>
            <p class="card-text">
              <small class="text-muted">
                {{ $student->grade_name }} {{ $student->classroom_name }}<br>
                {{ $student->email }}
              </small>
            </p>
          </div>
          <div class="card-footer">
            <a href="{{ route('teacher.students.logs', $student->id) }}" class="btn btn-primary btn-sm">
              過去記録を確認
            </a>
          </div>
        </div>
      </div>
    @empty
      <div class="col-12">
        <div class="alert alert-info">
          担当クラスの生徒が登録されていません。
        </div>
      </div>
    @endforelse
  </div>

  <div class="mt-4">
    <a href="{{ route('teacher.daily_logs.index') }}" class="btn btn-outline-secondary">
      提出状況一覧に戻る
    </a>
  </div>
</div>
@endsection


