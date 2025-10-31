# Mailtrap セットアップガイド

## 📧 Mailtrapとは

Mailtrapは**開発・テスト環境専用**のメールサービスです。
実際にメールを送信せず、送信されたメールを安全にキャッチして確認できます。

### 特徴
- ✅ 実際のメールを送信しない（誤送信防止）
- ✅ 送信メールの内容を確認できる
- ✅ HTML/テキスト両方の表示確認
- ✅ スパムスコアのチェック
- ✅ 無料枠: 月500通まで

---

## 🚀 Mailtrapアカウント作成手順

### 1. アカウント登録

1. **Mailtrap公式サイトにアクセス**
   - URL: https://mailtrap.io/

2. **Sign Upをクリック**
   - メールアドレスとパスワードを入力
   - または、GitHubアカウントでサインアップ

3. **メール認証**
   - 登録したメールアドレスに確認メールが届く
   - リンクをクリックして認証完了

### 2. Inboxの作成

1. **ダッシュボードにログイン**
   - https://mailtrap.io/inboxes

2. **デフォルトのInboxを使用**
   - 初回登録時に自動的に "My Inbox" が作成される
   - または、新しいInboxを作成: 「Add Inbox」をクリック

### 3. SMTP認証情報を取得

1. **Inboxを選択**
   - 作成したInboxをクリック

2. **SMTP Settingsを表示**
   - 「SMTP Settings」タブをクリック
   - 「Show Credentials」をクリック

3. **認証情報をコピー**
   ```
   Host: smtp.mailtrap.io (または sandbox.smtp.mailtrap.io)
   Port: 2525 (または 465, 587, 25)
   Username: <あなたのユーザー名>
   Password: <あなたのパスワード>
   ```

---

## ⚙️ Laravelローカル環境の設定

### 1. `.env` ファイルを編集

プロジェクトルートの `.env` ファイルに以下を追加：

```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=<Mailtrapのユーザー名>
MAIL_PASSWORD=<Mailtrapのパスワード>
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=noreply@example.com
MAIL_FROM_NAME="${APP_NAME}"
```

**重要**: `MAIL_USERNAME` と `MAIL_PASSWORD` は、Mailtrapの「SMTP Settings」から取得した値を使用してください。

### 2. キャッシュをクリア

```bash
php artisan config:clear
php artisan cache:clear
```

### 3. ローカルでテスト

1. **開発サーバーを起動**
   ```bash
   php artisan serve
   ```

2. **ログイン画面にアクセス**
   - http://localhost:8000/login

3. **「パスワードをお忘れですか？」をクリック**

4. **メールアドレスを入力**
   - データベースに存在するユーザーのメールアドレス
   - 例: `student1@example.com`

5. **Mailtrapで確認**
   - Mailtrapのダッシュボードに戻る
   - Inboxにメールが届いているか確認
   - メール内のリセットリンクをクリック

6. **新しいパスワードを設定**
   - パスワードリセット画面が表示される
   - 新しいパスワードを入力して送信

7. **ログインテスト**
   - 新しいパスワードでログインできるか確認

---

## 🌐 Laravel Cloudでの設定

### 1. Laravel Cloud環境変数を設定

1. **Laravel Cloudダッシュボードにアクセス**
   - https://cloud.laravel.com/

2. **プロジェクトを選択**
   - 対象のプロジェクト（contact-book-main1）をクリック

3. **環境変数を設定**
   - 左メニューから「Environment」または「Settings」を選択
   - 「Environment Variables」セクションを探す

4. **以下の変数を追加**
   ```
   MAIL_MAILER=smtp
   MAIL_HOST=smtp.mailtrap.io
   MAIL_PORT=2525
   MAIL_USERNAME=<Mailtrapのユーザー名>
   MAIL_PASSWORD=<Mailtrapのパスワード>
   MAIL_ENCRYPTION=tls
   MAIL_FROM_ADDRESS=noreply@example.com
   MAIL_FROM_NAME=ContactBook
   ```

5. **保存して再デプロイ**
   - 「Save」または「Update」をクリック
   - 環境変数の変更後は自動的に再デプロイされる場合がある
   - されない場合は手動でデプロイを実行

### 2. デプロイ後のテスト

1. **本番環境にアクセス**
   - https://contact-book-main1-flsrse.laravel.cloud/login

2. **パスワードリセットをテスト**
   - 「パスワードをお忘れですか？」をクリック
   - メールアドレスを入力
   - Mailtrapでメールを確認

---

## ✅ 動作確認チェックリスト

### ローカル環境
- [ ] `.env` にMailtrap設定を追加
- [ ] `config:clear` を実行
- [ ] ログイン画面に「パスワードをお忘れですか？」リンクが表示される
- [ ] メールアドレス入力後、Mailtrapにメールが届く
- [ ] メール内のリンクからパスワードリセット画面が開く
- [ ] 新しいパスワードでログインできる

### Laravel Cloud環境
- [ ] Laravel Cloudの環境変数にMailtrap設定を追加
- [ ] 再デプロイを実行
- [ ] 本番環境のログイン画面でリンクが表示される
- [ ] メールアドレス入力後、Mailtrapにメールが届く
- [ ] パスワードリセットが正常に動作する

---

## 🎯 本番環境への切り替え（将来）

Mailtrapは**テスト環境専用**です。本番環境では以下のサービスに切り替えてください：

### 推奨メールサービス

#### 1. **Mailgun**（推奨）
- 無料枠: 月5,000通
- Laravel公式パートナー
- 高い到達率

#### 2. **SendGrid**
- 無料枠: 月100通
- 高い到達率
- 豊富な機能

#### 3. **Amazon SES**
- 低コスト: $0.10/1000通
- AWS統合

### 切り替え手順
1. 選択したサービスでアカウント作成
2. API認証情報を取得
3. Laravel Cloudの環境変数を更新
4. 再デプロイ

---

## 🔍 トラブルシューティング

### メールが届かない場合

1. **環境変数を確認**
   ```bash
   php artisan config:clear
   php artisan tinker
   >>> config('mail')
   ```

2. **Mailtrapの認証情報を再確認**
   - ユーザー名とパスワードが正しいか
   - InboxのSMTP Settingsを確認

3. **ログを確認**
   ```bash
   tail -f storage/logs/laravel.log
   ```

4. **ファイアウォール/ポートの確認**
   - ポート2525が開いているか
   - 別のポート（587, 465, 25）を試す

### メールが送信されるが、リンクが無効

1. **APP_URLを確認**
   - `.env` の `APP_URL` が正しいか
   - ローカル: `http://localhost:8000`
   - 本番: `https://contact-book-main1-flsrse.laravel.cloud`

2. **キャッシュをクリア**
   ```bash
   php artisan config:clear
   php artisan cache:clear
   ```

### エラーメッセージ別対処

#### "Connection refused"
- MAIL_HOSTが正しいか確認
- ポート番号を変更してみる（2525 → 587）

#### "Authentication failed"
- MAIL_USERNAMEとMAIL_PASSWORDを再確認
- Mailtrapのダッシュボードで認証情報を再取得

#### "Address in mailbox given [] does not comply with RFC 2822"
- MAIL_FROM_ADDRESSが正しい形式か確認
- 例: `noreply@example.com`

---

## 📚 参考リンク

- [Mailtrap公式サイト](https://mailtrap.io/)
- [Mailtrapドキュメント](https://help.mailtrap.io/)
- [Laravel Mail公式ドキュメント](https://laravel.com/docs/11.x/mail)
- [Laravel Password Reset公式ドキュメント](https://laravel.com/docs/11.x/passwords)

---

## 💡 メモ

- Mailtrapは**テスト専用**。本番では実際のメールサービスに切り替えること
- 無料枠は月500通まで。超過する場合は有料プランを検討
- メールのHTML/テキスト表示を両方確認すること
- スパムスコアをチェックして、本番環境での到達率向上に役立てる

