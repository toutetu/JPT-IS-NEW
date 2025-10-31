@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">パスワードリセット</div>

                <div class="card-body">
                    <!-- 未実装の注意書き -->
                    <div class="alert alert-warning mb-3" role="alert">
                        <i class="fas fa-exclamation-triangle"></i> 
                        <strong>注意</strong>: メール送信機能は現在未実装です。パスワードを忘れた場合は、システム管理者にお問い合わせください。
                    </div>

                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    <p class="text-muted mb-3">
                        登録済みのメールアドレスを入力してください。パスワードリセット用のリンクをお送りします。
                    </p>

                    <form method="POST" action="{{ route('password.email') }}">
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

                        <div class="row mb-0">
                            <div class="col-md-6 offset-md-4">
                                <button type="submit" class="btn btn-primary">
                                    パスワードリセットリンクを送信
                                </button>
                                <a class="btn btn-link" href="{{ route('login') }}">
                                    ログイン画面に戻る
                                </a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
