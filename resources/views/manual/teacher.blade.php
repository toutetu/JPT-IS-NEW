@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="mb-4 text-danger">先生向け 操作マニュアル</h1>

    <div class="card mb-4 shadow-sm">
        <div class="card-header bg-info text-white">
            <h2 class="h5 mb-0">📑 目次</h2>
        </div>
        <div class="card-body">
            <ol>
                <li><a href="#login">ログイン</a>
                    <ul>
                        <li><a href="#login-screen">ログイン画面</a></li>
                    </ul>
                </li>
                <li><a href="#submission-status">提出状況（担当クラス）</a>
                    <ul>
                        <li><a href="#submission-dashboard">提出状況ダッシュボード</a></li>
                    </ul>
                </li>
                <li><a href="#student-records">生徒別過去記録</a>
                    <ul>
                        <li><a href="#student-list">担当クラス生徒一覧</a></li>
                        <li><a href="#student-logs">生徒別過去記録一覧</a></li>
                    </ul>
                </li>
                <li><a href="#read-processing">既読処理</a>
                    <ul>
                        <li><a href="#log-detail">連絡帳詳細画面</a></li>
                    </ul>
                </li>
                <li><a href="#manual-logout">マニュアル/ログアウト</a>
                    <ul>
                        <li><a href="#nav-top">ナビゲーション（右上）</a></li>
                    </ul>
                </li>
            </ol>
        </div>
    </div>


    <div class="card mb-4 border-info shadow-sm" id="login">
        <div class="card-header bg-info text-white">
            <h2 class="h4 mb-0"><span class="badge bg-light text-info me-2 fs-6">1</span>ログイン</h2>
        </div>
        <div class="card-body">
            <div class="border-start border-info border-4 ps-3 mb-4">
                <h3 class="h5 mt-3" id="login-screen">
                    <span class="badge bg-info me-2">1-1</span>ログイン画面 
                    <small class="ms-2"><a href="{{ url('/login') }}" target="_blank" rel="noopener" class="btn btn-sm btn-outline-info">＜リンク＞</a></small>
                </h3>
                <div class="alert alert-info mb-3">
                    <strong class="text-info">【画面タイトル】</strong> <span class="fs-5">ログイン</span>
                </div>
                <h4 class="h6 mt-2 text-info"><i class="fas fa-eye"></i> 画面の見方</h4>
                <ul class="list-group mb-3">
                    <li class="list-group-item"><strong>メールアドレス</strong>: アカウントのメールアドレスを入力</li>
                    <li class="list-group-item"><strong>パスワード</strong>: 初期パスワードは <code>Passw0rd!</code></li>
                    <li class="list-group-item"><strong>ログインボタン</strong>: 認証を実行</li>
                </ul>
                <h4 class="h6 mt-2 text-success"><i class="fas fa-cog"></i> 操作方法</h4>
                <ol class="list-group list-group-numbered mb-3">
                    <li class="list-group-item">メールアドレスとパスワードを入力</li>
                    <li class="list-group-item">「ログイン」をクリック</li>
                </ol>
                <img src="{{ asset('storage/manual/login.png') }}" alt="ログイン画面" class="img-fluid border border-3 border-info mb-3 shadow-sm">
            </div>
        </div>
    </div>

    <div class="card mb-4 border-danger shadow-sm" id="submission-status">
        <div class="card-header bg-danger text-white">
            <h2 class="h4 mb-0"><span class="badge bg-light text-danger me-2 fs-6">2</span>提出状況（担当クラス）</h2>
        </div>
        <div class="card-body">
            <div class="border-start border-danger border-4 ps-3 mb-4">
                <h3 class="h5 mt-3" id="submission-dashboard">
                    <span class="badge bg-danger me-2">2-1</span>提出状況ダッシュボード 
                    <small class="ms-2"><a href="{{ url('/teacher/daily-logs') }}" target="_blank" rel="noopener" class="btn btn-sm btn-outline-danger">＜リンク＞</a></small>
                </h3>
                <div class="alert alert-danger mb-3">
                    <strong class="text-danger">【画面タイトル】</strong> <span class="fs-5">提出状況（担当クラス）</span>
                </div>
                <h4 class="h6 mt-2 text-danger"><i class="fas fa-eye"></i> 画面の見方</h4>
                <ul class="list-group mb-3">
                    <li class="list-group-item"><strong>日付フィルタ</strong>: 対象日を選択して表示</li>
                    <li class="list-group-item"><strong>KPI表示</strong>: 対象生徒数、提出済、未提出、未読の数</li>
                    <li class="list-group-item"><strong>未提出者リスト</strong>: 未提出の生徒一覧</li>
                    <li class="list-group-item"><strong>提出一覧</strong>: 提出された連絡帳の一覧</li>
                </ul>
                <h4 class="h6 mt-2 text-success"><i class="fas fa-cog"></i> 操作方法</h4>
                <ol class="list-group list-group-numbered mb-3">
                    <li class="list-group-item"><strong>日付選択</strong>: 日付フィルタで確認したい日を選択</li>
                    <li class="list-group-item"><strong>「表示」ボタン</strong>: 選択した日の状況を表示</li>
                    <li class="list-group-item"><strong>詳細確認</strong>: 「詳細」ボタンで内容を確認</li>
                    <li class="list-group-item"><strong>既読処理</strong>: 「既読にする」ボタンで既読に変更</li>
                </ol>
                <img src="{{ asset('storage/manual/teacher_daily_logs_index.png') }}" alt="提出状況一覧" class="img-fluid border border-3 border-danger mb-3 shadow-sm">
            </div>
        </div>
    </div>

    <div class="card mb-4 border-primary shadow-sm" id="student-records">
        <div class="card-header bg-primary text-white">
            <h2 class="h4 mb-0"><span class="badge bg-light text-primary me-2 fs-6">3</span>生徒別過去記録</h2>
        </div>
        <div class="card-body">
            <div class="border-start border-primary border-4 ps-3 mb-4">
                <h3 class="h5 mt-3" id="student-list">
                    <span class="badge bg-primary me-2">3-1</span>担当クラス生徒一覧 
                    <small class="ms-2"><a href="{{ url('/teacher/students') }}" target="_blank" rel="noopener" class="btn btn-sm btn-outline-primary">＜リンク＞</a></small>
                </h3>
                <div class="alert alert-primary mb-3">
                    <strong class="text-primary">【画面タイトル】</strong> <span class="fs-5">生徒別過去記録</span>
                </div>
                <h4 class="h6 mt-2 text-primary"><i class="fas fa-eye"></i> 画面の見方</h4>
                <ul class="list-group mb-3">
                    <li class="list-group-item"><strong>生徒一覧</strong>: 担当クラスの生徒一覧</li>
                    <li class="list-group-item"><strong>過去記録確認</strong>: 各生徒の過去記録を確認可能</li>
                </ul>
                <h4 class="h6 mt-2 text-success"><i class="fas fa-cog"></i> 操作方法</h4>
                <ol class="list-group list-group-numbered mb-3">
                    <li class="list-group-item"><strong>生徒選択</strong>: 確認したい生徒の「過去記録」をクリック</li>
                    <li class="list-group-item"><strong>記録確認</strong>: その生徒の過去の連絡帳記録を確認</li>
                </ol>
                <img src="{{ asset('storage/manual/teacher_students_index.png') }}" alt="生徒一覧" class="img-fluid border border-3 border-primary mb-3 shadow-sm">
            </div>
            
            <div class="border-start border-primary border-4 ps-3 mb-4">
                <h3 class="h5 mt-4" id="student-logs">
                    <span class="badge bg-primary me-2">3-2</span>生徒別過去記録一覧 
                    <small class="ms-2"><a href="{{ url('/teacher/students/8/logs') }}" target="_blank" rel="noopener" class="btn btn-sm btn-outline-primary">＜リンク＞</a></small>
                </h3>
                <div class="alert alert-primary mb-3">
                    <strong class="text-primary">【画面タイトル】</strong> <span class="fs-5">生徒の過去記録</span>
                </div>
                <h4 class="h6 mt-2 text-primary"><i class="fas fa-eye"></i> 画面の見方</h4>
                <ul class="list-group mb-3">
                    <li class="list-group-item"><strong>日付フィルタ</strong>: 過去30日（デフォルト）の範囲で表示</li>
                    <li class="list-group-item"><strong>記録一覧</strong>: 時系列で過去の記録を表示</li>
                    <li class="list-group-item"><strong>色分け表示</strong>: 体調・メンタルスコアの色分け</li>
                </ul>
                <h4 class="h6 mt-2 text-success"><i class="fas fa-cog"></i> 操作方法</h4>
                <ol class="list-group list-group-numbered mb-3">
                    <li class="list-group-item"><strong>日付範囲変更</strong>: フィルタで期間を調整</li>
                    <li class="list-group-item"><strong>詳細確認</strong>: 各記録の「詳細」ボタンで内容確認</li>
                    <li class="list-group-item"><strong>既読処理</strong>: 未読の場合は既読に変更可能</li>
                </ol>
                <img src="{{ asset('storage/manual/teacher_student_logs.png') }}" alt="生徒の過去記録" class="img-fluid border border-3 border-primary mb-3 shadow-sm">
            </div>
        </div>
    </div>

    <div class="card mb-4 border-success shadow-sm" id="read-processing">
        <div class="card-header bg-success text-white">
            <h2 class="h4 mb-0"><span class="badge bg-light text-success me-2 fs-6">4</span>既読処理</h2>
        </div>
        <div class="card-body">
            <div class="border-start border-success border-4 ps-3 mb-4">
                <h3 class="h5 mt-3" id="log-detail">
                    <span class="badge bg-success me-2">4-1</span>連絡帳詳細画面 
                    <small class="ms-2"><a href="{{ url('/teacher/daily-logs/1') }}" target="_blank" rel="noopener" class="btn btn-sm btn-outline-success">＜リンク＞</a></small>
                </h3>
                <div class="alert alert-success mb-3">
                    <strong class="text-success">【画面タイトル】</strong> <span class="fs-5">連絡帳の詳細（担任向け）</span>
                </div>
                <h4 class="h6 mt-2 text-success"><i class="fas fa-eye"></i> 画面の見方</h4>
                <ul class="list-group mb-3">
                    <li class="list-group-item"><strong>生徒情報</strong>: 提出者の名前・メール</li>
                    <li class="list-group-item"><strong>対象日</strong>: 連絡帳の対象日</li>
                    <li class="list-group-item"><strong>スコア</strong>: 体調・メンタルスコア</li>
                    <li class="list-group-item"><strong>本文</strong>: 生徒が記入した内容</li>
                    <li class="list-group-item"><strong>既読マーク</strong>: 👍 ✓ 既読（大きく目立つ表示）</li>
                    <li class="list-group-item"><strong>既読ボタン</strong>: 未読の場合のみ表示</li>
                </ul>
                <h4 class="h6 mt-2 text-success"><i class="fas fa-cog"></i> 操作方法</h4>
                <ol class="list-group list-group-numbered mb-3">
                    <li class="list-group-item"><strong>内容確認</strong>: 生徒の記入内容を確認</li>
                    <li class="list-group-item"><strong>既読処理</strong>: 「既読にする」ボタンをクリック</li>
                    <li class="list-group-item"><strong>戻る</strong>: 一覧画面に戻る</li>
                </ol>
                <img src="{{ asset('storage/manual/teacher_daily_log_show.png') }}" alt="提出詳細" class="img-fluid border border-3 border-success mb-3 shadow-sm">
            </div>
        </div>
    </div>

    <div class="card mb-4 border-warning shadow-sm" id="manual-logout">
        <div class="card-header bg-warning text-dark">
            <h2 class="h4 mb-0"><span class="badge bg-light text-warning me-2 fs-6">5</span>マニュアル/ログアウト</h2>
        </div>
        <div class="card-body">
            <div class="border-start border-warning border-4 ps-3 mb-4">
                <h3 class="h5 mt-3" id="nav-top">
                    <span class="badge bg-warning text-dark me-2">5-1</span>ナビゲーション（右上）
                </h3>
                <div class="alert alert-warning mb-3">
                    <strong class="text-warning">【画面タイトル】</strong> <span class="fs-5">ナビゲーション</span>
                </div>
                <h4 class="h6 mt-2 text-warning"><i class="fas fa-eye"></i> 画面の見方</h4>
                <ul class="list-group mb-3">
                    <li class="list-group-item"><strong>ログイン状態</strong>: 現在のユーザー名とロールを表示</li>
                    <li class="list-group-item"><strong>マニュアル</strong>: 本ページ（操作マニュアル）へのリンク</li>
                    <li class="list-group-item"><strong>ログアウト</strong>: サインアウト用のボタン</li>
                </ul>
                <h4 class="h6 mt-2 text-success"><i class="fas fa-cog"></i> 操作方法</h4>
                <ol class="list-group list-group-numbered mb-3">
                    <li class="list-group-item"><strong>マニュアル</strong>: クリックで本ページを表示</li>
                    <li class="list-group-item"><strong>ログアウト</strong>: クリックでサインアウト</li>
                </ol>
                <img src="{{ asset('storage/manual/teacher_nav_top.png') }}" alt="先生ナビゲーション" class="img-fluid border border-3 border-warning mb-3 shadow-sm">
            </div>
        </div>
    </div>
</div>
@endsection


