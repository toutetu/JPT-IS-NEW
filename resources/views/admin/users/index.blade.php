@extends('layouts.app')

@section('content')
<div class="container">
  <h1 class="h4 mb-3">ユーザー一覧</h1>

  @if (session('status'))
    <div class="alert alert-success">{{ session('status') }}</div>
  @endif

  @if (session('error'))
    <div class="alert alert-danger">{{ session('error') }}</div>
  @endif

  <div class="mb-3 d-flex justify-content-between align-items-center">
    <a href="{{ route('admin.users.create') }}" class="btn btn-primary btn-sm">新規ユーザー作成</a>
    
    <!-- 検索ボタン -->
    <button type="button" class="btn btn-outline-secondary btn-sm" onclick="toggleSearchForm()">
      <i class="fas fa-search"></i> 検索を表示
    </button>
  </div>

  <!-- 検索フォーム（初期状態は非表示） -->
  <div id="searchForm" class="card mb-4" style="display: none;">
    <div class="card-body">
      <form method="GET" action="{{ route('admin.users.index') }}" class="row g-3">
        <div class="col-md-4">
          <label for="search" class="form-label">名前・メールで検索</label>
          <input type="text" class="form-control" id="search" name="search" 
                 value="{{ $search }}" placeholder="名前またはメールアドレスを入力">
        </div>
        <div class="col-md-3">
          <label for="role" class="form-label">ロールで絞り込み</label>
          <select class="form-select" id="role" name="role">
            <option value="">すべて</option>
            <option value="admin" {{ $role === 'admin' ? 'selected' : '' }}>管理者</option>
            <option value="teacher" {{ $role === 'teacher' ? 'selected' : '' }}>教師</option>
            <option value="student" {{ $role === 'student' ? 'selected' : '' }}>生徒</option>
          </select>
        </div>
        <div class="col-md-3 d-flex align-items-end">
          <button type="submit" class="btn btn-outline-primary me-2">検索</button>
          <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary">リセット</a>
        </div>
      </form>
    </div>
  </div>

  <!-- 検索結果の表示 -->
  @if($search || $role)
    <div class="alert alert-info">
      <strong>検索結果:</strong>
      @if($search)
        名前・メール: "{{ $search }}"
      @endif
      @if($search && $role)
        、
      @endif
      @if($role)
        ロール: {{ $role === 'admin' ? '管理者' : ($role === 'teacher' ? '教師' : '生徒') }}
      @endif
      （{{ $users->total() }}件の結果）
    </div>
  @endif

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
        <th>操作</th>
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
          <td>
            @if($u->id != auth()->id())
              <form method="POST" action="{{ route('admin.users.destroy', $u->id) }}" 
                    onsubmit="return confirm('本当にこのユーザーを削除しますか？');" style="display:inline;">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-sm btn-outline-danger">
                  削除
                </button>
              </form>
            @else
              <span class="text-muted">—</span>
            @endif
          </td>
        </tr>
      @empty
        <tr><td colspan="8">ユーザーがいません。</td></tr>
      @endforelse
    </tbody>

  </table>

  {{ $users->appends(request()->query())->links('pagination::bootstrap-4') }}
</div>

<script>
function toggleSearchForm() {
  const searchForm = document.getElementById('searchForm');
  const searchButton = document.querySelector('button[onclick="toggleSearchForm()"]');
  
  if (searchForm.style.display === 'none') {
    searchForm.style.display = 'block';
    searchButton.innerHTML = '<i class="fas fa-times"></i> 検索を閉じる';
  } else {
    searchForm.style.display = 'none';
    searchButton.innerHTML = '<i class="fas fa-search"></i> 検索';
  }
}

// 検索条件がある場合は検索フォームを表示
document.addEventListener('DOMContentLoaded', function() {
  @if($search || $role)
    const searchForm = document.getElementById('searchForm');
    const searchButton = document.querySelector('button[onclick="toggleSearchForm()"]');
    searchForm.style.display = 'block';
    searchButton.innerHTML = '<i class="fas fa-times"></i> 検索を閉じる';
  @endif
});
</script>
@endsection
