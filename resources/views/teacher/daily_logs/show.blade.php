@extends('layouts.app')

@section('content')
@php($w = ['日','月','火','水','木','金','土'])
<div class="container" style="max-width:820px;">
  <h1 class="h4 mb-3">
    提出内容（詳細）
    @if($log->read_at)
      <span class="badge bg-success fs-6 ms-2">👍 ✓ 既読</span>
    @endif
  </h1>

  @if (session('status'))
    <div class="alert alert-success">{{ session('status') }}</div>
  @endif
  @if ($errors->any())
    <div class="alert alert-danger">
      <ul class="mb-0">
        @foreach ($errors->all() as $e)
          <li>{{ $e }}</li>
        @endforeach
      </ul>
    </div>
  @endif

  <div class="card mb-3 @if($log->read_at) border-success @endif">
    <div class="card-body @if($log->read_at) bg-light @endif">
      <div class="row g-3">
        <div class="col-md-4">
          <div class="text-muted small">対象日</div>
          <div class="fw-bold">
            {{ \Carbon\Carbon::parse($log->target_date)->format('Y-m-d') }}
            ({{ $w[\Carbon\Carbon::parse($log->target_date)->dayOfWeek] }})
          </div>
        </div>
        <div class="col-md-4">
          <div class="text-muted small">生徒</div>
          <div class="fw-bold">{{ $log->student_name }}</div>
        </div>
        <div class="col-md-4">
          <div class="text-muted small">状態</div>
          <div class="fw-bold">
            @if($log->read_at)
              <div class="d-flex align-items-center">
                <span class="badge bg-success fs-6 me-2">👍 ✓ 既読</span>
                <small class="text-muted">{{ \Carbon\Carbon::parse($log->read_at)->format('Y-m-d') }} ({{ $w[\Carbon\Carbon::parse($log->read_at)->dayOfWeek] }})</small>
              </div>
            @else
              <span class="badge bg-warning fs-6">未読</span>
            @endif
          </div>
        </div>
        <div class="col-md-3">
          <div class="text-muted small">体調</div>
          <div class="fs-5">{{ $log->health_score }}</div>
        </div>
        <div class="col-md-3">
          <div class="text-muted small">メンタル</div>
          <div class="fs-5">{{ $log->mental_score }}</div>
        </div>
        <div class="col-12">
          <div class="text-muted small">本文</div>
          <div class="border rounded p-2 bg-light" style="white-space:pre-wrap;">{{ $log->body }}</div>
        </div>
      </div>
    </div>
  </div>

  <div class="d-flex gap-2">
    <a href="{{ route('teacher.daily_logs.index', ['date' => $backDate]) }}" class="btn btn-outline-secondary">一覧に戻る</a>

    @if(!$log->read_at)
      <form method="post" action="{{ route('teacher.daily_logs.read', $log->id) }}">
        @csrf
        <button class="btn btn-primary">この記録を既読にする</button>
      </form>
    @endif
  </div>
</div>
@endsection
