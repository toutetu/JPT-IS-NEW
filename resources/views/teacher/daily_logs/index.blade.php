@extends('layouts.app')

@section('content')
@php($w = ['日','月','火','水','木','金','土'])
<div class="container">
  <h1 class="h4 mb-3">提出状況@if($teacherAssignedClasses)（{{ $teacherAssignedClasses }}）@else（担当クラス）@endif</h1>

  {{-- 日付フィルタ --}}
  <form method="get" class="row g-2 mb-3">
    <div class="col-auto">
      <label class="col-form-label">対象日</label>
    </div>
    <div class="col-auto">
      <input type="date" name="date" class="form-control" value="{{ $selected }}">
    </div>
    <div class="col-auto">
      <button class="btn btn-outline-primary">表示</button>
    </div>
    <div class="col-auto align-self-center">
      <span class="ms-2 text-muted">
        表示日：
        {{ \Carbon\Carbon::parse($selected)->format('Y-m-d') }} ({{ $w[\Carbon\Carbon::parse($selected)->dayOfWeek] }})
      </span>
    </div>
  </form>

  {{-- KPI --}}
  <div class="row g-3 mb-3">
    <div class="col-md-3">
      <div class="card card-body">
        <div class="text-muted small">対象生徒数</div>
        <div class="fs-4 fw-bold">{{ $totalStudents }}</div>
      </div>
    </div>
    <div class="col-md-3">
      <div class="card card-body">
        <div class="text-muted small">提出済</div>
        <div class="fs-4 fw-bold">{{ $submittedCount }}</div>
      </div>
    </div>
    <div class="col-md-3">
      <div class="card card-body">
        <div class="text-muted small">未提出</div>
        <div class="fs-4 fw-bold">{{ max($totalStudents - $submittedCount, 0) }}</div>
      </div>
    </div>
    <div class="col-md-3">
      <div class="card card-body">
        <div class="text-muted small">未読</div>
        <div class="fs-4 fw-bold">{{ $unreadCount }}</div>
      </div>
    </div>
  </div>

  {{-- 未提出者リスト --}}
  <div class="card mb-3">
    <div class="card-header">未提出者（{{ count($unsubmitted) }}名）</div>
    <div class="card-body p-0">
      <table class="table table-sm mb-0">
        <tbody>
          @forelse($unsubmitted as $u)
            <tr>
              <td>{{ $u->name }}</td>
              <td class="text-muted small">{{ $u->email }}</td>
            </tr>
          @empty
            <tr><td class="text-muted small">全員提出済みです</td></tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </div>

  {{-- 提出一覧（選択日） --}}
  <h2 class="h5">提出一覧</h2>
    <table class="table table-sm">
    <thead>
      <tr>
        <th>対象日</th>
        <th>生徒</th>
        <th>体調</th>
        <th>メンタル</th>
        <th>本文</th>
        <th>状態</th>
        <th>操作</th>
      </tr>
    </thead>
    <tbody>
      @forelse($logs as $log)
        <tr>
          <td>
            {{ \Carbon\Carbon::parse($log->target_date)->format('Y-m-d') }}
            ({{ ['日','月','火','水','木','金','土'][\Carbon\Carbon::parse($log->target_date)->dayOfWeek] }})
          </td>
          <td>{{ $log->student_name }}</td>
          <td>{{ $log->health_score }}</td>
          <td>{{ $log->mental_score }}</td>
          {{-- 本文（長文対策：先頭100文字まで／左上詰め） --}}
          <td class="text-start align-top" style="max-width: 300px; white-space: pre-wrap;">
            {{ \Illuminate\Support\Str::limit($log->body, 100, '…') }}
          </td>
          <td>
            @if($log->read_at)
              👍 既読（{{ \Carbon\Carbon::parse($log->read_at)->format('Y-m-d') }}
              ({{ ['日','月','火','水','木','金','土'][\Carbon\Carbon::parse($log->read_at)->dayOfWeek] }})）
            @else
              未読
            @endif
          </td>

          <td class="d-flex gap-1">
            {{-- 詳細ページへのリンク --}}
            <a class="btn btn-outline-secondary btn-sm"
              href="{{ route('teacher.daily_logs.show', ['id' => $log->id, 'date' => $selected]) }}">
              詳細
            </a>

            {{-- 既読ボタン：一覧でも即操作可 --}}
            @if(!$log->read_at)
              <form method="post" action="{{ route('teacher.daily_logs.read', $log->id) }}">
                @csrf
                <button class="btn btn-outline-primary btn-sm" type="submit">既読にする</button>
              </form>
            @endif
          </td>
        </tr>
      @empty
        <tr><td colspan="7" class="text-muted">表示日には提出がありません。</td></tr>
      @endforelse
    </tbody>
  </table>

  {{ $logs->links('pagination::bootstrap-4') }}
</div>
@endsection
