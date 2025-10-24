@extends('layouts.app')

@section('content')
<div class="container">
  <h1 class="h4 mb-3">ユーザー一覧</h1>

  @if (session('status'))
    <div class="alert alert-success">{{ session('status') }}</div>
  @endif

  <div class="mb-3">
    <a href="{{ route('admin.users.create') }}" class="btn btn-primary btn-sm">新規ユーザー作成</a>
  </div>

  <table class="table table-sm">
    <thead>
      <tr>
        <th>ID</th>
        <th>名前</th>
        <th>メール</th>
        <th>ロール</th>
        <th>割り当てクラス</th>  
        <th>作成日</th>
        <th>割り当て変更</th> 
      </tr>
    </thead>
    <tbody>
      @forelse($users as $u)
        <tr>
          <td>{{ $u->id }}</td>
          <td>{{ $u->name }}</td>
          <td>{{ $u->email }}</td>
          <td>{{ $u->role }}</td>
          <td>{{ $u->assigned_class ?? '—' }}</td>
          <td>{{ \Carbon\Carbon::parse($u->created_at)->format('Y-m-d') }}</td>
          <td>
            @if($u->role === 'student')
              <a class="btn btn-sm btn-outline-primary"
                href="{{ route('admin.assign.enrollment.form', ['student_id' => $u->id]) }}">
                在籍を変更
              </a>
            @elseif($u->role === 'teacher')
              <a class="btn btn-sm btn-outline-primary"
                href="{{ route('admin.assign.homeroom.form', ['teacher_id' => $u->id]) }}">
                担任割当を変更
              </a>
            @else
              <span class="text-muted">—</span>
            @endif
          </td>
        </tr>
      @empty
        <tr><td colspan="7">ユーザーがいません。</td></tr>
      @endforelse
    </tbody>

  </table>

  {{ $users->links('pagination::bootstrap-4') }}
</div>
@endsection
