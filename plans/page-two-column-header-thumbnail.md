# 固定ページの全幅アイキャッチ表示修正計画

## ブランチ

- `fix/page-two-column-header-thumbnail`

## 調査結果

WordPress 6.9のテスト環境で、次の条件を組み合わせて固定ページ全体を描画した。

- 固定ページ
- サイドバーありの2カラム
- アイキャッチ画像あり
- `ys_page_post_thumbnail_type`が`full`

通常状態では全幅アイキャッチが出力され、7アサーションが成功した。2カラムのレイアウト処理やCSSは直接の原因ではない。

本文ヘッダーを`ys_is_active_post_header_page`で無効化すると問題を再現し、全幅アイキャッチの`<figure class="site-header-thumbnail">`が出力されなかった。

原因は`Post_Singular_Thumbnail::is_active_post_thumbnail()`が、画像の有無や表示設定に加えて`Post_Header::is_active_post_header()`も確認していることにある。

全幅アイキャッチは`ys_after_site_header`からサイトヘッダー直下へ出力されるが、本文内の`.singular-header`と同じ表示判定を使っている。そのため、本文タイトルなどだけを非表示にした場合も全幅アイキャッチまで停止する。

## 修正方針

`inc/post-singular/class-post-singular-thumbnail.php`で、次の判定を分離する。

- アイキャッチ画像自体を利用できるか
  - 詳細ページか
  - アイキャッチ画像が設定されているか
  - 投稿タイプ別の「アイキャッチ画像を表示する」が有効か
  - `ys_is_active_post_thumbnail`フィルターで許可されているか
- 本文ヘッダー内へ表示できるか
  - 上記に加えて`Post_Header::is_active_post_header()`が有効か

画像自体の利用可否を確認する内部メソッドを用意し、表示場所ごとに次のように使い分ける。

- 通常タイプのアイキャッチ
  - 従来どおり本文ヘッダーが有効な場合だけ表示する
- 全幅タイプのアイキャッチ
  - 本文ヘッダーのフィルター結果には連動させず、画像自体の利用可否で表示する

## 既存挙動の維持

次の条件では、従来どおり全幅アイキャッチを表示しない。

- 固定ページをフロントページとして使用している
- 「投稿ヘッダーなし」テンプレートを使用している
- 投稿タイプ別のアイキャッチ表示設定が無効
- アイキャッチ画像が未設定
- `ys_is_active_post_thumbnail`または`ys_get_header_post_thumbnail`で無効化されている

通常タイプは`.singular-header`内へ出力するため、`ys_is_active_post_header_page`などで本文ヘッダーを無効化した場合は引き続き表示しない。

## 実装対象

- `inc/post-singular/class-post-singular-thumbnail.php`
- `tests/test-header-thumbnail.php`

テンプレートHTMLとSCSSは変更しない。

## テスト

`tests/test-header-thumbnail.php`へ固定ページの回帰テストを追加する。

- 2カラム・全幅タイプでアイキャッチを表示できる
- 本文ヘッダーを無効化しても全幅タイプは表示できる
- 本文ヘッダーを無効化した通常タイプは表示しない
- アイキャッチ表示設定が無効な場合は表示しない
- アイキャッチ未設定時は表示しない
- フロントページと「投稿ヘッダーなし」テンプレートでは表示しない

検証コマンドは次のとおり。

- `npm run test:php -- --filter Header_Thumbnail_Test`
- `php -l inc/post-singular/class-post-singular-thumbnail.php`
- 変更PHPファイルのPHPCS
- `git diff --check`
