@extends('layouts.app')

@section('content')
<div class="container" style="max-width:720px;">
  <h1 class="h4 mb-3 text-danger">ユーザー削除確認</h1>

  @if (session('error'))
    <div class="alert alert-danger">{{ session('error') }}</div>
  @endif

  <div class="alert alert-danger">
    <strong>警告:</strong> この操作は取り消すことができません。
  </div>

  <div class="card mb-3">
    <div class="card-body">
      <h5 class="card-title">削除対象ユーザー</h5>
      <table class="table table-sm">
        <tr>
          <th style="width: 30%;">ID</th>
          <td>{{ $user->id }}</td>
        </tr>
        <tr>
          <th>名前</th>
          <td>{{ $user->name }}</td>
        </tr>
        <tr>
          <th>メール</th>
          <td>{{ $user->email }}</td>
        </tr>
        <tr>
          <th>ロール</th>
          <td>{{ $user->role }}</td>
        </tr>
        <tr>
          <th>割り当てクラス</th>
          <td>{{ $user->assigned_class ?? '—' }}</td>
        </tr>
        <tr>
          <th>作成日</th>
          <td>{{ \Carbon\Carbon::parse($user->created_at)->format('Y-m-d') }}</td>
        </tr>
      </table>
    </div>
  </div>

  <div class="card mb-3 border-danger">
    <div class="card-body">
      <h6 class="card-title text-danger">削除されるデータ</h6>
      <ul class="mb-0">
        <li>ユーザー情報</li>
        <li>在籍・担任割当データ</li>
        <li>日報データ</li>
      </ul>
    </div>
  </div>

  <form method="POST" action="{{ route('admin.users.destroy', $user->id) }}">
    @csrf
    @method('DELETE')

    <div class="d-flex gap-2">
      <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary">キャンセル</a>
      <button type="submit" class="btn btn-danger" onclick="return confirm('本当にこのユーザーを削除しますか？この操作は取り消せません。');">
        削除する
      </button>
    </div>
  </form>
</div>
@endsection
