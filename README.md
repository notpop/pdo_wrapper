# pdo_wrapper

詳しくはZenn記事を読んでいただければと思いますが不便だったのでPDOラッパーを書きました。<br><br>

## 前提/注意点
・自分の必要なだったものしか書いてないので必要に応じて追記してください。<br>
・データベースに応じてBaseModelが増えていく想定です。<br>
・テーブルの数に応じてModelが増えていく想定です。<br>
・物理削除のパターンはなかったので必要であれば追記してください。<br>
・queryを書くための効率化をする必要が出てくる可能性があります。<br>
・MIT License<br>

## 使い方

```php
// $query作成は手動で行ってください
$query = '';

// クエリ設定
$database = $this->database;
$database->setQuery($query);

// バインド設定
$database->setBind($key, $value);
$database->setBindInt($key, $value);
$database->setBindString($key, $value);

// フェッチパターン設定
// パターンの種類はPHPドキュメントをご確認ください
$database->setFetchPattern($pattern);

// 複数件取得に使用
$database->select();
// 単一データ取得に使用　※getName()的な関数を想定
$database->selectOne();
// 更新処理に使用
$database->update();
// 登録処理に使用
$database->insert();
```
