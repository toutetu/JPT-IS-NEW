@extends('layouts.app')

@section('content')
<div class="container" style="max-width:800px;">
  <h1 class="h4 mb-3">連絡帳 詳細</h1>

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

  <div class="card mb-3">
    <div class="card-body">
      <div class="row g-3">
        <div class="col-md-4">
          <div class="fw-bold">対象日</div>
          <div>{{ \App\Helpers\DateHelper::formatWithWeekday($log->target_date) }}</div>
        </div>
        <div class="col-md-4">
          <div class="fw-bold">体調</div>
          <div>{{ $log->health_score }}</div>
        </div>
        <div class="col-md-4">
          <div class="fw-bold">メンタル</div>
          <div>{{ $log->mental_score }}</div>
        </div>
        <div class="col-12">
          <div class="fw-bold">本文</div>
          <div class="border rounded p-2 bg-light">{{ $log->body }}</div>
        </div>
        <div class="col-12">
          <div class="fw-bold">状態</div>
          <div>
            @if($log->read_at)
              <span class="text-success">👍 既読</span>
            @else
              <span class="text-danger">未読</span>
            @endif
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="d-flex gap-2">
    <a href="{{ route('student.daily_logs.index') }}" class="btn btn-outline-secondary">一覧へ戻る</a>
    @if(!$log->read_at)
      <a href="{{ route('student.daily_logs.edit', $log->id) }}" class="btn btn-primary">修正する</a>
    @endif
  </div>
</div>
@endsection
