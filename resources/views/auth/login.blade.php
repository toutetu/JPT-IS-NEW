@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">ログイン</div>

                <div class="card-body">
                    <form method="POST" action="{{ route('login') }}">
                        @csrf

                        <div class="row mb-3">
                            <label for="email" class="col-md-4 col-form-label text-md-end">メールアドレス</label>

                            <div class="col-md-6">
                                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>

                                @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="password" class="col-md-4 col-form-label text-md-end">パスワード</label>

                            <div class="col-md-6">
                                <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="current-password">

                                @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6 offset-md-4">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>

                                    <label class="form-check-label" for="remember">
                                        ログイン状態を保持する
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="row mb-0">
                            <div class="col-md-8 offset-md-4">
                                <button type="submit" class="btn btn-primary">
                                    ログイン
                                </button>

                                @if (Route::has('password.request'))
                                    <a class="btn btn-link" href="{{ route('password.request') }}">
                                        パスワードをお忘れですか？
                                    </a>
                                @endif
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <!-- テスト用アカウント情報 -->
            <div class="card mt-4 border-info">
                <div class="card-header bg-info text-white">
                    <i class="fas fa-info-circle"></i> テスト用アカウント
                </div>
                <div class="card-body">
                    <p class="mb-3 text-muted small">
                        以下のテストアカウントでログインできます。
                    </p>
                    <div class="table-responsive">
                        <table class="table table-sm table-bordered mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>ロール</th>
                                    <th>メールアドレス</th>
                                    <th>パスワード</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td><span class="badge bg-danger">システム管理者</span></td>
                                    <td><code>admin@example.com</code></td>
                                    <td><code>Passw0rd!</code></td>
                                </tr>
                                <tr>
                                    <td><span class="badge bg-success">教師</span></td>
                                    <td><code>teacher1@example.com</code></td>
                                    <td><code>Passw0rd!</code></td>
                                </tr>
                                <tr>
                                    <td><span class="badge bg-primary">生徒</span></td>
                                    <td><code>student001@example.com</code></td>
                                    <td><code>Passw0rd!</code></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-3">
                        <p class="mb-0 small text-muted">
                            <i class="fas fa-book"></i> 
                            全てのテストアカウントは
                            <a href="https://github.com/{{ config('app.github_repo', 'your-repo') }}/blob/main/doc/アプリケーションのマニュアル/テストアカウント一覧.md" target="_blank" class="text-decoration-none">
                                テストアカウント一覧.md
                            </a>
                            をご確認ください。
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
