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
      <h5 class="card-title">CSVファイル形式（新規ユーザー登録用）</h5>
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
      <div class="alert alert-info mb-0 mt-2">
        <strong>CSVファイルの要件:</strong>
        <ul class="mb-0">
          <li>ヘッダー行は不要です</li>
          <li>名前：文字列（最大255文字）</li>
          <li>メール：メールアドレス形式、重複不可</li>
          <li>ロール：student, teacher, admin のいずれか</li>
          <li>パスワード：8文字以上</li>
          <li>文字コード：UTF-8（Excelで保存する場合は「CSV UTF-8（コンマ区切り）」を選択）</li>
          <li>区切り文字：カンマ（,）</li>
          <li>最大ファイルサイズ：1MB</li>
        </ul>
      </div>
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
    <h6>CSVファイルの作成例（新規ユーザー登録用）</h6>
    <div class="alert alert-light border">
      <p class="mb-2"><strong>例：生徒40名を一括登録する場合</strong></p>
      <pre class="bg-light p-3 border rounded mb-0" style="max-height: 300px; overflow-y: auto;"><code>山田太郎,yamada@example.com,student,Passw0rd!
佐藤花子,sato@example.com,student,Passw0rd!
鈴木一郎,suzuki@example.com,student,Passw0rd!
田中花子,tanaka@example.com,student,Passw0rd!
山本太郎,yamamoto@example.com,student,Passw0rd!
（以下、同様に続く...）</code></pre>
    </div>
  </div>
</div>
@endsection
