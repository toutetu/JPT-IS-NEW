# インターンシップ テストガイド

## 概要
ContactBook アプリケーションのインターンシップ用テストガイドです。
効率的にテストできるよう、実用的なテストデータとシナリオを用意しています。

---

## テストアカウント一覧

### 管理者
| 役割 | メールアドレス | パスワード | 説明 |
|------|----------------|------------|------|
| 管理者 | `admin@example.com` | `Passw0rd!` | システム全体の管理 |

### 担任（6名）
| 役割 | メールアドレス | パスワード | 担当クラス |
|------|----------------|------------|------------|
| 田中先生 | `teacher1@example.com` | `Passw0rd!` | 1年A組 |
| 佐藤先生 | `teacher2@example.com` | `Passw0rd!` | 1年B組 |
| 鈴木先生 | `teacher3@example.com` | `Passw0rd!` | 2年A組 |
| 高橋先生 | `teacher4@example.com` | `Passw0rd!` | 2年B組 |
| 山田先生 | `teacher5@example.com` | `Passw0rd!` | 3年A組 |
| 渡辺先生 | `teacher6@example.com` | `Passw0rd!` | 3年B組 |

### 生徒（24名）
| 学年 | クラス | 名前 | メールアドレス | パスワード | 特徴 |
|------|--------|------|----------------|------------|------|
| 1年 | A組 | 山田太郎 | `student001@example.com` | `Passw0rd!` | 完璧な生徒（毎日提出） |
| 1年 | A組 | 佐藤花子 | `student002@example.com` | `Passw0rd!` | 普通の生徒（80%提出） |
| 1年 | A組 | 田中一郎 | `student003@example.com` | `Passw0rd!` | 体調不良の生徒（60%提出） |
| 1年 | A組 | 小林未提出 | `student004@example.com` | `Passw0rd!` | **未提出の生徒（0%提出）** |
| 1年 | B組 | 鈴木美咲 | `student005@example.com` | `Passw0rd!` | メンタル不調の生徒（70%提出） |
| 1年 | B組 | 高橋健太 | `student006@example.com` | `Passw0rd!` | 不規則な生徒（50%提出） |
| 1年 | B組 | 渡辺さくら | `student007@example.com` | `Passw0rd!` | 新入生（最近始めた） |
| 1年 | B組 | 中島未提出 | `student008@example.com` | `Passw0rd!` | **未提出の生徒（0%提出）** |
| 2年 | A組 | 伊藤大輔 | `student009@example.com` | `Passw0rd!` | 完璧な生徒（毎日提出） |
| 2年 | A組 | 加藤由美 | `student010@example.com` | `Passw0rd!` | 普通の生徒（80%提出） |
| 2年 | A組 | 林直樹 | `student011@example.com` | `Passw0rd!` | 体調不良の生徒（60%提出） |
| 2年 | A組 | 西村未提出 | `student012@example.com` | `Passw0rd!` | **未提出の生徒（0%提出）** |
| 2年 | B組 | 森田あい | `student013@example.com` | `Passw0rd!` | メンタル不調の生徒（70%提出） |
| 2年 | B組 | 石川雄一 | `student014@example.com` | `Passw0rd!` | 不規則な生徒（50%提出） |
| 2年 | B組 | 中村みどり | `student015@example.com` | `Passw0rd!` | 新入生（最近始めた） |
| 2年 | B組 | 東田未提出 | `student016@example.com` | `Passw0rd!` | **未提出の生徒（0%提出）** |
| 3年 | A組 | 木村拓也 | `student017@example.com` | `Passw0rd!` | 完璧な生徒（毎日提出） |
| 3年 | A組 | 清水恵 | `student018@example.com` | `Passw0rd!` | 普通の生徒（80%提出） |
| 3年 | A組 | 松本慎一 | `student019@example.com` | `Passw0rd!` | 体調不良の生徒（60%提出） |
| 3年 | A組 | 北川未提出 | `student020@example.com` | `Passw0rd!` | **未提出の生徒（0%提出）** |
| 3年 | B組 | 井上麻衣 | `student021@example.com` | `Passw0rd!` | メンタル不調の生徒（70%提出） |
| 3年 | B組 | 岡田翔太 | `student022@example.com` | `Passw0rd!` | 不規則な生徒（50%提出） |
| 3年 | B組 | 小川優子 | `student023@example.com` | `Passw0rd!` | 新入生（最近始めた） |
| 3年 | B組 | 南野未提出 | `student024@example.com` | `Passw0rd!` | **未提出の生徒（0%提出）** |

---

## テストシナリオ

### 1. 生徒機能のテスト

#### 1.1 完璧な生徒（山田太郎）でのテスト
1. `student001@example.com` でログイン
2. マイ連絡帳一覧を確認（過去の提出履歴が豊富）
3. 提出カレンダーを確認（多くの日付に「済」マーク）
4. 新規提出を実行
5. 提出内容の詳細表示・編集を確認

#### 1.2 体調不良の生徒（田中一郎）でのテスト
1. `student003@example.com` でログイン
2. マイ連絡帳一覧を確認（提出頻度が低い）
3. 提出カレンダーを確認（空白の日が多い）
4. 体調・メンタルスコアが低めの提出を確認

#### 1.3 新入生（渡辺さくら）でのテスト
1. `student007@example.com` でログイン
2. 最近の提出データが多いことを確認
3. 体調・メンタルスコアが高めの傾向を確認

#### 1.4 未提出の生徒（小林未提出）でのテスト
1. `student004@example.com` でログイン
2. マイ連絡帳一覧が空であることを確認
3. 提出カレンダーに「済」マークがないことを確認
4. 新規提出を実行して初回提出をテスト

### 2. 担任機能のテスト

#### 2.1 1年A組担任（田中先生）でのテスト
1. `teacher1@example.com` でログイン
2. 提出状況ダッシュボードを確認
   - 山田太郎（完璧）: 提出率高い
   - 佐藤花子（普通）: 提出率中程度
   - 田中一郎（体調不良）: 提出率低い
   - **小林未提出（未提出）: 提出率0%**
3. 連絡帳の詳細表示・既読処理を実行
4. 担当クラス生徒の過去記録を確認
5. **未提出生徒の確認**: 小林未提出の記録がないことを確認

#### 2.2 2年A組担任（鈴木先生）でのテスト
1. `teacher3@example.com` でログイン
2. 異なるパターンの生徒データを確認
3. 日付フィルタ機能をテスト
4. 既読・未読の管理機能をテスト

### 3. 管理者機能のテスト

#### 3.1 ユーザー管理
1. `admin@example.com` でログイン
2. ユーザー一覧で各ロールの表示を確認
3. 割り当てクラス情報の表示を確認
4. 新規ユーザー作成をテスト

#### 3.2 在籍・担任割当
1. 生徒の在籍クラス変更をテスト
2. 担任の担当クラス割当変更をテスト
3. 変更後の反映を確認

---

## データの特徴

### 生徒パターン
- **完璧な生徒**: 毎日提出、体調・メンタル良好
- **普通の生徒**: 80%提出、一般的な体調・メンタル
- **体調不良の生徒**: 60%提出、体調スコア低め
- **メンタル不調の生徒**: 70%提出、メンタルスコア低め
- **不規則な生徒**: 50%提出、ばらつきあり
- **新入生**: 90%提出、体調・メンタル良好

### 既読状況
- 80%の提出が既読済み
- 20%が未読状態
- 既読日時もランダムに設定

### 提出時間
- 8:00-16:00の間でランダム
- 既読は提出後1-24時間後にランダム

---

## テスト実行手順

### 1. データベース初期化

#### 通常の環境
```bash
php artisan migrate:fresh --seed --class=InternshipTestSeeder
```

#### Laravel Cloud環境
```bash
php artisan migrate:fresh --seed --class=InternshipTestSeeder --force
```

#### 既存データを保持してシーダーのみ実行
```bash
php artisan db:seed --class=InternshipTestSeeder
```

### 2. アプリケーション起動
```bash
php artisan serve
```

### 3. ブラウザでアクセス
- URL: `http://localhost:8000`
- 上記のアカウントでログインしてテスト

---

## 注意事項

- 全アカウントのパスワードは `Passw0rd!` で統一
- データは過去30日分の平日のみ生成
- 各生徒に異なるパターンのデータを設定
- リアルな学校環境を想定したデータ構成

---

## トラブルシューティング

### ログインできない場合
1. **通常の環境**: `php artisan migrate:fresh --seed --class=InternshipTestSeeder`
2. **Laravel Cloud環境**: `php artisan migrate:fresh --seed --class=InternshipTestSeeder --force`
3. アプリケーションの再起動: `php artisan serve`

### データが表示されない場合
1. **シーダーの実行確認**: `php artisan db:seed --class=InternshipTestSeeder`
2. **キャッシュのクリア**: `php artisan optimize:clear`

### Laravel Cloud環境での注意点
- プロダクション環境では`--force`オプションが必要
- `migrate:fresh`コマンドは既存データを完全に削除します
- データベースのバックアップを取ることを推奨します
