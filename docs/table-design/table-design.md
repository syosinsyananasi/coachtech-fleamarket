# テーブル設計書

---

## 1. usersテーブル

| カラム名 | 型 | PRIMARY KEY | UNIQUE KEY | NOT NULL | FOREIGN KEY |
| --- | --- | --- | --- | --- | --- |
| id | UNSIGNED BIGINT | ○ |  | ○ |  |
| name | VARCHAR(20) |  |  | ○ |  |
| email | VARCHAR(255) |  | ○ | ○ |  |
| email_verified_at | TIMESTAMP |  |  |  |  |
| password | VARCHAR(255) |  |  | ○ |  |
| remember_token | VARCHAR(255) |  |  |  |  |
| created_at | TIMESTAMP |  |  |  |  |
| updated_at | TIMESTAMP |  |  |  |  |

---

## 2. profilesテーブル

| カラム名 | 型 | PRIMARY KEY | UNIQUE KEY | NOT NULL | FOREIGN KEY |
| --- | --- | --- | --- | --- | --- |
| id | UNSIGNED BIGINT | ○ |  | ○ |  |
| user_id | UNSIGNED BIGINT |  | ○ | ○ | users(id) |
| profile_image | VARCHAR(255) |  |  |  |  |
| postal_code | VARCHAR(8) |  |  | ○ |  |
| address | VARCHAR(255) |  |  | ○ |  |
| building | VARCHAR(255) |  |  |  |  |
| created_at | TIMESTAMP |  |  |  |  |
| updated_at | TIMESTAMP |  |  |  |  |

---

## 3. itemsテーブル

| カラム名 | 型 | PRIMARY KEY | UNIQUE KEY | NOT NULL | FOREIGN KEY |
| --- | --- | --- | --- | --- | --- |
| id | UNSIGNED BIGINT | ○ |  | ○ |  |
| user_id | UNSIGNED BIGINT |  |  | ○ | users(id) |
| condition_id | UNSIGNED BIGINT |  |  | ○ | conditions(id) |
| name | VARCHAR(255) |  |  | ○ |  |
| brand | VARCHAR(255) |  |  |  |  |
| description | VARCHAR(255) |  |  | ○ |  |
| price | UNSIGNED INT |  |  | ○ |  |
| image | VARCHAR(255) |  |  | ○ |  |
| is_sold | BOOLEAN |  |  | ○ |  |
| created_at | TIMESTAMP |  |  |  |  |
| updated_at | TIMESTAMP |  |  |  |  |

---

## 4. categoriesテーブル

| カラム名 | 型 | PRIMARY KEY | UNIQUE KEY | NOT NULL | FOREIGN KEY |
| --- | --- | --- | --- | --- | --- |
| id | UNSIGNED BIGINT | ○ |  | ○ |  |
| name | VARCHAR(255) |  |  | ○ |  |
| created_at | TIMESTAMP |  |  |  |  |
| updated_at | TIMESTAMP |  |  |  |  |

---

## 5. category_itemテーブル

| カラム名 | 型 | PRIMARY KEY | UNIQUE KEY | NOT NULL | FOREIGN KEY |
| --- | --- | --- | --- | --- | --- |
| id | UNSIGNED BIGINT | ○ |  | ○ |  |
| item_id | UNSIGNED BIGINT |  |  | ○ | items(id) |
| category_id | UNSIGNED BIGINT |  |  | ○ | categories(id) |
| created_at | TIMESTAMP |  |  |  |  |
| updated_at | TIMESTAMP |  |  |  |  |

---

## 6. conditionsテーブル

| カラム名 | 型 | PRIMARY KEY | UNIQUE KEY | NOT NULL | FOREIGN KEY |
| --- | --- | --- | --- | --- | --- |
| id | UNSIGNED BIGINT | ○ |  | ○ |  |
| name | VARCHAR(255) |  |  | ○ |  |
| created_at | TIMESTAMP |  |  |  |  |
| updated_at | TIMESTAMP |  |  |  |  |

---

## 7. purchasesテーブル

| カラム名 | 型 | PRIMARY KEY | UNIQUE KEY | NOT NULL | FOREIGN KEY |
| --- | --- | --- | --- | --- | --- |
| id | UNSIGNED BIGINT | ○ |  | ○ |  |
| user_id | UNSIGNED BIGINT |  |  | ○ | users(id) |
| item_id | UNSIGNED BIGINT |  | ○ | ○ | items(id) |
| payment_method | ENUM('コンビニ支払い', 'カード支払い') |  |  | ○ |  |
| postal_code | VARCHAR(8) |  |  | ○ |  |
| address | VARCHAR(255) |  |  | ○ |  |
| building | VARCHAR(255) |  |  |  |  |
| created_at | TIMESTAMP |  |  |  |  |
| updated_at | TIMESTAMP |  |  |  |  |

---

## 8. favoritesテーブル

| カラム名 | 型 | PRIMARY KEY | UNIQUE KEY | NOT NULL | FOREIGN KEY |
| --- | --- | --- | --- | --- | --- |
| id | UNSIGNED BIGINT | ○ |  | ○ |  |
| user_id | UNSIGNED BIGINT |  |  | ○ | users(id) |
| item_id | UNSIGNED BIGINT |  |  | ○ | items(id) |
| created_at | TIMESTAMP |  |  |  |  |
| updated_at | TIMESTAMP |  |  |  |  |

---

## 9. commentsテーブル

| カラム名 | 型 | PRIMARY KEY | UNIQUE KEY | NOT NULL | FOREIGN KEY |
| --- | --- | --- | --- | --- | --- |
| id | UNSIGNED BIGINT | ○ |  | ○ |  |
| user_id | UNSIGNED BIGINT |  |  | ○ | users(id) |
| item_id | UNSIGNED BIGINT |  |  | ○ | items(id) |
| content | VARCHAR(255) |  |  | ○ |  |
| created_at | TIMESTAMP |  |  |  |  |
| updated_at | TIMESTAMP |  |  |  |  |