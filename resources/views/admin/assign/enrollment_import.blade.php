@extends('layouts.app')

@section('content')
<div class="container" style="max-width:720px;">
  <h1 class="h4 mb-3">CSV一括クラス割り当て</h1>

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
      <h5 class="card-title">CSVファイル形式（クラス割り当て用）</h5>
      <p>CSVファイルは以下の形式で作成してください。</p>
      <table class="table table-sm table-bordered">
        <thead>
          <tr>
            <th>メールアドレス</th>
            <th>クラス名</th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td>student001@example.com</td>
            <td>1年A組</td>
          </tr>
        </tbody>
      </table>
      <div class="alert alert-info mb-0 mt-2">
        <strong>CSVファイルの要件:</strong>
        <ul class="mb-0">
          <li>ヘッダー行は不要です</li>
          <li>メールアドレス：既存の生徒のものである必要があります</li>
          <li>クラス名：登録済みクラス名と完全一致である必要があります</li>
          <li>文字コード：UTF-8（Excelで保存する場合は「CSV UTF-8（コンマ区切り）」を選択）</li>
          <li>区切り文字：カンマ（,）</li>
          <li>最大ファイルサイズ：1MB</li>
        </ul>
      </div>
    </div>
  </div>

  <div class="card mb-3">
    <div class="card-body">
      <h6>登録済みクラス一覧</h6>
      <table class="table table-sm">
        <thead>
          <tr>
            <th>学年</th>
            <th>クラス名</th>
          </tr>
        </thead>
        <tbody>
          @foreach($classes->groupBy('gname') as $gradeName => $gradeClasses)
            @foreach($gradeClasses as $class)
              <tr>
                @if($loop->first)
                  <td rowspan="{{ $gradeClasses->count() }}">{{ $gradeName }}</td>
                @endif
                <td>{{ $class->cname }}</td>
              </tr>
            @endforeach
          @endforeach
        </tbody>
      </table>
    </div>
  </div>

  <form method="POST" action="{{ route('admin.assign.enrollment.import.csv') }}" enctype="multipart/form-data">
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
    <h6>CSVファイルの作成例（クラス割り当て用）</h6>
    <div class="alert alert-light border">
      <p class="mb-2"><strong>例：生徒40名のクラスを一括変更する場合</strong></p>
      <pre class="bg-light p-3 border rounded mb-0" style="max-height: 300px; overflow-y: auto;"><code>student001@example.com,1年A組
student002@example.com,1年A組
student003@example.com,1年A組
student004@example.com,1年B組
student005@example.com,1年B組
student006@example.com,1年B組
student007@example.com,2年A組
student008@example.com,2年A組
（以下、同様に続く...）</code></pre>
    </div>
  </div>
</div>
@endsection
