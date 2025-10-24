@extends('layouts.app')

@section('content')
<div class="container">
  <h1 class="h4 mb-3">マイ連絡帳</h1>

  @if (session('status'))
    <div class="alert alert-success">{{ session('status') }}</div>
  @endif

  <div class="mb-3">
    <a href="{{ route('student.daily_logs.create') }}" class="btn btn-primary btn-sm">新規提出</a>
  </div>

  <table class="table table-sm">
    <thead>
      <tr>
        <th>対象日</th>
        <th>体調</th>
        <th>メンタル</th>
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
</div>
@endsection
