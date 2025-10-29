@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="mb-4">管理者向け 操作マニュアル</h1>

    <div class="alert alert-info">
        画像はダミーです。指定いただくスクリーンショットをご提供後に差し替えます。
    </div>

    <h2 class="h4 mt-4">1. ユーザー管理</h2>
    <p>「ユーザー管理」からユーザー一覧・作成・削除・CSVインポートを行います。</p>
    <img src="/storage/public/manual/placeholders/admin_users_index.png" alt="ユーザー一覧" class="img-fluid border mb-3">
    <img src="/storage/public/manual/placeholders/admin_users_create.png" alt="ユーザー作成" class="img-fluid border mb-3">
    <img src="/storage/public/manual/placeholders/admin_users_import.png" alt="CSVインポート" class="img-fluid border mb-3">

    <h2 class="h4 mt-4">2. 在籍割当・担任割当</h2>
    <p>管理メニューから在籍割当・担任割当を実行します。</p>
    <img src="/storage/public/manual/placeholders/admin_assign_enrollment.png" alt="在籍割当" class="img-fluid border mb-3">
    <img src="/storage/public/manual/placeholders/admin_assign_homeroom.png" alt="担任割当" class="img-fluid border mb-3">

    <h2 class="h4 mt-4">3. マニュアル/ログアウト</h2>
    <p>右上の「マニュアル」および「ログアウト」を利用します。</p>
</div>
@endsection


