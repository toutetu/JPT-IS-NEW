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

    <div class="alert alert-warning mb-4">
        <i class="fas fa-exclamation-triangle"></i> 
        <strong>重要なお知らせ</strong>: パスワードリセット機能（メール送信）は現在未実装です。ユーザーがパスワードを忘れた場合は、管理者が新しいパスワードを設定してください。
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
                <div class="card mb-3">
                    <div class="card-body">
                        <h5 class="h6 text-primary">画面上部</h5>
                        <ul class="mb-3">
                            <li><strong>「新規クラス作成」ボタン</strong>（緑色）: 新しいクラスを作成する画面に移動します</li>
                        </ul>
                        
                        <h5 class="h6 text-primary">クラス一覧テーブル</h5>
                        <table class="table table-sm table-bordered">
                            <thead class="table-light">
                                <tr>
                                    <th>列名</th>
                                    <th>説明</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td><strong>ID</strong></td>
                                    <td>クラスの一意な識別番号</td>
                                </tr>
                                <tr>
                                    <td><strong>学年</strong></td>
                                    <td>クラスが属する学年（例: 1年、2年、3年）</td>
                                </tr>
                                <tr>
                                    <td><strong>クラス名</strong></td>
                                    <td>クラスの名称（例: 1年A組、2年B組）</td>
                                </tr>
                                <tr>
                                    <td><strong>在籍生徒数</strong></td>
                                    <td>現在そのクラスに在籍している生徒の人数</td>
                                </tr>
                                <tr>
                                    <td><strong>担任</strong></td>
                                    <td>現在そのクラスを担当している教師の名前<br>担任が未設定の場合は「未設定」と表示</td>
                                </tr>
                                <tr>
                                    <td><strong>担任変更</strong></td>
                                    <td>
                                        担任の変更・割当を行うボタン<br>
                                        • 担任設定済み: 「変更」ボタン（青色）<br>
                                        • 担任未設定: 「割当」ボタン（グレー）
                                    </td>
                                </tr>
                                <tr>
                                    <td><strong>操作</strong></td>
                                    <td>「削除」ボタン（赤色）: クラスを削除する</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                
                <h4 class="h6 mt-2 text-success"><i class="fas fa-cog"></i> 操作方法</h4>
                <div class="card mb-3">
                    <div class="card-body">
                        <h5 class="h6 text-success">① 新しいクラスを作成する</h5>
                        <ol class="mb-3">
                            <li>画面右上の「新規クラス作成」ボタンをクリック</li>
                            <li>クラス作成画面が表示されます</li>
                        </ol>
                        
                        <h5 class="h6 text-success">② 担任を変更・割当する</h5>
                        <ol class="mb-3">
                            <li>変更したいクラスの「担任変更」列を確認</li>
                            <li>「変更」または「割当」ボタンをクリック</li>
                            <li>担任割当画面が表示されます（該当するクラスの担任が選択された状態）</li>
                            <li>新しい担任を選択して「割り当てる」をクリック</li>
                        </ol>
                        
                        <h5 class="h6 text-success">③ クラスを削除する</h5>
                        <ol class="mb-3">
                            <li>削除したいクラスの「操作」列の「削除」ボタンをクリック</li>
                            <li>削除確認画面が表示されます</li>
                            <li>詳細は「3-3 クラス削除画面」を参照</li>
                        </ol>
                    </div>
                </div>
                
                <h4 class="h6 mt-2 text-warning"><i class="fas fa-exclamation-triangle"></i> 注意事項</h4>
                <div class="alert alert-warning">
                    <ul class="mb-0">
                        <li><strong>在籍中の生徒がいるクラスは削除できません</strong><br>
                            先に生徒の在籍を別のクラスに変更してください</li>
                        <li><strong>担当中の教師がいるクラスは削除できません</strong><br>
                            先に担任割当を解除または変更してください</li>
                        <li>クラス削除は慎重に行ってください（取り消しできません）</li>
                    </ul>
                </div>
                
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
                <div class="card mb-3">
                    <div class="card-body">
                        <h5 class="h6 text-primary">入力フォーム</h5>
                        <table class="table table-sm table-bordered mb-3">
                            <thead class="table-light">
                                <tr>
                                    <th>項目</th>
                                    <th>説明</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td><strong>学年</strong> <span class="text-danger">*</span></td>
                                    <td>
                                        クラスが属する学年を選択（必須項目）<br>
                                        ドロップダウンから選択<br>
                                        例: 1年、2年、3年
                                    </td>
                                </tr>
                                <tr>
                                    <td><strong>クラス名</strong> <span class="text-danger">*</span></td>
                                    <td>
                                        クラスの名称を入力（必須項目）<br>
                                        わかりやすい名前を推奨<br>
                                        例: 1年A組、1年B組、2年1組
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        
                        <h5 class="h6 text-primary">ボタン</h5>
                        <ul class="mb-0">
                            <li><strong>「キャンセル」ボタン</strong>（グレー）: クラス一覧画面に戻る</li>
                            <li><strong>「クラスを作成」ボタン</strong>（青色）: 入力内容を保存してクラスを作成</li>
                        </ul>
                    </div>
                </div>
                
                <h4 class="h6 mt-2 text-success"><i class="fas fa-cog"></i> 操作方法</h4>
                <div class="card mb-3">
                    <div class="card-body">
                        <h5 class="h6 text-success">クラス作成の手順</h5>
                        <ol class="mb-3">
                            <li><strong>学年を選択</strong>
                                <ul>
                                    <li>「学年」ドロップダウンをクリック</li>
                                    <li>作成したいクラスの学年を選択</li>
                                </ul>
                            </li>
                            <li><strong>クラス名を入力</strong>
                                <ul>
                                    <li>「クラス名」欄にクラス名を入力</li>
                                    <li>学年情報を含めると管理しやすい（例: 1年A組）</li>
                                    <li>255文字以内で入力</li>
                                </ul>
                            </li>
                            <li><strong>作成を実行</strong>
                                <ul>
                                    <li>「クラスを作成」ボタンをクリック</li>
                                    <li>成功すると、クラス一覧画面に戻り「クラスを作成しました」と表示</li>
                                </ul>
                            </li>
                        </ol>
                        
                        <h5 class="h6 text-info">入力例</h5>
                        <table class="table table-sm table-bordered">
                            <thead class="table-light">
                                <tr>
                                    <th>学年</th>
                                    <th>クラス名の例</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>1年</td>
                                    <td>1年A組、1年B組、1年1組、1年2組</td>
                                </tr>
                                <tr>
                                    <td>2年</td>
                                    <td>2年A組、2年B組、2年1組、2年2組</td>
                                </tr>
                                <tr>
                                    <td>3年</td>
                                    <td>3年A組、3年B組、3年1組、3年2組</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                
                <h4 class="h6 mt-2 text-warning"><i class="fas fa-exclamation-triangle"></i> 注意事項</h4>
                <div class="alert alert-warning">
                    <ul class="mb-0">
                        <li><strong>同じ学年内で同じクラス名は登録できません</strong><br>
                            例: 「1年」に「1年A組」が既に存在する場合、同じ名前は登録不可</li>
                        <li><strong>必須項目（<span class="text-danger">*</span>）は必ず入力してください</strong><br>
                            学年とクラス名の両方が必須です</li>
                        <li><strong>クラス名は後から変更できません</strong><br>
                            作成前によく確認してください</li>
                    </ul>
                </div>
                
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
                <div class="card mb-3">
                    <div class="card-body">
                        <h5 class="h6 text-primary">削除するクラス情報</h5>
                        <p class="mb-2">削除対象のクラス詳細情報が表形式で表示されます：</p>
                        <table class="table table-sm table-bordered mb-3">
                            <thead class="table-light">
                                <tr>
                                    <th>項目</th>
                                    <th>説明</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td><strong>クラスID</strong></td>
                                    <td>クラスの識別番号</td>
                                </tr>
                                <tr>
                                    <td><strong>学年</strong></td>
                                    <td>クラスが属する学年</td>
                                </tr>
                                <tr>
                                    <td><strong>クラス名</strong></td>
                                    <td>削除対象のクラス名（太字で強調表示）</td>
                                </tr>
                                <tr>
                                    <td><strong>在籍生徒数</strong></td>
                                    <td>
                                        現在在籍中の生徒の人数<br>
                                        <span class="badge bg-warning text-dark">在籍中</span> が表示される場合は削除不可
                                    </td>
                                </tr>
                                <tr>
                                    <td><strong>担任</strong></td>
                                    <td>
                                        現在の担任教師名<br>
                                        <span class="badge bg-info">担当中</span> が表示される場合は削除不可
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        
                        <h5 class="h6 text-warning">警告メッセージ</h5>
                        <p class="mb-2">削除できない場合は、以下のような黄色い警告ボックスが表示されます：</p>
                        <ul class="mb-3">
                            <li><strong>在籍中の生徒がいる場合</strong>: 「このクラスには現在○名の生徒が在籍しています。削除できません。」</li>
                            <li><strong>担当中の教師がいる場合</strong>: 「このクラスには現在担任（○○先生）が割り当てられています。削除できません。」</li>
                        </ul>
                        
                        <h5 class="h6 text-danger">削除の影響（赤色の警告ボックス）</h5>
                        <p class="mb-0">削除可能な場合、削除による影響が表示されます</p>
                    </div>
                </div>
                
                <h4 class="h6 mt-2 text-success"><i class="fas fa-cog"></i> 操作方法</h4>
                <div class="card mb-3">
                    <div class="card-body">
                        <h5 class="h6 text-success">削除できる場合の手順</h5>
                        <ol class="mb-3">
                            <li><strong>クラス情報を確認</strong>
                                <ul>
                                    <li>削除するクラスの情報が正しいか確認</li>
                                    <li>在籍生徒数: 0名</li>
                                    <li>担任: 未設定</li>
                                </ul>
                            </li>
                            <li><strong>警告メッセージがないことを確認</strong>
                                <ul>
                                    <li>黄色い警告ボックスが表示されていないこと</li>
                                    <li>「削除を実行」ボタンが有効（青色）になっていること</li>
                                </ul>
                            </li>
                            <li><strong>削除を実行</strong>
                                <ul>
                                    <li>画面下部の「削除を実行」ボタン（赤色）をクリック</li>
                                    <li>確認ダイアログが表示されます</li>
                                </ul>
                            </li>
                            <li><strong>最終確認</strong>
                                <ul>
                                    <li>ダイアログで「本当にこのクラスを削除しますか？」と表示</li>
                                    <li>「OK」をクリックで削除実行</li>
                                    <li>「キャンセル」をクリックで削除中止</li>
                                </ul>
                            </li>
                            <li><strong>完了</strong>
                                <ul>
                                    <li>クラス一覧画面に戻ります</li>
                                    <li>「クラスを削除しました」と表示されます</li>
                                </ul>
                            </li>
                        </ol>
                        
                        <h5 class="h6 text-danger">削除できない場合の対処</h5>
                        <ol class="mb-0">
                            <li><strong>在籍中の生徒がいる場合</strong>
                                <ul>
                                    <li>「キャンセル」ボタンでクラス一覧に戻る</li>
                                    <li>ユーザー管理またはクラス管理から生徒の在籍を変更</li>
                                    <li>すべての生徒の在籍を変更後、再度削除を試す</li>
                                </ul>
                            </li>
                            <li><strong>担当中の教師がいる場合</strong>
                                <ul>
                                    <li>「キャンセル」ボタンでクラス一覧に戻る</li>
                                    <li>担任割当画面から別のクラスに変更</li>
                                    <li>担任割当を解除後、再度削除を試す</li>
                                </ul>
                            </li>
                        </ol>
                    </div>
                </div>
                
                <h4 class="h6 mt-2 text-danger"><i class="fas fa-skull-crossbones"></i> 削除の影響（重要）</h4>
                <div class="alert alert-danger">
                    <strong class="d-block mb-2">クラスを削除すると以下のデータが完全に削除されます：</strong>
                    <ul class="mb-2">
                        <li><strong>過去の在籍記録</strong>: このクラスに在籍していたすべての生徒の履歴</li>
                        <li><strong>過去の担任記録</strong>: このクラスを担当していたすべての教師の履歴</li>
                    </ul>
                    <div class="bg-white text-danger p-2 rounded">
                        <strong><i class="fas fa-exclamation-triangle"></i> この操作は取り消すことができません</strong><br>
                        削除前に必ずクラス情報を確認してください
                    </div>
                </div>
                
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
