# `ys_get_template_part`廃止計画

## ステータス

- 実装済み
- 自動検証済み
- 実環境での表示確認は未実施
- 作業ブランチ: `feature/remove-ys-get-template-part`

## 背景

yStandard独自の`ys_get_template_part()`と`\ystandard\Template::get_template_part()`を廃止し、WordPressコアの`get_template_part()`へ統一する。

既存メモは`docs/v5-dev.md`にあり、次の方針が記録されている。

- WordPressコアの`get_template_part()`へ統一する
- 既存の呼び出しをすべて置き換える
- 独自のファイルパス指定をコア関数で吸収できるか確認する
- v5リリースブロッカーとして扱う

メモの「約24ファイル33箇所」は現在のコードと一致しない。2026年8月19日時点では、テーマ内の利用は33ファイル43箇所である。

## 目的

- テンプレートパーツ読み込みをWordPressコアAPIへ一本化する
- WordPress 5.5以降で利用できる`$args`引き渡しをそのまま維持する
- 子テーマによる通常のテンプレート上書きを維持する
- 独自クラス、ラッパー関数、独自フックによる保守対象を削減する
- テンプレートの表示結果を変えずに移行する

## 対象外

- `template-parts/parts/`配下のファイル移動
- テンプレートパーツ内のHTML・CSS変更
- テンプレートパーツへ渡しているデータ構造の変更
- yStandard v4側の変更
- yStandard Blocks、yStandard Toolboxなど別リポジトリの変更

`template-parts/parts/`の整理は別変更とする。コア関数はサブディレクトリを含むスラグを扱えるため、今回同時に移動する必要はない。

## 現状調査

### 利用箇所

- `ys_get_template_part()`の呼び出し: 21ファイル27箇所
- `Template::get_template_part()`の直接呼び出し: 12ファイル16箇所
- 合計: 33ファイル43箇所
- `ys_get_template_part()`の定義: `inc/template-function/template.php`
- `Template::get_template_part()`の実装: `inc/template/class-template.php`

`ys_get_template_part()`からクラスメソッドを呼ぶ1箇所は、上記43箇所に含めない。

### 引数を渡す呼び出し

次の用途では、第3引数の`$args`を維持する必要がある。

- パンくずリスト
- 投稿者情報
- Google Analytics
- お知らせバー
- アーカイブとタクソノミーのヘッダー画像
- ブログカード
- 投稿ヘッダー画像
- 投稿日
- 前後記事
- 最近の投稿

WordPressコアの`get_template_part()`はWordPress 5.5以降で`$args`に対応している。テーマの最低対応バージョンはWordPress 6.5のため、独自処理なしで置き換えられる。

### 名前付きテンプレート

次の用途では、第2引数の`$name`を維持する必要がある。

- アーカイブ表示形式の`details-list.php`と`details-simple.php`
- Google Analyticsの`ga-gtag.php`と`ga-analytics.php`
- 最近の投稿における投稿タイプ別テンプレート候補

コア関数も`{$slug}-{$name}.php`、`{$slug}.php`の順で探索するため、現在のテーマ本体に存在するファイルは同じ優先順位で読み込める。

### 独自実装だけが持つ挙動

| 挙動 | 独自実装 | WordPressコア | 移行方針 |
|---|---|---|---|
| サブディレクトリを含むスラグ | 対応 | 対応 | そのまま置換 |
| `$name`による派生ファイル | 対応 | 対応 | そのまま置換 |
| `$args`の引き渡し | 対応 | 対応 | そのまま置換 |
| 子テーマによる上書き | 対応 | 対応 | 維持 |
| 投稿タイプ・タクソノミー別候補の自動追加 | 対応 | 非対応 | v5で廃止 |
| `ABSPATH`配下の絶対パス読み込み | 対応 | 非対応 | v5で廃止 |
| `ys_get_template_part_slug`フィルター | 対応 | 非対応 | v5で廃止 |
| `ys_get_template_part_name`フィルター | 対応 | 非対応 | v5で廃止 |
| `ys_get_template_part_args`フィルター | 対応 | 非対応 | v5で廃止 |
| コアのテンプレートパーツアクション | 一部対応 | 対応 | コア仕様へ統一 |

テーマ本体には、絶対パスを渡す呼び出し、3つの独自フィルターの登録、投稿タイプ・タクソノミー別の自動候補だけで選択されるテンプレートファイルは見つからなかった。通常表示の維持に独自挙動は不要である。

ただし、子テーマや外部プラグインが次の拡張へ依存している可能性はある。

- `archive-{post_type}.php`など、独自候補順を前提にした上書き
- 独自フィルターによるスラグ、名前、引数の変更
- プラグイン内テンプレートの絶対パス指定
- `ys_get_template_part()`または`Template::get_template_part()`の直接呼び出し

これらはv5の破壊的変更としてREADMEへ明記し、互換レイヤーは残さない。

## 実装方針

### 呼び出しの置換

- 43箇所のテーマ内呼び出しを`get_template_part()`へ置き換える
- 第2引数と第3引数は現在の値を維持する
- 動的スラグも値を変更せずコア関数へ渡す
- 出力バッファーを使っている箇所は現在の処理順を維持する
- テンプレートパーツのファイル名と配置は変更しない

### 独自APIの削除

- `inc/template-function/template.php`から`ys_get_template_part()`を削除する
- `inc/template/class-template.php`を削除する
- `inc/template/index.php`から`class-template.php`の読み込みを削除する
- 不要になる`Post_Type`参照、静的プロパティ、候補生成処理をまとめて削除する

### 廃止情報の記録

- READMEのv5.0.0変更履歴へ、廃止する関数とクラスメソッドを追記する
- READMEの「廃止されたフック」へ3つの独自フィルターを追記する
- `docs/v5-dev.md`の件数と進捗を実装結果に合わせて更新する
- コアの`get_template_part`アクションでは`$args`も渡されることを、移行時の注意点として記録する

## テスト方針

### 先に固定する回帰条件

- サブディレクトリ内のテンプレートを読み込める
- 名前付きテンプレートが汎用テンプレートより優先される
- 第3引数の値をテンプレート内の`$args`から参照できる
- 対象ファイルがない場合にエラーを出さず処理を継続する
- 子テーマ側に同じ相対パスがある場合は子テーマを優先する

コア関数自体の再テストではなく、yStandardが依存する呼び出し条件と代表的な出力を固定する。

### 自動チェック

- `rg`で`ys_get_template_part`と`Template::get_template_part`の残存がREADMEと移行メモ以外にないことを確認する
- 変更したPHPファイルへ`php -l`を実行する
- `npm run lint:php`を実行する
- 関連するPHPUnitテストを実行する
- `npm run test:php`で全体回帰を確認する
- `git diff --check`を実行する

PHPとドキュメントだけの変更になるため、CSS・JavaScriptのビルドは不要とする。実装中に生成対象のファイルへ変更が広がった場合だけ`npm run build`を追加する。

### 表示確認

自動テスト後、WordPress 6.9環境で次を確認する。

- トップページ、投稿一覧、検索結果、404
- カテゴリー、タグ、カスタム投稿タイプのアーカイブ
- 投稿、固定ページ、各ページテンプレート
- アーカイブのリスト表示とシンプル表示
- パンくずリスト、投稿者情報、アイキャッチ画像、前後記事
- 最近の投稿、お知らせバー、ブログカード
- Google Analyticsのgtag形式とanalytics形式
- 子テーマで上書きした代表的なテンプレートパーツ

ブラウザによる表示確認が必要になった場合は、実装後に確認目的と対象画面を提示し、許可を得てから実行する。

## 実装順序

- 現在のテンプレート選択と`$args`引き渡しを固定する回帰テストを追加する
- `ys_get_template_part()`の27箇所をコア関数へ置き換える
- `Template::get_template_part()`の16箇所をコア関数へ置き換える
- 独自ラッパー関数、クラス、ローダー登録を削除する
- READMEと`docs/v5-dev.md`へ破壊的変更と完了状況を記録する
- 静的チェック、PHPCS、PHPUnitを実行する
- 許可を得た環境で代表画面を確認する

## 完了条件

- テーマのPHPコードに`ys_get_template_part()`の定義と呼び出しが残っていない
- テーマのPHPコードに`Template::get_template_part()`の定義と呼び出しが残っていない
- `inc/template/class-template.php`が削除されている
- 43箇所がコアの`get_template_part()`へ移行している
- 名前付きテンプレート、`$args`、子テーマ上書きが維持されている
- 廃止する関数、クラスメソッド、独自フィルターがREADMEに記録されている
- PHPCSとPHPUnitが成功している、または今回の変更と無関係な既存失敗が明記されている
- 実環境での表示確認結果が、自動チェックと区別して記録されている

## 検証結果

- 変更したPHPファイルの`php -l`: 成功
- テンプレートパーツ回帰テスト: 4テスト6アサーション成功
- `git diff --check`: 成功
- `npm run lint:php`: 既存コードのPHPCS違反で失敗。今回の置換行に起因する新規違反はなし
- `npm run test:php`: 180テスト中、今回未変更の箇所に10エラー・5失敗あり。追加した回帰テストは成功
- ブラウザによる表示確認: 未実施

## 参考資料

- [get_template_part()](https://developer.wordpress.org/reference/functions/get_template_part/)
- [locate_template()](https://developer.wordpress.org/reference/functions/locate_template/)
- [load_template()](https://developer.wordpress.org/reference/functions/load_template/)
