@extends('layouts.app')

@section('content')
<div class="container" style="max-width:720px;">
  <h1 class="h4 mb-3">CSV一括登録</h1>

  @if (session('status'))
    <div class="alert alert-success">{{ session('status') }}</div>
  @endif

  @if (session('error'))
    <div class="alert alert-danger">{{ session('error') }}</div>
  @endif

  @if (isset($errors) && count($errors) > 0)
    <div class="alert alert-warning">
      <strong>エラー詳細:</strong>
      <ul class="mb-0">
        @foreach($errors as $error)
          <li>{{ $error }}</li>
        @endforeach
      </ul>
    </div>
  @endif

  <div class="card mb-3">
    <div class="card-body">
      <h5 class="card-title">CSVファイル形式</h5>
      <p>CSVファイルは以下の形式で作成してください。</p>
      <table class="table table-sm table-bordered">
        <thead>
          <tr>
            <th>名前</th>
            <th>メール</th>
            <th>ロール</th>
            <th>パスワード</th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td>山田太郎</td>
            <td>yamada@example.com</td>
            <td>student</td>
            <td>Passw0rd!</td>
          </tr>
        </tbody>
      </table>
      <p class="text-muted small mb-0">
        ※ ヘッダー行は不要です<br>
        ※ ロールは student, teacher, admin のいずれか<br>
        ※ パスワードは8文字以上<br>
        ※ メールアドレスは重複不可
      </p>
    </div>
  </div>

  <form method="POST" action="{{ route('admin.users.import.csv') }}" enctype="multipart/form-data">
    @csrf

    <div class="mb-3">
      <label for="csv_file" class="form-label">CSVファイルを選択</label>
      <input type="file" class="form-control" id="csv_file" name="csv_file" accept=".csv,.txt" required>
      <div class="form-text">最大1MBまで</div>
    </div>

    <div class="d-flex gap-2">
      <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary">戻る</a>
      <button type="submit" class="btn btn-primary">インポート</button>
    </div>
  </form>

  <div class="mt-4">
    <h6>CSVファイルの作成例</h6>
    <pre class="bg-light p-3 border rounded"><code>山田太郎,yamada@example.com,student,Passw0rd!
佐藤花子,sato@example.com,student,Passw0rd!
鈴木一郎,suzuki@example.com,teacher,Passw0rd!</code></pre>
  </div>
</div>
@endsection
