@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="mb-4">学生向け 操作マニュアル</h1>

    <div class="alert alert-info">
        画像はダミーです。指定いただくスクリーンショットをご提供後に差し替えます。
    </div>

    <h2 class="h4 mt-4">1. ログイン</h2>
    <p>配布されたアカウントでログインします。</p>
    <img src="/storage/public/manual/placeholders/login.png" alt="ログイン画面" class="img-fluid border mb-3">

    <h2 class="h4 mt-4">2. マイ連絡帳の確認・提出</h2>
    <ol>
        <li>ナビゲーションの「マイ連絡帳」から一覧を開く</li>
        <li>「新規作成」で連絡帳を入力し、保存する</li>
    </ol>
    <img src="/storage/public/manual/placeholders/student_daily_logs_index.png" alt="マイ連絡帳一覧" class="img-fluid border mb-3">
    <img src="/storage/public/manual/placeholders/student_daily_log_create.png" alt="連絡帳作成" class="img-fluid border mb-3">

    <h2 class="h4 mt-4">3. 提出カレンダー</h2>
    <p>「提出カレンダー」で提出状況を確認できます。</p>
    <img src="/storage/public/manual/placeholders/student_calendar.png" alt="提出カレンダー" class="img-fluid border mb-3">

    <h2 class="h4 mt-4">4. マニュアルの場所</h2>
    <p>右上の「マニュアル」から本ページをいつでも開けます。</p>

    <h2 class="h4 mt-4">5. ログアウト</h2>
    <p>右上の「ログアウト」からサインアウトします。</p>
</div>
@endsection


