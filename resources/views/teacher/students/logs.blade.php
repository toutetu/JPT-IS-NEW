@extends('layouts.app')

@section('content')
@php($w = ['日','月','火','水','木','金','土'])
<div class="container">
  <h1 class="h4 mb-3">過去記録 - {{ $student->name }}</h1>

  @if (session('status'))
    <div class="alert alert-success">{{ session('status') }}</div>
  @endif

  {{-- 生徒情報 --}}
  <div class="card mb-3">
    <div class="card-body">
      <div class="row">
        <div class="col-md-6">
          <strong>生徒名:</strong> {{ $student->name }}<br>
          <strong>メール:</strong> {{ $student->email }}
        </div>
        <div class="col-md-6">
          <strong>学年:</strong> {{ $student->grade_name }}<br>
          <strong>クラス:</strong> {{ $student->classroom_name }}
        </div>
      </div>
    </div>
  </div>

  {{-- 日付フィルタ --}}
  <div class="card mb-3">
    <div class="card-header">期間指定</div>
    <div class="card-body">
      <form method="get" class="row g-2">
        <div class="col-auto">
          <label class="col-form-label">開始日</label>
        </div>
        <div class="col-auto">
          <input type="date" name="date_from" class="form-control" value="{{ $dateFrom }}">
        </div>
        <div class="col-auto">
          <label class="col-form-label">終了日</label>
        </div>
        <div class="col-auto">
          <input type="date" name="date_to" class="form-control" value="{{ $dateTo }}">
        </div>
        <div class="col-auto">
          <button class="btn btn-outline-primary">表示</button>
        </div>
        <div class="col-auto">
          <a href="{{ route('teacher.students.logs', $student->id) }}" class="btn btn-outline-secondary">リセット</a>
        </div>
      </form>
      <small class="text-muted">
        期間を指定しない場合は過去30日間の記録を表示します。
      </small>
    </div>
  </div>

  {{-- 記録一覧 --}}
  <h2 class="h5 mb-3">連絡帳記録一覧</h2>
  
  @if($logs->count() > 0)
    <table class="table table-sm">
      <thead>
        <tr>
          <th>対象日</th>
          <th>体調</th>
          <th>メンタル</th>
          <th>本文</th>
          <th>提出日時</th>
          <th>既読状況</th>
          <th>操作</th>
        </tr>
      </thead>
      <tbody>
        @foreach($logs as $log)
          <tr>
            <td>
              {{ \Carbon\Carbon::parse($log->target_date)->format('Y-m-d') }}
              ({{ $w[\Carbon\Carbon::parse($log->target_date)->dayOfWeek] }})
            </td>
            <td>
              <span class="badge bg-{{ $log->health_score >= 4 ? 'success' : ($log->health_score >= 3 ? 'warning' : 'danger') }}">
                {{ $log->health_score }}
              </span>
            </td>
            <td>
              <span class="badge bg-{{ $log->mental_score >= 4 ? 'success' : ($log->mental_score >= 3 ? 'warning' : 'danger') }}">
                {{ $log->mental_score }}
              </span>
            </td>
            <td class="text-start align-top" style="max-width: 300px; white-space: pre-wrap;">
              {{ \Illuminate\Support\Str::limit($log->body, 100, '…') }}
            </td>
            <td>
              {{ \Carbon\Carbon::parse($log->submitted_at)->format('Y-m-d H:i') }}
            </td>
            <td>
              @if($log->read_at)
                <span class="text-success">
                  👍 既読<br>
                  <small>{{ \Carbon\Carbon::parse($log->read_at)->format('Y-m-d H:i') }}</small>
                </span>
              @else
                <span class="text-danger">未読</span>
              @endif
            </td>
            <td>
              <a class="btn btn-outline-secondary btn-sm" 
                 href="{{ route('teacher.daily_logs.show', ['id' => $log->id, 'date' => $log->target_date]) }}">
                詳細
              </a>
              @if(!$log->read_at)
                <form method="post" action="{{ route('teacher.daily_logs.read', $log->id) }}" class="d-inline">
                  @csrf
                  <button class="btn btn-outline-primary btn-sm" type="submit">既読にする</button>
                </form>
              @endif
            </td>
          </tr>
        @endforeach
      </tbody>
    </table>

    {{ $logs->links('pagination::bootstrap-4') }}
  @else
    <div class="alert alert-info">
      指定期間内に連絡帳の記録がありません。
    </div>
  @endif

  <div class="mt-4">
    <a href="{{ route('teacher.students.index') }}" class="btn btn-outline-secondary">
      生徒一覧に戻る
    </a>
    <a href="{{ route('teacher.daily_logs.index') }}" class="btn btn-outline-secondary">
      提出状況一覧に戻る
    </a>
  </div>
</div>
@endsection


