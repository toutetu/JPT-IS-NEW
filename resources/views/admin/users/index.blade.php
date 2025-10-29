@extends('layouts.app')

@section('content')
<div class="container">
  
  @if (session('status'))
  <div class="alert alert-success">{{ session('status') }}</div>
  @endif
  
  @if (session('error'))
  <div class="alert alert-danger">{{ session('error') }}</div>
  @endif
  
  <div class="mb-3 d-flex justify-content-between align-items-center">
    <div>
      <a href="{{ route('admin.users.create') }}" class="btn btn-primary btn-sm">新規ユーザー作成</a>
      <a href="{{ route('admin.users.import') }}" class="btn btn-success btn-sm">新規ユーザー作成（CSV一括登録）</a>
      <a href="{{ route('admin.assign.enrollment.import') }}" class="btn btn-info btn-sm">在籍を変更（CSV一括登録）</a>
      <button type="button" class="btn btn-outline-danger btn-sm" data-bs-toggle="modal" data-bs-target="#deleteUserModal">
        <i class="fas fa-trash"></i> ユーザーを削除
      </button>
    </div>
    
  </div>
  <h1 class="h4 mb-3">ユーザー一覧</h1>
  <!-- 検索ボタン -->
  <button type="button" class="btn btn-outline-secondary btn-sm" onclick="toggleSearchForm()">
    <i class="fas fa-search"></i> 検索を表示
  </button>

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

  {{ $users->appends(request()->query())->links('pagination::bootstrap-4') }}
</div>

<!-- 削除ユーザー選択モーダル -->
<div class="modal fade" id="deleteUserModal" tabindex="-1" aria-labelledby="deleteUserModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title text-danger" id="deleteUserModalLabel">ユーザー削除</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <p class="text-danger"><strong>警告:</strong> 削除したいユーザーを選択してください。この操作は取り消すことができません。</p>
        <label for="deleteUserSelect" class="form-label">削除するユーザーを選択:</label>
        <select class="form-select" id="deleteUserSelect">
          <option value="">-- ユーザーを選択 --</option>
          @foreach($users as $u)
            @if($u->id != auth()->id())
              <option value="{{ $u->id }}" data-name="{{ $u->name }}" data-email="{{ $u->email }}">
                ID: {{ $u->id }} - {{ $u->name }} ({{ $u->email }})
              </option>
            @endif
          @endforeach
        </select>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">キャンセル</button>
        <button type="button" class="btn btn-danger" id="confirmDeleteBtn" disabled>削除ページへ</button>
      </div>
    </div>
  </div>
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

  // 削除ユーザー選択の制御
  const deleteUserSelect = document.getElementById('deleteUserSelect');
  const confirmDeleteBtn = document.getElementById('confirmDeleteBtn');
  
  deleteUserSelect.addEventListener('change', function() {
    if (this.value) {
      confirmDeleteBtn.disabled = false;
    } else {
      confirmDeleteBtn.disabled = true;
    }
  });

  confirmDeleteBtn.addEventListener('click', function() {
    const userId = deleteUserSelect.value;
    if (userId) {
      window.location.href = '/admin/users/' + userId + '/delete';
    }
  });
});
</script>
@endsection
