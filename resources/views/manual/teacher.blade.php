@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="mb-4">先生向け 操作マニュアル</h1>

    <div class="alert alert-info">
        画像はダミーです。指定いただくスクリーンショットをご提供後に差し替えます。
    </div>

    <h2 class="h4 mt-4">1. ログイン</h2>
    <img src="/storage/public/manual/placeholders/login.png" alt="ログイン画面" class="img-fluid border mb-3">

    <h2 class="h4 mt-4">2. 提出状況（担当クラス）</h2>
    <p>「提出状況（担当クラス）」で直近の提出一覧を確認します。</p>
    <img src="/storage/public/manual/placeholders/teacher_daily_logs_index.png" alt="提出状況一覧" class="img-fluid border mb-3">

    <h2 class="h4 mt-4">3. 生徒別過去記録</h2>
    <p>「生徒別過去記録」から生徒を選び、過去記録を一覧表示します。</p>
    <img src="/storage/public/manual/placeholders/teacher_students_index.png" alt="生徒一覧" class="img-fluid border mb-3">
    <img src="/storage/public/manual/placeholders/teacher_student_logs.png" alt="生徒の過去記録" class="img-fluid border mb-3">

    <h2 class="h4 mt-4">4. 既読処理</h2>
    <p>提出詳細から「既読」ボタンで既読処理を行います。</p>
    <img src="/storage/public/manual/placeholders/teacher_daily_log_show.png" alt="提出詳細" class="img-fluid border mb-3">

    <h2 class="h4 mt-4">5. マニュアル/ログアウト</h2>
    <p>右上の「マニュアル」および「ログアウト」を利用します。</p>
</div>
@endsection


