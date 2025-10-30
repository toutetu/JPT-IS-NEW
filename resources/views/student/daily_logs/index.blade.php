@extends('layouts.app')

@section('content')
<div class="container">
  <h1 class="h4 mb-3">マイ連絡帳</h1>

  @if (session('status'))
    <div class="alert alert-success">{{ session('status') }}</div>
  @endif

  {{-- 所属情報 --}}
  <div class="card mb-3">
    <div class="card-body py-2">
      <div class="d-flex flex-wrap align-items-center gap-3">
        <div><strong>所属クラス</strong>：{{ $classroomName ?? '未設定' }}</div>
        <div><strong>担任</strong>：{{ $homeroomTeacherName ?? '未割り当て' }}</div>
      </div>
    </div>
  </div>

  <div class="mb-3">
    <a href="{{ route('student.daily_logs.create') }}" class="btn btn-primary btn-sm">新規提出</a>
  </div>


  <table class="table table-sm">
    <thead>
      <tr>
        <th>対象日</th>
        <th>体調</th>
        <th>メンタル</th>
        <th>内容(冒頭)</th>
        <th>既読</th>
        <th>操作</th>
      </tr>
    </thead>
    <tbody>
      @forelse($logs as $log)
        <tr>
          <td>{{ \App\Helpers\DateHelper::formatWithWeekday($log->target_date) }}</td>
          <td>{{ $log->health_score }}</td>
          <td>{{ $log->mental_score }}</td>
          <td>
            @php $body = $log->body ?? ''; @endphp
            <span class="d-inline d-md-none">
              {{ mb_strlen($body) > 10 ? mb_substr($body, 0, 10) . '…' : $body }}
            </span>
            <span class="d-none d-md-inline">
              {{ mb_strlen($body) > 20 ? mb_substr($body, 0, 20) . '…' : $body }}
            </span>
          </td>
          <td>{{ $log->read_at ? '👍既読' : '未読' }}</td>
          <td>
            <a class="btn btn-outline-secondary btn-sm" href="{{ route('student.daily_logs.show', $log->id) }}">詳細</a>
          </td>
        </tr>
      @empty
        <tr><td colspan="4">まだ提出がありません。</td></tr>
      @endforelse
    </tbody>
  </table>

  {{ $logs->links('pagination::bootstrap-4') }}

  {{-- 期間検索フォーム（常時表示・ページネーション下） --}}
  <div class="card mt-3">
    <div class="card-body py-2">
      <form method="get" action="{{ route('student.daily_logs.index') }}" class="row g-2 align-items-end">
        <div class="col-sm-6 col-md-3">
          <label class="form-label mb-1">開始日</label>
          <input type="date" name="date_from" value="{{ $dateFrom }}" class="form-control form-control-sm">
        </div>
        <div class="col-sm-6 col-md-3">
          <label class="form-label mb-1">終了日</label>
          <input type="date" name="date_to" value="{{ $dateTo }}" class="form-control form-control-sm">
        </div>
        <div class="col-sm-12 col-md-auto d-flex gap-2">
          <button type="submit" class="btn btn-outline-primary btn-sm">検索</button>
          <a href="{{ route('student.daily_logs.index') }}" class="btn btn-outline-secondary btn-sm">リセット</a>
        </div>
      </form>
    </div>
  </div>
</div>
@endsection
