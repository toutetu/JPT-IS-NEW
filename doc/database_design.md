# データベース設計書

## 概要
JPT-IS-NEW アプリケーションのデータベース設計書です。
生徒の連絡帳提出システムにおける各テーブルの詳細な仕様を記載しています。

---

## テーブル一覧

### 1. users（ユーザー）
システムの全ユーザー（管理者・担任・生徒）を管理するテーブル

| カラム名 | データ型 | PK | FK | NULL | 説明 |
|---------|---------|----|----|----- |------|
| id | BIGINT | ○ | × | × | ユーザーID（自動採番） |
| name | VARCHAR(255) | × | × | × | 氏名 |
| email | VARCHAR(255) | × | × | × | メールアドレス（ユニーク） |
| email_verified_at | TIMESTAMP | × | × | ○ | メール認証日時 |
| password | VARCHAR(255) | × | × | × | パスワード（ハッシュ化） |
| role | VARCHAR(255) | × | × | × | ロール（admin/teacher/student） |
| remember_token | VARCHAR(100) | × | × | ○ | ログイン記憶用トークン |
| created_at | TIMESTAMP | × | × | × | 作成日時 |
| updated_at | TIMESTAMP | × | × | × | 更新日時 |

**インデックス**
- `email` (UNIQUE)
- `role`

---

### 2. grades（学年）
学年情報を管理するマスターテーブル

| カラム名 | データ型 | PK | FK | NULL | 説明 |
|---------|---------|----|----|----- |------|
| id | BIGINT | ○ | × | × | 学年ID（自動採番） |
| name | VARCHAR(255) | × | × | × | 学年名（例：1年、2年） |
| created_at | TIMESTAMP | × | × | × | 作成日時 |
| updated_at | TIMESTAMP | × | × | × | 更新日時 |

---

### 3. classrooms（クラス）
クラス情報を管理するテーブル

| カラム名 | データ型 | PK | FK | NULL | 説明 |
|---------|---------|----|----|----- |------|
| id | BIGINT | ○ | × | × | クラスID（自動採番） |
| grade_id | BIGINT | × | ○ | × | 学年ID（grades.id） |
| name | VARCHAR(255) | × | × | × | クラス名（例：A組、B組） |
| created_at | TIMESTAMP | × | × | × | 作成日時 |
| updated_at | TIMESTAMP | × | × | × | 更新日時 |

**外部キー制約**
- `grade_id` → `grades.id` (CASCADE UPDATE, RESTRICT DELETE)

**インデックス**
- `(grade_id, name)` (複合インデックス)

---

### 4. enrollments（在籍）
生徒のクラス在籍履歴を管理するテーブル

| カラム名 | データ型 | PK | FK | NULL | 説明 |
|---------|---------|----|----|----- |------|
| id | BIGINT | ○ | × | × | 在籍ID（自動採番） |
| student_id | BIGINT | × | ○ | × | 生徒ID（users.id） |
| classroom_id | BIGINT | × | ○ | × | クラスID（classrooms.id） |
| is_active | BOOLEAN | × | × | × | アクティブフラグ（デフォルト：true） |
| since_date | DATE | × | × | ○ | 在籍開始日 |
| until_date | DATE | × | × | ○ | 在籍終了日 |
| created_at | TIMESTAMP | × | × | × | 作成日時 |
| updated_at | TIMESTAMP | × | × | × | 更新日時 |

**外部キー制約**
- `student_id` → `users.id` (CASCADE UPDATE, RESTRICT DELETE)
- `classroom_id` → `classrooms.id` (CASCADE UPDATE, RESTRICT DELETE)

**インデックス**
- `(student_id, classroom_id)` (複合インデックス)

---

### 5. homeroom_assignments（担任割当）
担任のクラス担当履歴を管理するテーブル

| カラム名 | データ型 | PK | FK | NULL | 説明 |
|---------|---------|----|----|----- |------|
| id | BIGINT | ○ | × | × | 担任割当ID（自動採番） |
| teacher_id | BIGINT | × | ○ | × | 担任ID（users.id） |
| classroom_id | BIGINT | × | ○ | × | クラスID（classrooms.id） |
| since_date | DATE | × | × | ○ | 担当開始日 |
| until_date | DATE | × | × | ○ | 担当終了日 |
| created_at | TIMESTAMP | × | × | × | 作成日時 |
| updated_at | TIMESTAMP | × | × | × | 更新日時 |

**外部キー制約**
- `teacher_id` → `users.id` (CASCADE UPDATE, RESTRICT DELETE)
- `classroom_id` → `classrooms.id` (CASCADE UPDATE, RESTRICT DELETE)

**インデックス**
- `(teacher_id, classroom_id)` (複合インデックス)

---

### 6. daily_logs（連絡帳）
生徒の連絡帳提出記録を管理するテーブル

| カラム名 | データ型 | PK | FK | NULL | 説明 |
|---------|---------|----|----|----- |------|
| id | BIGINT | ○ | × | × | 連絡帳ID（自動採番） |
| student_id | BIGINT | × | ○ | × | 生徒ID（users.id） |
| target_date | DATE | × | × | × | 対象日（前登校日） |
| health_score | TINYINT | × | × | × | 体調スコア（1-5） |
| mental_score | TINYINT | × | × | × | メンタルスコア（1-5） |
| body | TEXT | × | × | × | 本文 |
| submitted_at | TIMESTAMP | × | × | × | 提出日時 |
| read_at | TIMESTAMP | × | × | ○ | 既読日時 |
| read_by | BIGINT | × | ○ | ○ | 既読者ID（users.id） |
| created_at | TIMESTAMP | × | × | × | 作成日時 |
| updated_at | TIMESTAMP | × | × | × | 更新日時 |

**外部キー制約**
- `student_id` → `users.id` (CASCADE UPDATE, RESTRICT DELETE)
- `read_by` → `users.id` (CASCADE UPDATE, RESTRICT DELETE)

**制約**
- `(student_id, target_date)` (UNIQUE) - 同一生徒の同一日重複提出防止

---

### 7. sessions（セッション）
ユーザーのセッション情報を管理するテーブル

| カラム名 | データ型 | PK | FK | NULL | 説明 |
|---------|---------|----|----|----- |------|
| id | VARCHAR(255) | ○ | × | × | セッションID |
| user_id | BIGINT | × | ○ | ○ | ユーザーID（users.id） |
| ip_address | VARCHAR(45) | × | × | ○ | IPアドレス |
| user_agent | TEXT | × | × | ○ | ユーザーエージェント |
| payload | LONGTEXT | × | × | × | セッションデータ |
| last_activity | INT | × | × | × | 最終アクティビティ |

**インデックス**
- `user_id`
- `last_activity`

---

### 8. password_reset_tokens（パスワードリセット）
パスワードリセット用トークンを管理するテーブル

| カラム名 | データ型 | PK | FK | NULL | 説明 |
|---------|---------|----|----|----- |------|
| email | VARCHAR(255) | ○ | × | × | メールアドレス |
| token | VARCHAR(255) | × | × | × | リセットトークン |
| created_at | TIMESTAMP | × | × | ○ | 作成日時 |

---

## テーブル関係図

```
users (1) ←→ (N) enrollments (N) ←→ (1) classrooms (N) ←→ (1) grades
users (1) ←→ (N) homeroom_assignments (N) ←→ (1) classrooms
users (1) ←→ (N) daily_logs (N) ←→ (1) users (read_by)
users (1) ←→ (N) sessions
users (1) ←→ (1) password_reset_tokens
```

---

## 設計方針

### 1. 履歴管理
- `enrollments`と`homeroom_assignments`は履歴管理を実装
- `is_active`フラグと`since_date`/`until_date`で期間管理
- 転校・転クラス・担任変更に対応

### 2. データ整合性
- 外部キー制約による参照整合性の保証
- ユニーク制約による重複防止
- 適切なインデックスによる検索性能の最適化

### 3. セキュリティ
- パスワードはハッシュ化して保存
- セッション管理による認証状態の管理
- ロールベースのアクセス制御

### 4. パフォーマンス
- よく使用される検索条件にインデックスを設定
- 複合インデックスによる効率的な検索
- 適切なデータ型の選択

---

## 初期データ

### 学年データ
- 1年、2年、3年

### クラスデータ
- 各学年にA組、B組（計6クラス）

### ユーザーデータ
- 管理者：1名
- 担任：8名
- 生徒：30名

### テストアカウント
詳細は `doc/test_accounts.md` を参照してください。