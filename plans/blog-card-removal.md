# ブログカード設定・自動変換機能削除計画

## 目的

カスタマイザーの`[ys]ブログカード`設定と、URLのみの行をブログカードへ自動変換する機能をv5.0.0で削除する。

`[ys_blog_card]`ショートコード自体は維持し、テーマ本体のHTML、外部URLの情報取得、キャッシュ、専用テンプレート、専用CSSを使って引き続き描画する。yStandard Blocksの`Card_Block`へ描画を委譲する処理は削除し、テーマのショートコードとBlocksのカードブロックを明確に分離する。

ブログカード形式で独自表示するために追加されたEmbed関連処理に加え、`[ys]高速化`のoEmbed設定と最適化処理も含め、テーマ独自のEmbed関連機能はすべて削除する。

## ブランチ

- `feature/remove-blog-card`

## 現状調査

### カスタマイザー設定

`inc/blog-card/class-blog-card.php`が次を登録している。

- セクションID: `ys_blog_card`
- 表示名: `[ys]ブログカード`
- 設定ID: `ys_blog_card_create_card_auto`
- 設定内容: URLのみの行をブログカード形式へ自動変換する

`inc/customizer/class-customizer.php`には、廃止予定のコメント付きで`ys_blog_card`の優先度`910`が残っている。

### テーマ本体のブログカード機能

`inc/blog-card/class-blog-card.php`は、設定画面以外に次の機能を持つ。

- `[ys_blog_card]`ショートコードの登録とHTML生成
- URLのみの行を検出するWordPress Embedハンドラー
- 内部投稿のタイトル、抜粋、アイキャッチ取得
- 外部URLへのHTTPリクエストとtitle、description、OGP画像の抽出
- ブログカードデータのキャッシュ
- エディター内でのブログカード展開
- yStandard Blocksの`Card_Block`が存在する場合の描画委譲

表示HTMLは`template-parts/parts/blog-card.php`、スタイルは`src/styles/components/blog-card/_blog-card.scss`で管理されている。ブログカードCSSはフロント、ブロックエディター、クラシックエディター、Embed用CSSへ含まれている。

### Embed関連

`inc/blog-card/class-embed.php`はWordPressのEmbed表示をテーマ独自のブログカードで置き換えるために追加された処理であり、次のファイルと連動している。

- `inc/template-function/embed.php`
- `template-parts/parts/embed.php`
- `src/styles/embed.scss`
- `css/embed.css`

カスタマイザー`[ys]高速化`内には`ys_option_disable_wp_oembed`があり、`inc/optimization/class-optimization.php`がoEmbedの検出リンクとスクリプトを停止している。今回はEmbed関連をすべて削除する方針のため、この設定と最適化処理も削除対象に含める。

### キャッシュ管理

`inc/admin/class-admin-menu.php`にはブログカードキャッシュの件数表示、個別削除、削除完了メッセージがある。`[ys_blog_card]`を維持するため管理UIも残すが、Blocks有効時にキャッシュキーを差し替える旧連携は削除する。

### yStandard Blocksとの境界

yStandard Blocksのカードブロックは、テーマのブログカードとは別に次を持っている。

- `ystdb/card`ブロック
- `[ystdb_card]`ショートコード
- カード専用HTMLとCSS
- 外部URLデータの取得と独自キャッシュ

v5テーマではカードブロックのCSSをBlocks側のブロックメタデータから読み込むため、テーマの`.ys-blog-card`用CSSと`ystdb/card`の表示は互いに依存しない。テーマ側のCSSは`[ys_blog_card]`のために維持する。

一方、Blocks側には旧テーマのブログカード連携用として、次のフック登録が残っている。

- `ys_editor_blog_card_embed_css`
- `ys_cache_count_key__blog_card`
- `ys_cache_delete_key__blog_card`

テーマ側のエディター用Embed処理とキャッシュキー置換フックを削除すると、これらは呼び出されないフックになる。テーマ削除の実装をBlocks側の変更へ広げず、Blocksリポジトリでは別作業として不要な互換フックを整理する。

## 削除方針

### 削除する機能

- カスタマイザー`[ys]ブログカード`セクション
- `ys_blog_card_create_card_auto`の設定UIと値参照
- URLのみの行をブログカードへ変換するEmbedハンドラー
- エディター内の旧ブログカード展開
- yStandard Blocksの`Card_Block`へ旧ショートコードの描画を委譲する処理
- ブログカード形式で出力するためだけに存在するテーマ独自Embedテンプレート
- Embed用CSSとRocket Lazy Load向けの補助処理
- `[ys]高速化`内のoEmbed設定と`ys_option_disable_wp_oembed`の値参照
- oEmbedの検出リンクとスクリプトを停止する最適化処理
- yStandard Blocksのキャッシュへ切り替える旧テーマ連携フック

### 維持する機能

- テーマの`[ys_blog_card]`ショートコード
- テーマによる内部投稿と外部URLのブログカード情報取得
- テーマのブログカードキャッシュとキャッシュ管理UI
- `template-parts/parts/blog-card.php`と`.ys-blog-card`用CSS
- yStandard Blocksの`ystdb/card`ブロック
- yStandard Blocksの`[ystdb_card]`ショートコード
- テーマ共通のキャッシュ基盤と、ブログカード以外のキャッシュ管理UI

テーマ独自EmbedテンプレートとoEmbed停止処理を削除した後は、WordPressコアの標準動作へ戻す。`[ys_blog_card]`の処理からもEmbed判定とエディター用展開を削除し、明示的に入力されたショートコードだけを処理する。

この変更に伴い、旧`Embed`クラスが追加していたRocket Lazy Load向けの除外属性と、ブロックエディター内のEmbed高さ調整も削除する。どちらもテーマ独自ブログカード表示を支える補助処理であり、WordPressコアのEmbed表示へ戻した後は引き継がない。

## 互換性方針

### 既存設定値

データベース内の`ys_blog_card_create_card_auto`と`ys_option_disable_wp_oembed`は積極的に削除しない。設定UIと参照処理を削除し、保存値が残っていても動作へ影響しない状態にする。

### 既存ショートコード

`[ys_blog_card]`と既存の属性は維持する。既存コンテンツの書き換えは不要とし、yStandard Blocksが有効な場合もテーマ本体のブログカードHTMLを出力する。

URLのみの行から`[ys_blog_card]`へ自動変換する処理は廃止するため、ショートコードを明示的に入力していない既存のURL単独行はWordPress標準の処理へ戻る。

### 既存キャッシュ

テーマの`[ys_blog_card]`が使用する既存キャッシュキーと有効期限は維持し、保存済みキャッシュも引き続き利用する。yStandard Blocksのキャッシュキーへ置き換える連携は削除し、テーマ設定のキャッシュ管理画面ではテーマ側のブログカードキャッシュだけを管理する。

## 実装対象

### PHP

- `inc/blog-card/class-blog-card.php`からカスタマイザー登録、自動変換、Embed判定、エディター用展開、`Card_Block`への委譲を削除する
- `inc/blog-card/class-blog-card.php`のショートコード登録、属性、データ取得、キャッシュ、テンプレート出力は維持する
- `inc/blog-card/class-embed.php`を削除する
- `inc/blog-card/index.php`から`class-embed.php`の読み込みを削除する
- `inc/customizer/class-customizer.php`から`ys_blog_card`の優先度定義を削除する
- `inc/admin/class-admin-menu.php`のブログカードキャッシュ行は維持する
- `inc/admin/class-admin-menu.php`からBlocksのキャッシュキーへ置き換える`ys_cache_count_key__blog_card`と`ys_cache_delete_key__blog_card`の適用経路を削除し、テーマの`Blog_Card::CACHE_KEY`を管理する
- `inc/template-function/index.php`から`embed.php`の読み込みを削除する
- `inc/template-function/embed.php`を削除する
- `template-parts/parts/embed.php`を削除する
- `inc/optimization/class-optimization.php`から`optimize_oembed()`のアクション登録とメソッドを削除する
- `inc/optimization/class-optimization.php`から`add_oembed_section()`の呼び出しとメソッドを削除する

実装後は、テーマ内にEmbed用クラス、テンプレート関数、テンプレート、設定、最適化処理が残らないことを確認する。WordPressコア自体のEmbed処理を停止または置き換える代替処理は追加しない。

`add_theme_support( 'responsive-embeds' )`、コアEmbedブロックのレスポンシブ表示調整、Googleマップの比率調整など、通常コンテンツのWordPress標準Embedを表示するための既存処理は旧ブログカードのEmbed機能ではないため維持する。

### SCSSと生成CSS

- `src/styles/components/blog-card/_blog-card.scss`と各エディター向け読み込みは維持する
- `src/styles/embed.scss`を削除する
- `npm run build`で生成CSSを更新し、`css/embed.css`を削除する
- 生成後のフロント、ブロックエディター、クラシックエディターCSSに`.ys-blog-card`が残ることを確認する

### ドキュメント

- READMEのv5.0.0変更履歴へブログカード設定、自動変換、Embed関連機能の削除を追加する
- READMEの廃止オプションへ`ys_blog_card_create_card_auto`と`ys_option_disable_wp_oembed`を追加する
- READMEへ`[ys_blog_card]`ショートコードを維持することを明記する
- READMEの廃止された関数・クラスへ`\ystandard\Embed`、`ys_embed_content()`、`Blog_Card::embed_register_handler()`、`Blog_Card::blog_card_handler()`、`Blog_Card::get_admin_blog_card()`、`Blog_Card::customize_register()`を追加する
- READMEの廃止されたフックへブログカード固有フックを追加する
- `docs/v5-dev.md`の現在作業、進捗、互換性方針を更新する
- `plans/customizer-design-settings-reorganization.md`の最終表示順から`[ys]ブログカード`を削除する
- v4の履歴である`docs/release-note/v4.md`は変更しない

廃止フックとして記録する対象は次の通り。

- `ys_use_blogcard`
- `ys_use_blogcard_admin`
- `ys_use_ystdb_card`
- `ys_editor_blog_card_embed_css`
- `ys_cache_count_key__blog_card`
- `ys_cache_delete_key__blog_card`

## テスト方針

### PHPUnit

既存の`CustomizerTest`を拡張するか、ブログカード削除用テストを追加して次を固定する。

- カスタマイザーに`ys_blog_card`セクションが登録されない
- `ys_blog_card_create_card_auto`設定が登録されない
- `[ys_blog_card]`ショートコードが登録される
- yStandard Blocksが無効でも既存属性からテーマのブログカードHTMLを出力できる
- `ys_blog_card`Embedハンドラーが登録されない
- `ys_optimize_oembed`セクションと`ys_option_disable_wp_oembed`設定が登録されない
- oEmbedの検出リンクとスクリプトを停止するテーマ独自処理が登録されない
- yStandard Blocks有効時も`[ys_blog_card]`が`Card_Block`へ描画を委譲しない
- ブログカードキャッシュの件数確認と削除がテーマの`Blog_Card::CACHE_KEY`を使用する

管理画面キャッシュ一覧は、ブログカード用削除タイプが残り、Blocksのキャッシュキーへ置き換わらないことを確認する。

### 静的チェックとビルド

- 変更したPHPファイルへ`php -l`を実行する
- `npm run lint:php`を実行する
- 関連するPHPUnitテストを実行する
- `npm run test:php`で全体回帰を確認する
- `npm run build`を実行する
- `git diff --check`を実行する

### 残存確認

実装完了後、次がテーマ本体に残っていないことを`rg`で確認する。

- `ys_blog_card_create_card_auto`
- `embed_register_handler`
- `blog_card_handler`
- `get_admin_blog_card`
- `ys_use_blogcard`
- `ys_use_blogcard_admin`
- `ys_use_ystdb_card`
- `ys_editor_blog_card_embed_css`
- `ys_embed_content`
- `\ystandard\Embed`
- `ys_option_disable_wp_oembed`
- `optimize_oembed`
- `add_oembed_section`
- `ys_optimize_oembed`

`ys_blog_card`、`.ys-blog-card`、`Blog_Card`、ブログカード用テンプレートとCSSは維持対象のため、残存を許容する。

## 表示確認

ブラウザ確認が必要な場合は実装後に別途許可を得る。確認する場合の対象は次の通り。

- カスタマイザーに`[ys]ブログカード`が表示されない
- 投稿本文のURL単独行がテーマ独自ブログカードへ変換されない
- 既存の`[ys_blog_card]`がテーマ標準のブログカードとして表示される
- yStandard Blocks有効時も`[ys_blog_card]`のHTMLがBlocks側へ切り替わらない
- yStandard Blocksのカードブロックがフロントとエディターで従来どおり表示される
- カスタマイザー`[ys]高速化`にoEmbed設定が表示されない
- WordPress標準のEmbed処理をテーマが停止または置換していない
- テーマ設定のブログカードキャッシュ行でテーマ側のキャッシュ件数確認と削除が動作する

## 実装順序

- ブログカード設定とEmbedハンドラーが登録されず、ショートコードが維持されることを示すテストを追加する
- `Blog_Card`から自動変換、Embed、Blocksへの描画委譲を削除する
- テーマ独自Embedクラス、テンプレート、テンプレート関数を削除する
- `[ys]高速化`からoEmbed設定と最適化処理を削除する
- カスタマイザー優先度とBlocks向けキャッシュキー置換処理を削除する
- Embed用SCSSと生成CSSを削除し、ブログカード本体のCSSを維持する
- ビルドして生成CSSを検証する
- README、v5開発計画、カスタマイザー整理計画を更新する
- 静的チェック、PHPUnit、ビルド、残存検索を実行する

## 完了条件

- カスタマイザーに`[ys]ブログカード`と関連設定が表示されない
- テーマ本体がURL単独行をブログカードへ自動変換しない
- テーマ本体で`[ys_blog_card]`が登録され、既存属性と表示を維持する
- `[ys_blog_card]`がyStandard Blocksの`Card_Block`へ描画を委譲しない
- ブログカードの外部HTTP取得、キャッシュ、PHPテンプレート、CSSが維持されている
- テーマ独自Embedテンプレートへの置き換えがなくなり、WordPressコアのEmbed表示へ戻っている
- oEmbed設定とテーマ独自の停止処理が削除されている
- yStandard Blocksのカードブロックが削除対象に巻き込まれていない
- 廃止オプション、API、フックとショートコード維持方針がREADMEに記録されている
- 関連テスト、PHPCS、全体PHPUnit、ビルド、`git diff --check`が完了している

## 対象外

- yStandard Blocksのカードブロックと`[ystdb_card]`ショートコードの削除
- yStandard Blocksリポジトリに残る旧テーマ連携フックの整理
- `[ys_blog_card]`の属性、HTML、デザインの変更
- WordPress標準Embedブロック、`responsive-embeds`テーマサポート、Googleマップのレスポンシブ表示処理の削除
- DB内の旧設定値の一括削除
- v4リリースノートの書き換え
