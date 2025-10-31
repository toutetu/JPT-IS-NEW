@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="mb-4 text-success">生徒向け 操作マニュアル</h1>

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
                <li><a href="#daily-logs">マイ連絡帳の確認・提出</a>
                    <ul>
                        <li><a href="#daily-logs-list">マイ連絡帳一覧画面</a></li>
                        <li><a href="#daily-logs-create">新規提出画面</a></li>
                        <li><a href="#daily-logs-detail">詳細・編集画面</a></li>
                    </ul>
                </li>
                <li><a href="#calendar">提出カレンダー</a>
                    <ul>
                        <li><a href="#calendar-screen">提出カレンダー画面</a></li>
                    </ul>
                </li>
                <li><a href="#manual-location">マニュアルの場所</a>
                    <ul>
                        <li><a href="#nav-top">ナビゲーション（右上）</a></li>
                    </ul>
                </li>
                <li><a href="#logout">ログアウト</a>
                    <ul>
                        <li><a href="#logout-method">ログアウト方法</a></li>
                    </ul>
                </li>
            </ol>
        </div>
    </div>

    <div class="alert alert-info">
        画像はダミーです。指定いただくスクリーンショットをご提供後に差し替えます。
    </div>

    <div class="alert alert-warning mb-4">
        <i class="fas fa-exclamation-triangle"></i> 
        <strong>重要なお知らせ</strong>: パスワードリセット機能（メール送信）は現在未実装です。パスワードを忘れた場合は、システム管理者にお問い合わせください。
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

    <div class="card mb-4 border-success shadow-sm" id="daily-logs">
        <div class="card-header bg-success text-white">
            <h2 class="h4 mb-0"><span class="badge bg-light text-success me-2 fs-6">2</span>マイ連絡帳の確認・提出</h2>
        </div>
        <div class="card-body">
            <div class="border-start border-success border-4 ps-3 mb-4">
                <h3 class="h5 mt-3" id="daily-logs-list">
                    <span class="badge bg-success me-2">2-1</span>マイ連絡帳一覧画面 
                    <small class="ms-2"><a href="{{ url('/student/daily-logs') }}" target="_blank" rel="noopener" class="btn btn-sm btn-outline-success">＜リンク＞</a></small>
                </h3>
                <div class="alert alert-success mb-3">
                    <strong class="text-success">【画面タイトル】</strong> <span class="fs-5">マイ連絡帳</span>
                </div>
                <h4 class="h6 mt-2 text-success"><i class="fas fa-eye"></i> 画面の見方</h4>
                <ul class="list-group mb-3">
                    <li class="list-group-item"><strong>所属情報</strong>: 画面上部のカードに <strong>所属クラス</strong> と <strong>担任</strong> を表示</li>
                    <li class="list-group-item"><strong>対象日</strong>: 連絡帳の対象日（曜日付きで表示）</li>
                    <li class="list-group-item"><strong>体調</strong>: 体調スコア（1-5）</li>
                    <li class="list-group-item"><strong>メンタル</strong>: メンタルスコア（1-5）</li>
                    <li class="list-group-item"><strong>内容(冒頭)</strong>: 本文の先頭を表示（<strong>スマホ:10文字</strong> / <strong>PC:20文字</strong>、超える場合は末尾に「…」）</li>
                    <li class="list-group-item"><strong>既読</strong>: 👍既読 または 未読</li>
                    <li class="list-group-item"><strong>操作</strong>: 詳細ボタン</li>
                    <li class="list-group-item"><strong>ページネーション</strong>: 改善されたページネーション（「＞」「＜」文字が表示されない）</li>
                </ul>
                <h4 class="h6 mt-2 text-success"><i class="fas fa-cog"></i> 操作方法</h4>
                <ol class="list-group list-group-numbered mb-3">
                    <li class="list-group-item"><strong>新規提出</strong>: 「新規提出」ボタンをクリック</li>
                    <li class="list-group-item"><strong>詳細確認</strong>: 「詳細」ボタンで内容を確認</li>
                    <li class="list-group-item"><strong>編集</strong>: 未読の場合は詳細画面で編集可能</li>
                </ol>
                <div class="alert alert-secondary">
                    <strong>期間検索</strong>: 一覧の <strong>ページネーション直下</strong>に期間検索フォームが<strong>常時表示</strong>されます。<br>
                    「開始日」「終了日」を指定して「検索」を押すと対象期間のみ表示されます。<br>
                    「リセット」で検索条件を解除して全件表示に戻ります。
                </div>
                <img src="{{ asset('storage/manual/student_daily_logs_index.png') }}" alt="マイ連絡帳一覧" class="img-fluid border border-3 border-success mb-3 shadow-sm">
            </div>
            
            <div class="border-start border-success border-4 ps-3 mb-4">
                <h3 class="h5 mt-4" id="daily-logs-create">
                    <span class="badge bg-success me-2">2-2</span>新規提出画面 
                    <small class="ms-2"><a href="{{ url('/student/daily-logs/create') }}" target="_blank" rel="noopener" class="btn btn-sm btn-outline-success">＜リンク＞</a></small>
                </h3>
                <div class="alert alert-success mb-3">
                    <strong class="text-success">【画面タイトル】</strong> <span class="fs-5">連絡帳の新規提出</span>
                </div>
                <h4 class="h6 mt-2 text-success"><i class="fas fa-edit"></i> 入力項目</h4>
                <ul class="list-group mb-3">
                    <li class="list-group-item"><strong>対象日</strong>: 自動設定（平日は前日、月曜は前金曜）</li>
                    <li class="list-group-item"><strong>体調スコア</strong>: 1-5の数値（ラジオボタン形式、初期値3）</li>
                    <li class="list-group-item"><strong>メンタルスコア</strong>: 1-5の数値（ラジオボタン形式、初期値3）</li>
                    <li class="list-group-item"><strong>本文</strong>: 自由記述（必須）</li>
                </ul>
                <div class="alert alert-secondary">画面上部に <strong>所属クラス</strong> と <strong>担任</strong> が表示されます。</div>
                <h4 class="h6 mt-2 text-success"><i class="fas fa-cog"></i> 操作方法</h4>
                <ol class="list-group list-group-numbered mb-3">
                    <li class="list-group-item">対象日を変更したい場合は日付を選択</li>
                    <li class="list-group-item">体調・メンタルスコアをラジオボタンで選択
                        <ul>
                            <li>体調: 青色のボタン、「悪い」← 1 2 3 4 5 →「良い」</li>
                            <li>メンタル: 緑色のボタン、「悪い」← 1 2 3 4 5 →「良い」</li>
                        </ul>
                    </li>
                    <li class="list-group-item">本文を入力</li>
                    <li class="list-group-item">「提出」ボタンをクリック</li>
                </ol>
                <img src="{{ asset('storage/manual/student_daily_log_create.png') }}" alt="連絡帳作成" class="img-fluid border border-3 border-success mb-3 shadow-sm">
            </div>
            
            <div class="border-start border-success border-4 ps-3 mb-4">
                <h3 class="h5 mt-4" id="daily-logs-detail">
                    <span class="badge bg-success me-2">2-3</span>詳細・編集画面 
                    <small class="ms-2"><a href="{{ url('/student/daily-logs/1') }}" target="_blank" rel="noopener" class="btn btn-sm btn-outline-success">＜リンク＞</a></small>
                </h3>
                <div class="alert alert-success mb-3">
                    <strong class="text-success">【画面タイトル】</strong> <span class="fs-5">連絡帳の詳細</span>
                </div>
                <h4 class="h6 mt-2 text-success"><i class="fas fa-eye"></i> 画面の見方</h4>
                <ul class="list-group mb-3">
                    <li class="list-group-item"><strong>既読状況</strong>: 👍既読 または 未読</li>
                    <li class="list-group-item"><strong>提出内容</strong>: 体調・メンタルスコア、本文</li>
                    <li class="list-group-item"><strong>編集ボタン</strong>: 未読時のみ表示</li>
                </ul>
                <div class="alert alert-secondary">画面上部に <strong>所属クラス</strong> と <strong>担任</strong> が表示されます。</div>
                <h4 class="h6 mt-2 text-success"><i class="fas fa-cog"></i> 操作方法</h4>
                <ol class="list-group list-group-numbered mb-3">
                    <li class="list-group-item"><strong>内容確認</strong>: 提出内容を確認</li>
                    <li class="list-group-item"><strong>編集</strong>: 未読の場合は「編集」ボタンで修正可能</li>
                    <li class="list-group-item"><strong>保存</strong>: 編集後は「保存」ボタンで更新</li>
                </ol>
            </div>
        </div>
    </div>

    <div class="card mb-4 border-warning shadow-sm" id="calendar">
        <div class="card-header bg-warning text-dark">
            <h2 class="h4 mb-0"><span class="badge bg-light text-warning me-2 fs-6">3</span>提出カレンダー</h2>
        </div>
        <div class="card-body">
            <div class="border-start border-warning border-4 ps-3 mb-4">
                <h3 class="h5 mt-3" id="calendar-screen">
                    <span class="badge bg-warning text-dark me-2">3-1</span>提出カレンダー画面 
                    <small class="ms-2"><a href="{{ url('/student/calendar') }}" target="_blank" rel="noopener" class="btn btn-sm btn-outline-warning">＜リンク＞</a></small>
                </h3>
                <div class="alert alert-warning mb-3">
                    <strong class="text-warning">【画面タイトル】</strong> <span class="fs-5">提出カレンダー</span>
                </div>
                <h4 class="h6 mt-2 text-warning"><i class="fas fa-eye"></i> 画面の見方</h4>
                <ul class="list-group mb-3">
                    <li class="list-group-item"><strong>月間カレンダー</strong>: 現在の月のカレンダー表示</li>
                    <li class="list-group-item"><strong>提出済みマーク</strong>: 赤い○で囲まれた「済」文字</li>
                    <li class="list-group-item"><strong>前月・次月</strong>: ナビゲーションボタン</li>
                    <li class="list-group-item"><strong>凡例</strong>: 提出済み・前登校日の説明</li>
                </ul>
                <h4 class="h6 mt-2 text-success"><i class="fas fa-cog"></i> 操作方法</h4>
                <ol class="list-group list-group-numbered mb-3">
                    <li class="list-group-item"><strong>カレンダー表示</strong>: ナビゲーションの「提出カレンダー」をクリック</li>
                    <li class="list-group-item"><strong>月の移動</strong>: 前月・次月ボタンで移動</li>
                    <li class="list-group-item"><strong>提出状況確認</strong>: 提出済みの日は「済」マークで表示</li>
                </ol>
                <img src="{{ asset('storage/manual/student_calendar.png') }}" alt="提出カレンダー" class="img-fluid border border-3 border-warning mb-3 shadow-sm">
            </div>
        </div>
    </div>

    <div class="card mb-4 border-secondary shadow-sm" id="manual-location">
        <div class="card-header bg-secondary text-white">
            <h2 class="h4 mb-0"><span class="badge bg-light text-secondary me-2 fs-6">4</span>マニュアルの場所</h2>
        </div>
        <div class="card-body">
            <div class="border-start border-secondary border-4 ps-3 mb-4">
                <h3 class="h5 mt-3" id="nav-top">
                    <span class="badge bg-secondary me-2">4-1</span>ナビゲーション（右上） 
                    <small class="ms-2"><a href="{{ url('/manual') }}" target="_blank" rel="noopener" class="btn btn-sm btn-outline-secondary">＜リンク＞</a></small>
                </h3>
                <div class="alert alert-secondary mb-3">
                    <strong class="text-secondary">【画面タイトル】</strong> <span class="fs-5">ナビゲーション</span>
                </div>
                <h4 class="h6 mt-2 text-secondary"><i class="fas fa-eye"></i> 画面の見方</h4>
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
                <img src="{{ asset('storage/manual/student_nav_top.png') }}" alt="生徒ナビゲーション" class="img-fluid border border-3 border-secondary mb-3 shadow-sm">
            </div>
        </div>
    </div>

    <div class="card mb-4 border-danger shadow-sm" id="logout">
        <div class="card-header bg-danger text-white">
            <h2 class="h4 mb-0"><span class="badge bg-light text-danger me-2 fs-6">5</span>ログアウト</h2>
        </div>
        <div class="card-body">
            <div class="border-start border-danger border-4 ps-3 mb-4">
                <h3 class="h5 mt-3" id="logout-method">
                    <span class="badge bg-danger me-2">5-1</span>ログアウト方法
                </h3>
                <h4 class="h6 mt-2 text-success"><i class="fas fa-cog"></i> 操作方法</h4>
                <ol class="list-group list-group-numbered mb-3">
                    <li class="list-group-item">右上の「ログアウト」をクリック</li>
                    <li class="list-group-item">安全にサインアウトされます</li>
                </ol>
            </div>
        </div>
    </div>
</div>
@endsection


