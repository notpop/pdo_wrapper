# pdo_wrapper

詳しくはZenn記事を読んでいただければと思いますが不便だったのでPDOラッパーを書きました。<br><br>
https://zenn.dev/kishimoto/articles/82dccb02fc7ec3

## 前提/注意点
・自分の必要なだったものしか書いてないので必要に応じて追記してください。<br>
・データベースに応じてBaseModelが増えていく想定です。<br>
・テーブルの数に応じてModelが増えていく想定です。<br>
・物理削除のパターンはなかったので必要であれば追記してください。<br>
・queryを書くための効率化をする必要が出てくる可能性があります。<br>
・MIT License<br><br>

## 使い方
#### クエリ設定
クエリは手動で書く必要があります
```php
$database->setQuery($query);
```

#### バインド設定
```php
$database->setBind($key, $value);
$database->setBind($key, $value, $parameter);
$database->setBindInt($key, $value);
$database->setBindString($key, $value);
```

#### フェッチパターン設定
パターンの種類はPHPドキュメントをご確認ください
```php
$database->setFetchPattern($pattern);
```

#### 複数件取得に使用
```php
$database->select();
```
#### 単一データ取得に使用　※getName()的な関数を想定
```php
$database->selectOne();
```
#### 更新処理に使用
```php
$database->update();
```
#### 登録処理に使用
```php
$database->insert();
```
