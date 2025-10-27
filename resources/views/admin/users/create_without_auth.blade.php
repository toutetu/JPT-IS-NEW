<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>新規ユーザー作成（ログイン不要）</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container" style="max-width:720px; margin-top: 50px;">
        <h1 class="h4 mb-3">新規ユーザー作成（ログイン不要）</h1>

        @if (session('status'))
            <div class="alert alert-success">{{ session('status') }}</div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        @if (session('success'))
            <div class="alert alert-success">✅ ユーザー作成が完了しました！</div>
        @endif

        <!-- デバッグ情報 -->
        @if(config('app.debug'))
            <div class="alert alert-info">
                <strong>デバッグ情報:</strong><br>
                Status: {{ session('status') ?? 'なし' }}<br>
                Success: {{ session('success') ? 'true' : 'false' }}<br>
                Error: {{ session('error') ?? 'なし' }}
            </div>
        @endif

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach ($errors->all() as $e)
                        <li>{{ $e }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="post" action="{{ route('admin.users.store_without_auth') }}">

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
                <a href="{{ route('login') }}" class="btn btn-outline-secondary">ログインページへ</a>
                <button type="submit" class="btn btn-primary">作成</button>
            </div>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
