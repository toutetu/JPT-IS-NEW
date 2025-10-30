@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="mb-4 text-primary">管理者向け 操作マニュアル</h1>

    <div class="card mb-4 shadow-sm">
        <div class="card-header bg-info text-white">
            <h2 class="h5 mb-0">📑 目次</h2>
        </div>
        <div class="card-body">
            <ol>
                <li><a href="#user-management">ユーザー管理</a>
                    <ul>
                        <li><a href="#user-list">ユーザー一覧画面</a></li>
                        <li><a href="#user-create">ユーザー作成画面</a></li>
                        <li><a href="#user-delete">ユーザー削除画面</a></li>
                        <li><a href="#csv-import">CSV一括登録機能</a></li>
                    </ul>
                </li>
                <li><a href="#assignments">在籍割当・担任割当</a>
                    <ul>
                        <li><a href="#enrollment-assign">生徒在籍割当画面</a></li>
                        <li><a href="#homeroom-assign">担任割当画面</a></li>
                        <li><a href="#csv-enrollment">CSV一括クラス割り当て機能</a></li>
                    </ul>
                </li>
                <li><a href="#classroom-management">クラス管理</a>
                    <ul>
                        <li><a href="#classroom-list">クラス一覧画面</a></li>
                        <li><a href="#classroom-create">クラス作成画面</a></li>
                        <li><a href="#classroom-delete">クラス削除画面</a></li>
                    </ul>
                </li>
                <li><a href="#navigation">ナビゲーション</a>
                    <ul>
                        <li><a href="#nav-top">ナビゲーション（右上）</a></li>
                    </ul>
                </li>
            </ol>
        </div>
    </div>

    <!-- セクション1: ユーザー管理 -->
    <div class="card mb-4 border-primary shadow-sm" id="user-management">
        <div class="card-header bg-primary text-white">
            <h2 class="h4 mb-0"><span class="badge bg-light text-primary me-2 fs-6">1</span>ユーザー管理</h2>
        </div>
        <div class="card-body">
            <p class="lead mb-4">「ユーザー管理」からユーザー一覧・作成・削除・CSVインポートを行います。</p>
            
            <div class="border-start border-primary border-4 ps-3 mb-4">
                <h3 class="h5 mt-3" id="user-list">
                    <span class="badge bg-primary me-2">1-1</span>ユーザー一覧画面 
                    <small class="ms-2"><a href="{{ url('/admin/users') }}" target="_blank" rel="noopener" class="btn btn-sm btn-outline-primary">＜リンク＞</a></small>
                </h3>
                <div class="alert alert-primary mb-3">
                    <strong class="text-primary">【画面タイトル】</strong> <span class="fs-5">ユーザー管理</span>
                </div>
                <h4 class="h6 mt-2 text-primary"><i class="fas fa-eye"></i> 画面の見方</h4>
                <ul class="list-group mb-3">
                    <li class="list-group-item"><strong>ユーザー一覧</strong>: 全ユーザー（管理者・担任・生徒）の一覧</li>
                    <li class="list-group-item"><strong>割り当てクラス</strong>: 生徒は在籍クラス、担任は担当クラスを表示</li>
                    <li class="list-group-item"><strong>割り当て変更</strong>: 生徒・担任の割り当て変更ボタン</li>
                    <li class="list-group-item"><strong>検索機能</strong>: 名前・メール・ロールでの検索が可能</li>
                </ul>
                <h4 class="h6 mt-2 text-success"><i class="fas fa-cog"></i> 操作方法</h4>
                <ol class="list-group list-group-numbered mb-3">
                    <li class="list-group-item"><strong>新規ユーザー作成</strong>: 「新規ユーザー作成」ボタン</li>
                    <li class="list-group-item"><strong>CSV一括登録</strong>: 「新規ユーザー作成（CSV一括登録）」ボタン</li>
                    <li class="list-group-item"><strong>在籍変更</strong>: 生徒の「在籍を変更」ボタン</li>
                    <li class="list-group-item"><strong>担任割当変更</strong>: 担任の「担任割当を変更」ボタン</li>
                    <li class="list-group-item"><strong>CSV一括割当</strong>: 「在籍を変更（CSV一括登録）」ボタン</li>
                </ol>
                <img src="{{ asset('storage/manual/admin_users_index.png') }}" alt="ユーザー一覧" class="img-fluid border border-3 border-primary mb-3 shadow-sm">
            </div>
            
            <div class="border-start border-primary border-4 ps-3 mb-4">
                <h3 class="h5 mt-4" id="user-create">
                    <span class="badge bg-primary me-2">1-2</span>ユーザー作成画面 
                    <small class="ms-2"><a href="{{ url('/admin/users/create') }}" target="_blank" rel="noopener" class="btn btn-sm btn-outline-primary">＜リンク＞</a></small>
                </h3>
                <img src="{{ asset('storage/manual/admin_users_create.png') }}" alt="ユーザー作成" class="img-fluid border border-3 border-primary mb-3 shadow-sm">
            </div>
            
            <div class="border-start border-primary border-4 ps-3 mb-4">
                <h3 class="h5 mt-4" id="user-delete">
                    <span class="badge bg-primary me-2">1-3</span>ユーザー削除画面
                </h3>
                <img src="{{ asset('storage/manual/admin_users_delete.png') }}" alt="ユーザー削除" class="img-fluid border border-3 border-primary mb-3 shadow-sm">
            </div>
            
            <div class="border-start border-primary border-4 ps-3 mb-4">
                <h3 class="h5 mt-4" id="csv-import">
                    <span class="badge bg-primary me-2">1-4</span>CSV一括登録機能 
                    <small class="ms-2"><a href="{{ url('/admin/users/import') }}" target="_blank" rel="noopener" class="btn btn-sm btn-outline-primary">＜リンク＞</a></small>
                </h3>
                <div class="alert alert-primary mb-3">
                    <strong class="text-primary">【画面タイトル】</strong> <span class="fs-5">新規ユーザー作成（CSV一括登録）</span>
                </div>
                <h4 class="h6 mt-2 text-info"><i class="fas fa-info-circle"></i> 機能概要</h4>
                <p class="mb-3">複数のユーザーを一度に登録できる機能です。CSVファイルからユーザー情報を読み込んで一括登録します。</p>
                <h4 class="h6 mt-2 text-success"><i class="fas fa-cog"></i> 操作方法</h4>
                <ol class="list-group list-group-numbered mb-3">
                    <li class="list-group-item">「CSVファイルを選択」をクリックし、所定形式のCSVを選択</li>
                    <li class="list-group-item">「登録する」ボタンをクリック</li>
                    <li class="list-group-item">結果（成功件数・エラー件数）を確認</li>
                </ol>
                <img src="{{ asset('storage/manual/admin_users_import.png') }}" alt="CSVインポート" class="img-fluid border border-3 border-primary mb-3 shadow-sm">
            </div>
        </div>
    </div>

    <!-- セクション2: 在籍割当・担任割当 -->
    <div class="card mb-4 border-success shadow-sm" id="assignments">
        <div class="card-header bg-success text-white">
            <h2 class="h4 mb-0"><span class="badge bg-light text-success me-2 fs-6">2</span>在籍割当・担任割当</h2>
        </div>
        <div class="card-body">
            <p class="lead mb-4">管理メニューから在籍割当・担任割当を実行します。</p>
            
            <div class="border-start border-success border-4 ps-3 mb-4">
                <h3 class="h5 mt-3" id="enrollment-assign">
                    <span class="badge bg-success me-2">2-1</span>生徒在籍割当画面 
                    <small class="ms-2"><a href="{{ url('/admin/assign/enrollment') }}" target="_blank" rel="noopener" class="btn btn-sm btn-outline-success">＜リンク＞</a></small>
                </h3>
                <div class="alert alert-success mb-3">
                    <strong class="text-success">【画面タイトル】</strong> <span class="fs-5">在籍割当</span>
                </div>
                <h4 class="h6 mt-2 text-success"><i class="fas fa-eye"></i> 画面の見方</h4>
                <ul class="list-group mb-3">
                    <li class="list-group-item"><strong>生徒選択</strong>: 変更対象の生徒を選択</li>
                    <li class="list-group-item"><strong>現在在籍</strong>: 現在の在籍クラスが自動表示</li>
                    <li class="list-group-item"><strong>クラス選択</strong>: 新しい在籍クラスを選択</li>
                </ul>
                <h4 class="h6 mt-2 text-success"><i class="fas fa-cog"></i> 操作方法</h4>
                <ol class="list-group list-group-numbered mb-3">
                    <li class="list-group-item"><strong>生徒選択</strong>: ドロップダウンで生徒を選択</li>
                    <li class="list-group-item"><strong>クラス確認</strong>: 現在の在籍クラスを確認</li>
                    <li class="list-group-item"><strong>新クラス選択</strong>: 新しい在籍クラスを選択</li>
                    <li class="list-group-item"><strong>保存</strong>: 「割当を保存」ボタンで変更</li>
                </ol>
                <img src="{{ asset('storage/manual/admin_assign_enrollment.png') }}" alt="在籍割当" class="img-fluid border border-3 border-success mb-3 shadow-sm">
            </div>
            
            <div class="border-start border-success border-4 ps-3 mb-4">
                <h3 class="h5 mt-4" id="homeroom-assign">
                    <span class="badge bg-success me-2">2-2</span>担任割当画面 
                    <small class="ms-2"><a href="{{ url('/admin/assign/homeroom') }}" target="_blank" rel="noopener" class="btn btn-sm btn-outline-success">＜リンク＞</a></small>
                </h3>
                <div class="alert alert-success mb-3">
                    <strong class="text-success">【画面タイトル】</strong> <span class="fs-5">担任割当</span>
                </div>
                <h4 class="h6 mt-2 text-success"><i class="fas fa-eye"></i> 画面の見方</h4>
                <ul class="list-group mb-3">
                    <li class="list-group-item"><strong>担任選択</strong>: 変更対象の担任を選択</li>
                    <li class="list-group-item"><strong>現在担当</strong>: 現在の担当クラスが自動表示</li>
                    <li class="list-group-item"><strong>クラス選択</strong>: 新しい担当クラスを選択</li>
                </ul>
                <h4 class="h6 mt-2 text-success"><i class="fas fa-cog"></i> 操作方法</h4>
                <ol class="list-group list-group-numbered mb-3">
                    <li class="list-group-item"><strong>担任選択</strong>: ドロップダウンで担任を選択</li>
                    <li class="list-group-item"><strong>担当確認</strong>: 現在の担当クラスを確認</li>
                    <li class="list-group-item"><strong>新クラス選択</strong>: 新しい担当クラスを選択</li>
                    <li class="list-group-item"><strong>保存</strong>: 「割当を保存」ボタンで変更</li>
                </ol>
                <img src="{{ asset('storage/manual/admin_assign_homeroom.png') }}" alt="担任割当" class="img-fluid border border-3 border-success mb-3 shadow-sm">
            </div>
            
            <div class="border-start border-success border-4 ps-3 mb-4">
                <h3 class="h5 mt-4" id="csv-enrollment">
                    <span class="badge bg-success me-2">2-3</span>CSV一括クラス割り当て機能 
                    <small class="ms-2"><a href="{{ url('/admin/assign/enrollment/import') }}" target="_blank" rel="noopener" class="btn btn-sm btn-outline-success">＜リンク＞</a></small>
                </h3>
                <div class="alert alert-success mb-3">
                    <strong class="text-success">【画面タイトル】</strong> <span class="fs-5">在籍を変更（CSV一括登録）</span>
                </div>
                <h4 class="h6 mt-2 text-info"><i class="fas fa-info-circle"></i> 機能概要</h4>
                <p class="mb-3">既存の生徒のクラス割り当てを一括変更できる機能です。CSVファイルから生徒のメールアドレスとクラス名を読み込んで、クラス割り当てを一括変更します。</p>
                <h4 class="h6 mt-2 text-success"><i class="fas fa-cog"></i> 操作方法</h4>
                <ol class="list-group list-group-numbered mb-3">
                    <li class="list-group-item">画面の説明に従い、所定形式のCSVを選択</li>
                    <li class="list-group-item">対象の学年・クラス設定が必要な場合は指定</li>
                    <li class="list-group-item">「実行する」ボタンをクリック</li>
                    <li class="list-group-item">結果（成功件数・エラー詳細）を確認</li>
                </ol>
                <img src="{{ asset('storage/manual/admin_enrollment_csv.png') }}" alt="在籍CSV一括登録" class="img-fluid border border-3 border-success mb-3 shadow-sm">
            </div>
        </div>
    </div>

    <!-- セクション3: クラス管理 -->
    <div class="card mb-4 border-info shadow-sm" id="classroom-management">
        <div class="card-header bg-info text-white">
            <h2 class="h4 mb-0"><span class="badge bg-light text-info me-2 fs-6">3</span>クラス管理</h2>
        </div>
        <div class="card-body">
            <p class="lead mb-4">「クラス管理」からクラスの追加・削除・担任変更を行います。</p>
            
            <div class="border-start border-info border-4 ps-3 mb-4">
                <h3 class="h5 mt-3" id="classroom-list">
                    <span class="badge bg-info me-2">3-1</span>クラス一覧画面 
                    <small class="ms-2"><a href="{{ url('/admin/classrooms') }}" target="_blank" rel="noopener" class="btn btn-sm btn-outline-info">＜リンク＞</a></small>
                </h3>
                <div class="alert alert-info mb-3">
                    <strong class="text-info">【画面タイトル】</strong> <span class="fs-5">クラス管理</span>
                </div>
                <h4 class="h6 mt-2 text-info"><i class="fas fa-eye"></i> 画面の見方</h4>
                <ul class="list-group mb-3">
                    <li class="list-group-item"><strong>クラス一覧</strong>: 学年・クラス名・在籍生徒数・担任の一覧</li>
                    <li class="list-group-item"><strong>在籍生徒数</strong>: 現在在籍中の生徒数を表示</li>
                    <li class="list-group-item"><strong>担任</strong>: 現在担当している教師名を表示（未設定の場合は「未設定」）</li>
                    <li class="list-group-item"><strong>担任変更</strong>: 担任の変更・割当ボタン</li>
                    <li class="list-group-item"><strong>削除</strong>: クラスの削除ボタン</li>
                </ul>
                <h4 class="h6 mt-2 text-success"><i class="fas fa-cog"></i> 操作方法</h4>
                <ol class="list-group list-group-numbered mb-3">
                    <li class="list-group-item"><strong>新規クラス作成</strong>: 「新規クラス作成」ボタンをクリック</li>
                    <li class="list-group-item"><strong>担任を変更</strong>: 
                        <ul class="mt-2">
                            <li>担任が設定済み: 「変更」ボタン → 担任割当画面へ</li>
                            <li>担任が未設定: 「割当」ボタン → 担任割当画面へ</li>
                        </ul>
                    </li>
                    <li class="list-group-item"><strong>クラス削除</strong>: 「削除」ボタン → 削除確認画面へ</li>
                </ol>
                <h4 class="h6 mt-2 text-warning"><i class="fas fa-exclamation-triangle"></i> 注意事項</h4>
                <ul class="list-group mb-3">
                    <li class="list-group-item">在籍中の生徒がいるクラスは削除できません</li>
                    <li class="list-group-item">担当中の教師がいるクラスは削除できません</li>
                </ul>
                <img src="{{ asset('storage/manual/admin_classrooms_index.png') }}" alt="クラス一覧" class="img-fluid border border-3 border-info mb-3 shadow-sm">
            </div>
            
            <div class="border-start border-info border-4 ps-3 mb-4">
                <h3 class="h5 mt-4" id="classroom-create">
                    <span class="badge bg-info me-2">3-2</span>クラス作成画面 
                    <small class="ms-2"><a href="{{ url('/admin/classrooms/create') }}" target="_blank" rel="noopener" class="btn btn-sm btn-outline-info">＜リンク＞</a></small>
                </h3>
                <div class="alert alert-info mb-3">
                    <strong class="text-info">【画面タイトル】</strong> <span class="fs-5">新規クラス作成</span>
                </div>
                <h4 class="h6 mt-2 text-info"><i class="fas fa-eye"></i> 画面の見方</h4>
                <ul class="list-group mb-3">
                    <li class="list-group-item"><strong>学年選択</strong>: クラスが属する学年を選択</li>
                    <li class="list-group-item"><strong>クラス名入力</strong>: クラス名を入力（例: 1年A組、2年B組）</li>
                </ul>
                <h4 class="h6 mt-2 text-success"><i class="fas fa-cog"></i> 操作方法</h4>
                <ol class="list-group list-group-numbered mb-3">
                    <li class="list-group-item">学年をドロップダウンから選択</li>
                    <li class="list-group-item">クラス名を入力（学年情報を含めることを推奨）</li>
                    <li class="list-group-item">「クラスを作成」ボタンをクリック</li>
                </ol>
                <h4 class="h6 mt-2 text-warning"><i class="fas fa-exclamation-triangle"></i> 注意事項</h4>
                <ul class="list-group mb-3">
                    <li class="list-group-item">同じ学年内で同じクラス名は登録できません</li>
                </ul>
                <img src="{{ asset('storage/manual/admin_classrooms_create.png') }}" alt="クラス作成" class="img-fluid border border-3 border-info mb-3 shadow-sm">
            </div>
            
            <div class="border-start border-info border-4 ps-3 mb-4">
                <h3 class="h5 mt-4" id="classroom-delete">
                    <span class="badge bg-info me-2">3-3</span>クラス削除画面
                </h3>
                <div class="alert alert-info mb-3">
                    <strong class="text-info">【画面タイトル】</strong> <span class="fs-5">クラス削除確認</span>
                </div>
                <h4 class="h6 mt-2 text-info"><i class="fas fa-eye"></i> 画面の見方</h4>
                <ul class="list-group mb-3">
                    <li class="list-group-item"><strong>クラス情報</strong>: 削除対象のクラス詳細情報</li>
                    <li class="list-group-item"><strong>在籍生徒数</strong>: 現在在籍中の生徒数</li>
                    <li class="list-group-item"><strong>担任</strong>: 現在の担任名</li>
                    <li class="list-group-item"><strong>警告メッセージ</strong>: 削除できない理由を表示</li>
                </ul>
                <h4 class="h6 mt-2 text-success"><i class="fas fa-cog"></i> 操作方法</h4>
                <ol class="list-group list-group-numbered mb-3">
                    <li class="list-group-item">クラス情報を確認</li>
                    <li class="list-group-item">警告メッセージがないことを確認</li>
                    <li class="list-group-item">「削除を実行」ボタンをクリック</li>
                    <li class="list-group-item">確認ダイアログで「OK」を選択</li>
                </ol>
                <h4 class="h6 mt-2 text-danger"><i class="fas fa-skull-crossbones"></i> 削除の影響</h4>
                <ul class="list-group mb-3">
                    <li class="list-group-item text-danger">過去の在籍記録が削除されます</li>
                    <li class="list-group-item text-danger">過去の担任記録が削除されます</li>
                    <li class="list-group-item text-danger">この操作は取り消すことができません</li>
                </ul>
                <img src="{{ asset('storage/manual/admin_classrooms_delete.png') }}" alt="クラス削除" class="img-fluid border border-3 border-info mb-3 shadow-sm">
            </div>
        </div>
    </div>

    <!-- セクション4: ナビゲーション -->
    <div class="card mb-4 border-warning shadow-sm" id="navigation">
        <div class="card-header bg-warning text-dark">
            <h2 class="h4 mb-0"><span class="badge bg-light text-warning me-2 fs-6">4</span>ナビゲーション</h2>
        </div>
        <div class="card-body">
            <div class="border-start border-warning border-4 ps-3 mb-4">
                <h3 class="h5 mt-3" id="nav-top">
                    <span class="badge bg-warning text-dark me-2">4-1</span>ナビゲーション（右上）
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
                    <li class="list-group-item"><strong>マニュアル</strong>: クリックでロール別マニュアルを表示</li>
                    <li class="list-group-item"><strong>ログアウト</strong>: クリックで安全にサインアウト</li>
                </ol>
                <img src="{{ asset('storage/manual/admin_nav_top.png') }}" alt="管理者ナビゲーション" class="img-fluid border border-3 border-warning mb-3 shadow-sm">
            </div>
        </div>
    </div>
</div>
@endsection
