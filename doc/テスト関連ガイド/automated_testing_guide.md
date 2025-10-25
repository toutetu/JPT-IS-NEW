# 自動テスト実行ガイド

## 概要
JPT-IS-NEW アプリケーションの自動テスト実行ガイドです。
手動テストケースを自動化し、効率的なテスト実行を可能にします。

---

## 自動テストの構成

### テストクラス一覧
1. **StudentTest.php** - 生徒機能のテスト
2. **TeacherTest.php** - 担任機能のテスト
3. **AdminTest.php** - 管理者機能のテスト
4. **IntegrationTest.php** - 統合テスト
5. **AutomatedTestRunner.php** - テスト実行環境の確認

### テスト内容
- **ログイン・リダイレクト**: 各ロールでの適切な画面遷移
- **CRUD操作**: 作成・読み取り・更新・削除の基本機能
- **権限管理**: ロールベースのアクセス制御
- **UI改善**: ページネーション、フォーム、視覚的改善
- **統合フロー**: 複数ロール間での連携動作

---

## 自動テストの実行方法

### 1. 全テストの実行
```bash
# 全機能テストを実行
php artisan test --testsuite=Feature

# 詳細出力付きで実行
php artisan test --testsuite=Feature --verbose
```

### 2. 個別テストの実行
```bash
# 生徒機能のテスト
php artisan test tests/Feature/StudentTest.php

# 担任機能のテスト
php artisan test tests/Feature/TeacherTest.php

# 管理者機能のテスト
php artisan test tests/Feature/AdminTest.php

# 統合テスト
php artisan test tests/Feature/IntegrationTest.php
```

### 3. 自動実行スクリプトの使用

#### Linux/Mac環境
```bash
# 実行権限を付与
chmod +x run_tests.sh

# 自動テストを実行
./run_tests.sh
```

#### Windows環境
```cmd
# 自動テストを実行
run_tests.bat
```

---

## テスト実行の流れ

### 1. 環境準備
- 設定キャッシュのクリア
- ビューキャッシュのクリア
- アプリケーションキャッシュのクリア

### 2. データベース初期化
- マイグレーションの実行
- テスト用データの投入
- データベースのリセット

### 3. テスト実行
- 各機能のテスト実行
- 結果の出力
- エラーの検出

### 4. 結果確認
- テスト結果の表示
- エラーの有無確認
- 成功/失敗の判定

---

## テストケースの詳細

### 生徒機能のテスト
- **ログイン**: 自動リダイレクトの確認
- **マイ連絡帳**: 一覧表示・詳細表示
- **提出カレンダー**: カレンダー表示・提出状況
- **新規提出**: フォーム表示・提出処理
- **編集機能**: 修正・保存・権限制御
- **未提出生徒**: 空データの表示

### 担任機能のテスト
- **提出状況ダッシュボード**: 統計表示・フィルタ
- **連絡帳詳細**: 詳細表示・既読処理
- **生徒過去記録**: 担当クラス管理・個別記録
- **権限制御**: 担当外クラスへのアクセス制御
- **視覚的改善**: 既読マーク・未読マーク

### 管理者機能のテスト
- **ユーザー管理**: 一覧表示・新規作成
- **在籍割当**: クラス割当変更
- **担任割当**: 担当クラス変更
- **権限制御**: 管理者以外のアクセス制御
- **データ整合性**: 割当情報の表示

### 統合テスト
- **完全フロー**: 生徒提出→担任確認→既読処理
- **管理フロー**: ユーザー作成→割当→確認
- **UI改善**: ページネーション・フォーム・視覚的改善
- **エラーハンドリング**: 重複・権限・バリデーション

---

## 期待される結果

### 正常な実行
```
PASS  Tests\Feature\StudentTest
PASS  Tests\Feature\TeacherTest  
PASS  Tests\Feature\AdminTest
PASS  Tests\Feature\IntegrationTest

Tests:  50 passed
Time:   2.5s
```

### エラーが発生した場合
```
FAIL  Tests\Feature\StudentTest::test_student_can_submit_daily_log

Error: Database connection failed
```

---

## トラブルシューティング

### よくある問題

#### 1. データベース接続エラー
```bash
# 解決方法
php artisan config:clear
php artisan migrate:fresh
```

#### 2. テストデータの不整合
```bash
# 解決方法
php artisan migrate:fresh --seed --class=InternshipTestSeeder
```

#### 3. キャッシュの問題
```bash
# 解決方法
php artisan optimize:clear
php artisan config:clear
php artisan view:clear
```

#### 4. 権限エラー
```bash
# 解決方法
chmod +x run_tests.sh  # Linux/Mac
```

### デバッグ方法

#### 1. 詳細出力での実行
```bash
php artisan test --verbose
```

#### 2. 特定のテストのみ実行
```bash
php artisan test --filter=test_student_can_submit_daily_log
```

#### 3. テスト環境の確認
```bash
php artisan test tests/Feature/AutomatedTestRunner.php
```

---

## 継続的インテグレーション

### GitHub Actions での自動実行
```yaml
name: Automated Tests
on: [push, pull_request]
jobs:
  test:
    runs-on: ubuntu-latest
    steps:
      - uses: actions/checkout@v2
      - name: Setup PHP
        uses: shivammathur/setup-php@v2
        with:
          php-version: '8.1'
      - name: Install dependencies
        run: composer install
      - name: Run tests
        run: php artisan test --testsuite=Feature
```

### ローカルでの定期実行
```bash
# cron での定期実行設定例
0 2 * * * cd /path/to/project && ./run_tests.sh >> test_results.log 2>&1
```

---

## テスト結果の解釈

### 成功パターン
- 全テストが PASS
- エラーメッセージなし
- 実行時間が適切

### 失敗パターン
- テストが FAIL
- エラーメッセージあり
- データベースエラー
- 権限エラー

### 改善点の特定
- 失敗したテストの特定
- エラーメッセージの分析
- ログファイルの確認
- データベース状態の確認

---

## まとめ

この自動テストシステムにより、以下の効果が期待できます：

1. **効率性**: 手動テストの自動化
2. **信頼性**: 一貫したテスト実行
3. **迅速性**: 短時間での全機能テスト
4. **品質保証**: 継続的な品質チェック
5. **開発支援**: リグレッションの早期発見

自動テストを活用して、高品質なアプリケーションの開発・保守を行いましょう。


