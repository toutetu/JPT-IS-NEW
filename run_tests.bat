@echo off
REM JPT-IS-NEW 自動テスト実行バッチファイル

echo === JPT-IS-NEW 自動テスト実行 ===
echo 開始時刻: %date% %time%
echo.

REM テスト環境の準備
echo 1. テスト環境の準備...
php artisan config:clear
php artisan cache:clear
php artisan view:clear

REM データベースの初期化
echo 2. データベースの初期化...
php artisan migrate:fresh --seed --class=InternshipTestSeeder

REM テストの実行
echo 3. 自動テストの実行...

echo.
echo === 生徒機能のテスト ===
php artisan test tests/Feature/StudentTest.php --verbose

echo.
echo === 担任機能のテスト ===
php artisan test tests/Feature/TeacherTest.php --verbose

echo.
echo === 管理者機能のテスト ===
php artisan test tests/Feature/AdminTest.php --verbose

echo.
echo === 統合テスト ===
php artisan test tests/Feature/IntegrationTest.php --verbose

echo.
echo === 全テストの実行 ===
php artisan test --testsuite=Feature --verbose

echo.
echo === テスト完了 ===
echo 終了時刻: %date% %time%
echo.
echo === テスト結果の確認 ===
echo 上記の出力でエラーがないことを確認してください。
echo 全てのテストが PASS であれば、アプリケーションは正常に動作しています。
pause


