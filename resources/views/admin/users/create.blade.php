@extends('layouts.app')

@section('content')
<div class="container" style="max-width:720px;">
  <h1 class="h4 mb-3">新規ユーザー作成</h1>

  @if ($errors->any())
    <div class="alert alert-danger">
      <ul class="mb-0">
        @foreach ($errors->all() as $e)
          <li>{{ $e }}</li>
        @endforeach
      </ul>
    </div>
  @endif

  <form method="post" action="{{ route('admin.users.store') }}">
    @csrf

    <div class="mb-3">
      <label class="form-label">名前</label>
      <input type="text" name="name" class="form-control" value="{{ old('name') }}" required>
    </div>

    <div class="mb-3">
      <label class="form-label">メール</label>
      <input type="email" name="email" class="form-control" value="{{ old('email') }}" required>
    </div>

    <div class="mb-3">
      <label class="form-label">ロール</label>
      <select name="role" class="form-select" required>
        <option value="student" @selected(old('role')==='student')>student</option>
        <option value="teacher" @selected(old('role')==='teacher')>teacher</option>
        <option value="admin"   @selected(old('role')==='admin')>admin</option>
      </select>
    </div>

    <div class="mb-3">
      <label class="form-label">パスワード（8文字以上）</label>
      <input type="password" name="password" class="form-control" required minlength="8">
    </div>

    <div class="d-flex gap-2">
      <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary">戻る</a>
      <button type="submit" class="btn btn-primary">作成</button>
    </div>
  </form>
</div>
@endsection
