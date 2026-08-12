# WordPress 7.1対応と投稿メタUI移行方針

調査日: 2026-08-09

## 結論

WordPress 7.1対応として、yStandardの従来メタボックスが直ちに動作しなくなるわけではない。WordPress 7.1では投稿エディターが常時iframe化される一方、従来メタボックスは編集キャンバス外の互換領域で引き続き表示される。

ただし、現在のメタボックスを維持し続ける判断は推奨しない。以下の方針で、保存済みデータを変えずに編集UIだけをブロックエディター標準のドキュメント設定パネルへ移行する。

- 既存の11個の投稿メタキーは変更しない
- 本文へ挿入する独自ブロックは作らない
- `register_post_meta()`で既存メタをREST APIへ公開する
- `PluginDocumentSettingPanel`で「投稿設定」「SEO設定」「SNS設定」を再構築する
- クラシックエディター向けには既存メタボックスを`__back_compat_meta_box`として残す
- `[ys]パーツ`のショートコード表示もドキュメント設定パネルへ移す
- エディター内の保存はWordPressの投稿保存フローへ統合し、独自nonce付きメタボックス送信から分離する

これは「メタボックスを本文ブロックへ置き換える」のではなく、「投稿全体の設定をブロックエディター標準UIへ移す」設計である。投稿全体に効く設定を本文ブロックとして保存すると、ブロックの削除・複製・複数配置による不整合やコンテンツロックインが生じるため採用しない。

## WordPress 7.1で確定した変更

WordPress 7.1の最終リリース予定日は2026年8月19日で、2026年8月5日にField GuideとRC1が公開されている。

投稿エディターはWordPress 7.1から常時iframe化される。テーマがクラシックテーマかブロックテーマか、登録済み・使用中ブロックのBlock APIバージョンがいくつかに関係なくiframeが使われる。

従来メタボックスが存在する場合もiframe化される。WordPress 6.7で導入された分割表示により、編集キャンバスと従来メタボックスは別領域として共存するため、PHPだけで構成された一般的なメタボックスは引き続き利用できる。

したがって、対応の緊急度は次のように分ける。

| 判定 | 内容 |
| --- | --- |
| リリース前の必須確認 | WordPress 7.1上で投稿編集、メタ保存、エディターCSSを確認する |
| 互換性のための即時必須改修 | 現時点ではなし。現在のメタボックスは互換表示される |
| 製品として優先度の高い改修 | メタボックスをエディター標準サイドバーへ移行する |
| 将来対応 | 共同編集、ビジュアルリビジョン、メタのリビジョン対応を別途評価する |

WordPress 7.1ではリアルタイム共同編集は最終的に同梱されなかった。ただし、従来メタボックスは共同編集など新しい編集フローの互換性上の制約になっているため、移行を先送りする理由にはならない。

## 公式情報

- [WordPress 7.1 Field Guide](https://make.wordpress.org/core/2026/08/05/wordpress-7-1-field-guide/)
- [Iframed Editor Changes in WordPress 7.1](https://make.wordpress.org/core/2026/08/03/iframed-editor-changes-in-wordpress-7-1/)
- [Post Editor iframing with meta boxes in WordPress 6.7](https://make.wordpress.org/core/2024/10/18/post-editor-iframing-with-meta-boxes-in-wordpress-6-7/)
- [Migrating Blocks for iframe Editor Compatibility](https://developer.wordpress.org/block-editor/reference-guides/block-api/block-api-versions/block-migration-for-iframe-editor-compatibility/)
- [Meta Boxes](https://developer.wordpress.org/block-editor/how-to-guides/metabox/)
- [PluginDocumentSettingPanel](https://developer.wordpress.org/block-editor/reference-guides/slotfills/plugin-document-setting-panel/)
- [register_meta()](https://developer.wordpress.org/reference/functions/register_meta/)
- [Enqueueing assets in the Editor](https://developer.wordpress.org/block-editor/how-to-guides/enqueueing-assets-in-the-editor/)

## yStandardの現状

このリポジトリは独自ブロックを持たないWordPressクラシックテーマである。`block.json`と`theme.json`はなく、コアブロックのスタイル追加とブロックエディター用CSSをテーマ側で提供している。

投稿メタの編集UIは`inc/content/class-post-meta.php`へ集約され、公開投稿タイプから`attachment`と`ys-parts`を除いた投稿タイプへ3つのメタボックスを表示している。

| メタボックス | メタキー | 値 | 現在の表示条件 |
| --- | --- | --- | --- |
| SEO設定 | `ys_noindex` | `1`または未保存 | 対象投稿タイプ共通 |
| SEO設定 | `ys_hide_meta_dscr` | `1`または未保存 | 対象投稿タイプ共通 |
| SNS設定 | `ys_ogp_title` | 文字列または未保存 | 対象投稿タイプ共通 |
| SNS設定 | `ys_ogp_description` | 文字列または未保存 | 対象投稿タイプ共通 |
| 投稿設定 | `ys_hide_ad` | `1`または未保存 | 対象投稿タイプ共通 |
| 投稿設定 | `ys_hide_toc` | `1`または未保存 | 対象投稿タイプ共通 |
| 投稿設定 | `ys_hide_share` | `1`または未保存 | 対象投稿タイプ共通 |
| 投稿設定 | `ys_hide_publish_date` | `1`または未保存 | 対象投稿タイプ共通 |
| 投稿設定 | `ys_hide_author` | `1`または未保存 | 対象投稿タイプ共通 |
| 投稿設定 | `ys_hide_related` | `1`または未保存 | `post`のみ |
| 投稿設定 | `ys_hide_paging` | `1`または未保存 | `post`のみ |

これらの値はフロント側の広告、目次、シェアボタン、日付、著者、関連記事、ページング、noindex、meta description、OGP出力から直接参照されている。保存キーを維持すれば、フロント側の読み取り処理と既存サイトのデータ移行は不要である。

`inc/parts/class-parts.php`には、`ys-parts`投稿のショートコードを表示・コピーする情報用メタボックスが1つある。投稿メタは保存していないが、従来メタボックスを完全になくすには同時移行が必要である。

## 現状から見つかった対応点

### 投稿メタがREST APIへ登録されていない

現在は`save_post`と`$_POST`を使って保存している。ブロックエディター標準のデータストアから読み書きするには、投稿タイプごとに`register_post_meta()`で登録し、`show_in_rest`を有効にする必要がある。

REST APIで登録メタを利用する投稿タイプには`custom-fields`サポートも必要になる。標準投稿・固定ページだけでなく、`ys_get_meta_box_post_types`で追加される公開カスタム投稿タイプについて、以下を判定する必要がある。

- ブロックエディターを使用するか
- `show_in_rest`が有効か
- `custom-fields`をサポートしているか
- yStandardが`custom-fields`サポートを追加してよいか

無条件に全公開投稿タイプへ`custom-fields`サポートを追加すると、他プラグインの投稿タイプやREST公開範囲へ影響する。新UIの標準対象は`post`と`page`にし、その他の既存対象投稿タイプは条件を満たす場合だけ新UIへ移す。条件を満たさない投稿タイプでは従来メタボックスを残し、機能を失わせない。既存フィルターには、新UI対象を明示できる互換的な引数または別フィルターを追加する。

### 保存値の型が未定義

現在のチェックボックス値は文字列`'1'`で、未チェック時はメタ行を削除している。REST APIでは型を明示する必要がある。

| 対象 | 登録型 | 初期値 | サニタイズ |
| --- | --- | --- | --- |
| 9個の表示切り替え | `boolean` | `false` | 真偽値へ正規化 |
| `ys_ogp_title` | `string` | 空文字 | `sanitize_text_field()` |
| `ys_ogp_description` | `string` | 空文字 | 既存仕様に合わせてHTMLと改行を除去 |

既存の`'1'`はREST応答で`true`として扱い、フロント側では従来どおり`Utility::to_bool()`で読める。保存後に`false`や空文字のメタ行が残っても出力結果は変わらないため、初回実装では空値削除のための独自REST処理を追加しない。

### 現在の保存処理にサニタイズ上の改善余地がある

`save_post_checkbox()`は`$_POST`の値を直接保存している。`save_post_text()`は保存時に`esc_attr()`を使い、入力サニタイズと出力エスケープの責務が混在している。また、`wp_unslash()`も行っていない。

REST登録時に型、`sanitize_callback`、`auth_callback`を定義し、クラシックエディター向けの既存保存処理も同じサニタイズ関数を使う形へ統一する。

### 外部拡張用フックの互換性がある

現在は以下のフックが公開されている。

- `ys_get_meta_box_post_types`
- `ys_meta_box_seo`
- `ys_meta_box_sns`
- `ys_meta_box_post`
- `ys_save_post_meta_seo`
- `ys_save_post_meta_sns`
- `ys_save_post_meta_post`

HTML出力用の`ys_meta_box_*`はクラシックエディターでは維持できるが、Reactで構築するブロックエディター側へPHPの任意HTMLをそのまま移植できない。

移行時は次の互換方針を採用する。

- 既存フックは削除せず、クラシックエディター用メタボックス内で維持する
- ブロックエディター向けには`ys_block_editor_post_meta_fields`のようなフィールド定義フィルターを新設する
- 独自UIが必要な拡張には、独自の`PluginDocumentSettingPanel`登録を案内する
- `ys_save_post_meta_*`はREST保存後にも発火させる互換ブリッジを設ける。ただし`$_POST`依存は非推奨として明記する

### 管理画面アセットの読み込み範囲が広い

`inc/admin/class-admin.php`はメタボックスCSS、管理画面JavaScript、メディアアップローダーを全管理画面へ読み込んでいる。今回のUI移行後は、投稿編集画面、テーマ設定画面、`ys-parts`一覧など用途ごとに読み込み条件を分ける。

`ys-parts`のコピー処理は`document.execCommand( 'copy' )`を使用している。これはiframe移行の直接的な破壊点ではないが、エディター標準パネルへの移行時はClipboard APIを優先し、HTTP環境などClipboard APIを利用できない場合だけ入力選択によるコピーへフォールバックする。どちらの経路でもアクセシブルな完了通知を表示する。

### エディターCSSは別軸で確認が必要

yStandardは`add_editor_style()`、`enqueue_block_assets`、`wp_enqueue_block_style()`を使っている。これらは常時iframe化に対応できる標準経路であり、静的調査では大きな設計変更は不要と判断する。

ただし、以下はWordPress 7.1上で表示確認する。

- `css/block-editor.css`と`style.css`がiframe内へ読み込まれること
- `css/block-editor-assets.css`のCSSカスタムプロパティがiframe内で有効なこと
- `*-editor.css`が対象ブロックと一緒にiframe内へ読み込まれること
- `.editor-post-title__block`など古いエディターDOMに依存したセレクターが残っていないか
- 全幅・幅広ブロック、見出し、画像、ギャラリー、ボタン、テーブルの表示がフロントと一致すること

yStandard自身は編集キャンバスへ触るJavaScriptやBlock API v2以下の独自ブロックを持たないため、WordPress 7.1のiframe強制によるJavaScript破損リスクは低い。

## 推奨アーキテクチャ

### データ層

投稿メタの定義を1か所へ集約する。定義にはキー、型、初期値、サニタイズ、表示対象、パネル、ラベル、説明を持たせる。

PHP側は同じ定義から以下を行う。

- 対象投稿タイプごとの`register_post_meta()`
- クラシックエディター用メタボックスの描画
- クラシックエディター保存時のサニタイズ
- ブロックエディターへ渡すフィールド定義の生成

保存経路は次の構成になる。

| 編集環境 | UI | 保存経路 | 既存データ |
| --- | --- | --- | --- |
| ブロックエディター | `PluginDocumentSettingPanel` | Core DataからREST API | 同じメタキーを読み書き |
| クラシックエディター | 従来メタボックス | nonce確認後の`save_post` | 同じメタキーを読み書き |
| フロントエンド | 変更なし | `Content::get_post_meta()` | 同じメタキーを参照 |

データベース一括変換や、既存投稿を開いて再保存する作業は不要である。

### UI層

1つのエディタープラグインを登録し、ドキュメント設定サイドバーに3パネルを表示する。

| パネル | コントロール |
| --- | --- |
| `[ys] 投稿設定` | 7個の`ToggleControl`。関連記事とページングは`post`だけ表示 |
| `[ys] SEO設定` | 2個の`ToggleControl` |
| `[ys] SNS設定` | `TextControl`と`TextareaControl` |

`ys-parts`では上記3パネルを表示せず、公開済み投稿にだけ`[ys] ショートコード`パネルを表示する。コピー操作にはボタンの目的が分かるラベル、キーボード操作、コピー完了の通知を付け、Clipboard APIを利用できないHTTP環境でもコピーできるようにする。

本文ブロック、Block Bindings、ブロック属性の`meta`ソースは使用しない。Block Bindingsは投稿メタを本文中の見出し・段落・画像などへ結び付ける用途であり、今回のドキュメント設定UIには適さない。ブロック属性の`meta`ソースも非推奨である。

### レガシーUI

新UIを利用できる投稿タイプの既存メタボックスには`__back_compat_meta_box => true`を指定する。これにより、ブロックエディターでは非表示になり、クラシックエディターでは従来どおり表示できる。

RESTまたは`custom-fields`の条件を満たさず新UIを利用できないカスタム投稿タイプでは、通常の互換メタボックスとして表示を継続する。投稿タイプごとに`add_meta_box()`を登録し、同じメタボックス配列へ一括で`__back_compat_meta_box`を指定しない。

移行直後に既存メタボックスのPHPを削除しない。最低サポートWordPressが6.1であり、Classic Editor利用サイトやプラグイン連携を保護する必要があるためである。

### JavaScriptビルド

JavaScriptのビルドは、既存のBabel CLI構成から`@wordpress/scripts`へ移行する。SassとPostCSSによるCSSビルドは今回の移行対象に含めず、現在のコマンドと出力先を維持する。

WordPress公式設定を継承する`webpack.config.js`を追加し、現在の7つのブラウザ向けJavaScriptと、新しい投稿メタUIを明示的なエントリーとして登録する。出力先は既存PHPとの互換性を保つため`js/`のままとし、既存のファイル名とディレクトリ構造を変更しない。

- `src/js/ystandard.js`から`js/ystandard.js`
- `src/js/admin/*.js`から`js/admin/*.js`
- `src/js/block-editor/post-meta.js`から`js/block-editor/post-meta.js`

webpackの出力先が既存の`js/`であるため、`output.clean`は無効にする。ビルド対象として明示したファイルだけを更新し、他の生成物を暗黙に削除しない。

新しいエディターUIでは`@wordpress/*`パッケージをimportし、Dependency Extraction Webpack Pluginが生成する`js/block-editor/post-meta.asset.php`から依存ハンドルとバージョンを読み込む。依存配列をPHPへ二重管理しない。必要な主な依存は次のとおり。

- `wp-plugins`
- `wp-editor`
- `wp-components`
- `wp-data`
- `wp-core-data`
- `wp-element`
- `wp-i18n`
- `wp-notices`

既存の管理画面・フロント向けJavaScriptは、ビルド移行時に動作まで一度に書き換えない。現在PHPで指定している依存ハンドルと`filemtime()`によるバージョン管理を維持し、webpackで同等の成果物を生成できることを優先する。

翻訳対象文字列は`wp.i18n`を使い、`wp_set_script_translations()`でテキストドメイン`ystandard`を設定する。

`src/js/icons/brand.js`はブラウザ向けJavaScriptではなく、SVGスプライトを生成するNode.jsスクリプトである。webpackのエントリーには含めず、既存の`build:icons`として維持する。

移行後のnpm scriptsは次の責務にする。

| コマンド | 責務 |
| --- | --- |
| `npm run build:js` | `NODE_ENV=production`を明示した`wp-scripts build`による本番向けJavaScriptビルド |
| `npm run watch:js` | `NODE_ENV=development`を明示した`wp-scripts start`によるJavaScriptの監視ビルド |
| `npm run lint:js` | `wp-scripts lint-js`による新しいブロックエディター用JavaScriptの検査 |
| `npm run build:css`、`npm run watch:css` | 現在のSass・PostCSS構成を維持 |
| `npm run build:icons` | 現在のNode.jsスクリプトを維持 |

実行環境に設定された`NODE_ENV`によって本番成果物が非圧縮にならないよう、npm scriptsでは`cross-env`を使って環境を固定する。本番ビルドではソースマップを生成せず、配布zipへ含めない。

既存出力との同等性を確認した後、`.babelrc`と直接利用しなくなるBabel関連パッケージを削除する。コード全体の一括整形やCSSツールチェーンの変更は、この移行へ混在させない。既存JavaScriptには現在のLint規約に対する負債があるため、初回移行では新規ブロックエディター用コードを必須Lint対象とし、既存コードの一括整形は別変更に分ける。

## ファイル構成案

既存構造を崩さず、責務を次のように分ける。

| ファイル | 役割 |
| --- | --- |
| `inc/content/class-post-meta.php` | メタ定義、REST登録、クラシックUIと保存の互換処理 |
| `inc/block-editor/class-block-editor-post-meta.php` | 投稿編集画面へのスクリプト登録・設定受け渡し |
| `webpack.config.js` | WordPress公式webpack設定の継承、複数JSエントリーと既存出力先の定義 |
| `src/js/block-editor/post-meta.js` | ドキュメント設定パネルと各コントロール |
| `js/block-editor/post-meta.js` | 配布用ビルド成果物 |
| `js/block-editor/post-meta.asset.php` | WordPress依存ハンドルとビルドバージョン |
| `src/sass/admin-post-meta.scss` | 必要な場合だけ追加するエディターUI用CSS |
| `tests/test-post-meta.php` | メタ登録、型、権限、既存値互換のPHPUnitテスト |

専用CSSが不要なら`src/sass/admin-post-meta.scss`は作らず、WordPressコンポーネントの標準スタイルだけを使う。

## 配布テーマとしての責務分離

WordPress公式のテーマ要件では、独自ブロック、独自投稿タイプ、ショートコード、SEO設定、非デザイン機能はプラグイン領域とされる。yStandardの現状にはテーマ固有表示とサイト機能が混在している。

今回のWordPress 7.1対応で一度に分離すると既存利用者への影響が大きいため、UI移行とプラグイン分離は同時に行わない。ただし、長期の正本設計は次の境界にする。

| テーマに残す | コンパニオンプラグインへ移す候補 |
| --- | --- |
| 広告、目次、シェア、日付、著者、関連記事、ページングの表示制御 | noindex、meta description、OGPタイトル・説明 |
| コアブロックのスタイル、エディターCSS | `ys-parts`投稿タイプ、ショートコード、ウィジェット |

テーマ切り替え後も意味を持つSEO・SNSデータやコンテンツパーツは、最終的にはコンパニオンプラグイン所有にする方がデータ可搬性に優れる。分離時もメタキーを維持すれば、保存済みデータの移行は不要である。

参考:

- [What Is a Theme?](https://developer.wordpress.org/themes/getting-started/what-is-a-theme/)
- [WordPress.org Theme Requirements](https://make.wordpress.org/themes/handbook/review/required/)

## 実装の進め方

### JavaScriptビルド移行

- `@wordpress/scripts`を開発依存へ追加する
- WordPress公式設定を継承した`webpack.config.js`へ既存7ファイルを登録する
- `build:js`と`watch:js`を`wp-scripts`へ切り替える
- CSSをJavaScriptからimportせず、既存のSass・PostCSSコマンドを維持する
- 旧ビルドと新ビルドの出力ファイル一覧、構文、主要動作を比較する
- `lint:js`を追加し、移行対象JavaScriptのエラーを解消する
- 同等性確認後に`.babelrc`と不要なBabel関連パッケージを削除する

### WordPress 7.1互換性確認

- WordPress 7.1 RCで現状コードの投稿・固定ページ・`ys-parts`編集を確認する
- 11個の既存メタが保存・再読込できることを確認する
- ブロックエディターCSSと主要コアブロックを確認する
- PHPエラー、JavaScriptエラー、非推奨警告を記録する

この段階は現状把握であり、正本スクリーンショットを更新する場合やブラウザ系ツールを使う場合は、プロジェクトルールに従って別途了承を得る。

### 投稿メタ登録

- 11個のメタ定義を配列へ集約する
- `init`で対象投稿タイプごとに登録する
- `type`、`single`、`default`、`show_in_rest`、`sanitize_callback`、`auth_callback`を指定する
- カスタム投稿タイプはRESTと`custom-fields`サポートを検証してから対象にする
- クラシック保存処理も同じサニタイズ関数へ統一する

### エディター標準UI

- 3つの`PluginDocumentSettingPanel`を実装する
- Core Data経由で投稿メタを読み書きする
- 投稿タイプによる表示条件を実装する
- `ys-parts`のショートコードパネルを実装する
- 翻訳、ラベル、説明、キーボード操作、保存中状態を確認する

### レガシー互換

- 既存メタボックスを`__back_compat_meta_box`化する
- Classic Editorで従来UIと保存を確認する
- 既存PHPフックを維持する
- REST保存後の保存アクション互換ブリッジを追加する
- 新しいフィールド定義フィルターを追加し、利用方法を開発者向けに記録する

### アセット整理

- 投稿編集画面以外で不要なメタボックスCSSを停止する
- `ys-parts`コピー処理を新UIへ移す
- エディターCSSの古いDOMセレクターを確認し、必要な箇所だけ修正する
- 関係ない管理画面アセットの整理は別変更に分ける

## 検証方針

### 自動テスト

- `register_post_meta()`の登録内容と型
- `post`、`page`、対象外投稿タイプの登録差分
- 管理権限、編集権限、権限不足時のREST更新
- 既存の文字列`'1'`を真偽値として扱えること
- 空値、HTMLを含む入力、改行を含むOGP説明のサニタイズ
- 既存のnoindex、OGP、表示切り替え処理への回帰がないこと
- Classic Editor保存時のnonceと権限確認

実装後は最低限`npm run lint`、`npm run build`、`npm run test`を実行する。WordPress 7.1用のPlaygroundテストコマンドを追加する場合も、既存のWordPress 6.9テストを置き換えず併存させる。

JavaScriptビルド移行では、これに加えて`npm run lint:js`と`npm run build:js`を単独実行し、次を確認する。

- 既存7ファイルが同じパスへ生成される
- CSSファイルがwebpackから生成・変更されない
- `src/js/icons/brand.js`がwebpackへ混入しない
- 投稿メタUIの`*.asset.php`に必要なWordPress依存ハンドルが出力される
- 配布zipにJavaScriptと`*.asset.php`が含まれる
- 本番用ソースマップが配布zipに含まれない

### 編集画面の確認対象

| 環境 | 確認内容 |
| --- | --- |
| WordPress 6.1 | 最低サポートでスクリプトが構文エラーにならない |
| WordPress 6.9 | 現行に近い旧動作とレガシー互換 |
| WordPress 7.0 | 条件付きiframeでの動作 |
| WordPress 7.1 | 常時iframeでの標準動作 |
| Classic Editor | 従来メタボックスの表示・保存 |
| `post` | 全11項目の表示・保存 |
| `page` | 関連記事・ページングを除く9項目の表示・保存 |
| 公開カスタム投稿タイプ | REST、ブロックエディター、`custom-fields`条件の確認 |
| `ys-parts` | 公開状態に応じたショートコード表示とコピー |

## 完了条件

- 新UIへ移行できる投稿タイプでは、ブロックエディターにyStandard由来の従来メタボックスが表示されない
- 新UIの条件を満たさない既存対象投稿タイプでは、従来機能が失われず互換メタボックスを使用できる
- 既存11キーを変更せず、既存投稿の設定値が新UIへ反映される
- 新UIでの変更が通常の投稿保存・自動保存フローへ統合される
- フロント側の出力結果が移行前と一致する
- Classic Editorでは従来メタボックスが使用できる
- WordPress 7.1の常時iframe環境でエディターCSSと主要ブロック表示が崩れない
- 既存PHPフックの互換範囲と新しい拡張方法が明記される
- JavaScriptが`@wordpress/scripts`でビルドされ、CSSは既存のSass・PostCSS構成を維持する
- lint、build、PHPUnitが成功する

## 推奨する着手順

最初にJavaScriptビルドだけを`@wordpress/scripts`へ移行し、既存7ファイルの出力と動作を確定する。その後の実装単位は「11個の既存投稿メタ登録と3つのドキュメント設定パネル」に限定する。保存キーとフロント出力を変えず、`ys-parts`、外部拡張フック、アセット整理は同じブランチ内でも独立コミットに分ける。

WordPress 7.1リリース前に最低限の互換性確認を完了し、UI移行はWordPress 7.1専用コードにせず、最低サポートのWordPress 6.1から利用できる実装としてリリースする。
