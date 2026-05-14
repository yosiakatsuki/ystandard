# WordPress 7.0 対応調査

作成日: 2026-05-14

## 前提

yStandard は WordPress クラシックテーマです。テーマヘッダーでは `Requires at least: 6.1`、`Requires PHP: 7.4.0`、`Text Domain: ystandard` が指定されています。Composer の開発環境要件は PHP 8.0 以上です。

WordPress 7.0 は 2026-05-20 公開予定です。2026-05-14 時点では RC3 が公開済みで、公式スケジュール上は RC4 が 2026-05-14、リリースのドライランが 2026-05-19、正式リリースが 2026-05-20 です。Real-time Collaboration は 7.0 には含まれず、7.1 サイクルで再評価される予定です。

## テーマの関連実装

- テーマ基本サポートは `inc/init/class-init.php` に集約されています。`wp-block-styles`, `align-wide`, `responsive-embeds`, `custom-line-height`, `custom-units`, `custom-spacing`, `appearance-tools` を有効化しています。
- ブロックエディター用 CSS は `inc/block-editor/class-block-editor-assets.php` で `enqueue_block_assets` と `add_editor_style()` を使って読み込んでいます。
- カラーパレットとフォントサイズは `editor-color-palette`, `editor-font-sizes` の theme support で提供しています。
- `src/block-styles/` に Core ブロック向け Sass があり、Gallery, Cover, Image, Search, Button, Group, Columns, Table などの見た目をテーマ側で調整しています。
- `inc/block-editor/class-block-editor.php` で FSE 系ブロックを許可リストから除外しています。ここには `core/navigation` なども含まれています。
- 投稿編集画面では `inc/content/class-post-meta.php` が複数のクラシックメタボックスを追加し、`save_post` で投稿メタを保存しています。
- JS 読み込みでは `inc/enqueue/class-enqueue-utility.php` が `wp_script_add_data( $handle, 'defer', true )` / `async` を付与し、`inc/enqueue/class-enqueue-scripts.php` が `script_loader_tag` で属性を追加しています。
- jQuery 最適化機能は `inc/optimization/class-optimization.php` で CDN 差し替え、フッター移動、defer、無効化を行っています。

## 対応が必要な可能性が高い項目

### PHP 要件

WordPress 7.0 は PHP 7.2 / 7.3 のサポートを終了し、最小サポート PHP は 7.4.0 になります。yStandard のテーマヘッダーはすでに `Requires PHP: 7.4.0` のため、配布要件の変更は不要です。

ただし、7.0 対応ブランチでは PHP 7.4 / 8.0 / 8.1 / 8.2 / 8.3 での基本動作確認を行うのが安全です。公式情報では WordPress Core は PHP 8.0 から 8.3 に完全対応、8.4 / 8.5 は beta compatible とされています。

### ブロックエディターの iframe 化

WordPress 7.0 では、投稿内に実際に挿入されているブロックの API version を見て、すべて version 3 以上なら投稿エディターを iframe 化する方向に変わります。Gutenberg プラグイン側ではクラシックテーマにも iframe 強制の検証が進んでいますが、WordPress 7.0 Core では強制ではありません。

yStandard は `enqueue_block_assets` と `add_editor_style()` を使っているため基本方針は合っています。確認すべき点は次の通りです。

- `css/block-editor.css`, `css/block-editor-assets.css`, `style.css` が iframe 内の `.editor-styles-wrapper` で意図通り効くか。
- `src/sass/component/block-editor/` と `src/block-styles/*-editor.scss` のセレクタが iframe 化された投稿エディターでも崩れないか。
- `inc/block-editor/class-block-editor.php` の `disallow_block_types()` に未定義変数 `$block_editor_context` があり、`$editor_context` の typo に見えます。WP_DEBUG 有効時の notice や、投稿/ウィジェット判定の誤動作につながるため、7.0 対応とは別に優先修正候補です。

### クラシックテーマでの Font Library

WordPress 7.0 では Font Library がクラシックテーマにも拡張され、外観メニューに Fonts 画面が出る想定です。yStandard は独自にフォント設定をカスタマイザーで持っているため、次の確認が必要です。

- 外観 > Fonts が yStandard 有効時に表示され、UI が崩れないか。
- Font Library で追加したフォントがブロックエディターの Typography 設定に表示されるか。
- フロント表示で yStandard の CSS カスタムプロパティ、カスタマイザーのフォント設定、Core が出力するフォント指定が競合しないか。
- 必要なら、テーマ独自フォント設定と Font Library の役割を分けるヘルプ文言やドキュメントを用意する。

### ブロック表示制御

WordPress 7.0 ではブロックを desktop / tablet / mobile ごとに表示・非表示にする `blockVisibility` metadata が追加されます。viewport 非表示は DOM に残り、CSS で非表示になります。

yStandard 側でサーバーサイドにブロック markup を解析・変換する処理がなければ大きな修正は不要です。ただし、テーマ CSS ではレスポンシブ表示制御を多く扱うため、次を確認します。

- ブロック表示制御の Core CSS と、テーマ側の `.is-*`, `.align*`, `.wp-block-*` 系スタイルが競合しないか。
- ユーザーがモバイル非表示にしたブロックが、テーマ CSS によって再表示されないか。
- 7.1 以降で `theme.json` による breakpoints 連携が予定されているため、yStandard 独自のブレークポイント設計との関係を継続確認する。

### 個別ブロック Custom CSS

WordPress 7.0 では、`edit_css` 権限を持つユーザーが個別ブロックに Custom CSS を追加できるようになります。CSS はブロックの `style` 属性内の `css` キーに保存され、フロントでは `has-custom-css` と `wp-custom-css-*` クラスが付与されます。

yStandard 側で直接の実装修正は不要な見込みですが、次を検証します。

- `.ystd` 配下のテーマ CSS と `wp-custom-css-*` の優先順位がユーザー期待に反しないか。
- 個別 CSS が入った Heading, Button, Group, Image, Gallery などで、テーマのブロックスタイルが破綻しないか。
- `edit_css` 権限を持たないユーザーで Custom CSS が保存・出力されないことを確認する。

### Core ブロック追加・更新

WordPress 7.0 では Icon ブロックと Breadcrumbs ブロックが追加され、Gallery の lightbox、Cover の外部動画、Grid の controls などが更新対象です。

yStandard には独自のパンくず実装があります。Core Breadcrumbs ブロックを使うユーザーが増える可能性があるため、次の判断が必要です。

- `core/breadcrumbs` は yStandard 独自のパンくず表示と重複するため、FSE 系ブロックとして `inc/block-editor/class-block-editor.php` の除外リストに追加する。
- `core/icon` は表示確認の結果、テーマ側の追加スタイルなしで現状維持とする。
- yStandard 独自パンくずと Core Breadcrumbs ブロックを同時に表示した場合の重複をドキュメント化するか、テーマ側で案内を出すか。
- Gallery / Cover / Grid / Image / Search / Button は既存 Sass の影響範囲が大きいため、7.0 RC でエディターとフロントの表示確認を行う。

### Navigation Overlay

WordPress 7.0 の Customizable Navigation Overlays は Navigation ブロックと Site Editor 向けの機能です。yStandard はクラシックテーマで、`core/navigation` を FSE 系ブロックとして除外しています。

現状のままなら直接対応は不要です。ただし、投稿やウィジェットエリアで Navigation ブロックの利用を許可したい方針に変える場合は、`core/navigation` の除外方針を見直し、モバイルメニュー実装との関係を整理する必要があります。

### メタボックスと投稿メタ

Real-time Collaboration は 7.0 から外れたため、RTC 対応としてのメタボックス移行は今回の必須項目ではありません。ただし、公式の過去説明では metabox がある投稿編集体験との互換性が論点になっていました。7.1 以降に備え、`inc/content/class-post-meta.php` の投稿メタは段階的に REST 対応を検討する価値があります。

候補:

- `ys_noindex`, `ys_hide_meta_dscr`, `ys_ogp_title`, `ys_ogp_description`, `ys_hide_ad`, `ys_hide_toc`, `ys_hide_share`, `ys_hide_publish_date`, `ys_hide_author`, `ys_hide_related`, `ys_hide_paging` を `register_post_meta()` で登録する。
- `show_in_rest` を有効化する場合は、公開すべき値か、認証・権限・型・sanitize callback を整理する。
- 保存処理では `save_post_checkbox()` が `$_POST` 値をそのまま `update_post_meta()` しているため、7.0 対応とは別に `sanitize_text_field()` や明示的な `1` 保存へ寄せる余地があります。

### Script loading strategy

WordPress 6.3 以降、`wp_enqueue_script()` / `wp_register_script()` の `$args` で `strategy => 'defer'` / `async` を指定できます。7.0 RC のテストコメントでも、未知の `$args` キー `defer` ではなく `strategy` を使う修正が言及されています。

yStandard は `$args` に `defer` を渡していないため、その notice には該当しません。一方で `wp_script_add_data( $handle, 'defer', true )` と `script_loader_tag` フィルターで属性を追加する独自実装を持つため、7.0 では次を確認します。

- Core の `strategy` と併用した場合に二重属性や順序問題が起きないか。
- `Enqueue_Utility::add_defer()` / `add_async()` を将来的に Core strategy へ寄せられるか。
- jQuery / jQuery Migrate への defer 付与が 7.0 の管理バー、ブロック、埋め込み、外部プラグインと衝突しないか。

## 推奨作業リスト

### 優先度 高

- ✅ WP70-01: WordPress 7.0 RC 環境で `WP_DEBUG`, `WP_DEBUG_LOG` を有効にしてテーマを有効化し、フロント・投稿編集・カスタマイザー・ウィジェット・管理画面で notice / warning / fatal が出ないか確認する。
- ✅ WP70-02: `inc/block-editor/class-block-editor.php` の `$block_editor_context` typo を修正する。
- ✅ WP70-03: クラシックテーマ Font Library の表示、アップロード、エディター反映、フロント反映を確認する。
- ✅ WP70-04: 既存ブロックスタイルの表示確認を行う。特に Gallery, Cover, Image, Search, Button, Group, Columns, Table, Paragraph。
- ✅ WP70-05: `npm run lint` と `npm run build` を実行する。可能であれば `npm run test` も実行する。

### 優先度 中

- ✅ WP70-06: `core/breadcrumbs` は利用不可にし、`core/icon` は現状維持にする。
- WP70-07: Core Breadcrumbs ブロックと yStandard 独自パンくずの併用方針を整理する。
- WP70-08: 個別ブロック Custom CSS と yStandard の CSS 優先順位を確認する。
- WP70-09: ブロック表示制御の mobile/tablet/desktop 非表示がテーマ CSS で上書きされないか確認する。
- WP70-10: `wp_script_add_data( 'defer' / 'async' )` ベースの実装を Core の loading strategy へ移行できるか調査する。

### 優先度 低

- WP70-11: 7.1 以降の RTC 再投入に備え、投稿メタを `register_post_meta()` + `show_in_rest` に寄せる設計案を作る。
- WP70-12: `theme.json` を導入するか検討する。現状のクラシックテーマ構成では必須ではありませんが、Font Library、dimensions presets、textIndent、将来の block visibility breakpoints と相性がよい可能性があります。
- WP70-13: Navigation ブロックを今後許可するか検討する。許可する場合は yStandard のグローバルナビ・ドロワーメニューとの責務分担を整理する。

## 動作確認チェックリスト

- テーマ有効化後に PHP エラーがない。
- フロント: トップ、投稿、固定ページ、アーカイブ、404、検索結果、コメント、パンくず、ヘッダー、フッター、モバイルメニュー。
- 管理画面: ダッシュボード、投稿一覧、投稿編集、固定ページ編集、メディア、外観、カスタマイザー、ウィジェット。
- ブロックエディター: 既存投稿編集、新規投稿作成、画像アップロード、Gallery lightbox、Cover 外部動画、Icon、Breadcrumbs、Search、Button、Group、Columns、Table。
- Font Library: 外観 > Fonts、フォントアップロード、Paragraph の Font Family 選択、フロント反映。
- ブロック表示制御: desktop / tablet / mobile の表示・非表示。
- 個別ブロック Custom CSS: 権限あり/なしの保存と出力。
- JS: defer / async 設定時にコンソールエラーがない。jQuery 最適化設定の ON/OFF で動作する。
- CSS: エディター iframe 内とフロントで同等の見た目になる。
- テスト: `npm run lint`, `npm run build`, 可能なら `npm run test`。

## 参照情報

### WordPress 公式

- [WordPress 7.0 - Make WordPress Core](https://make.wordpress.org/core/7-0/)
- [WordPress 7.0 Release Party Updated Schedule - Make WordPress Core](https://make.wordpress.org/core/2026/04/22/wordpress-7-0-release-party-updated-schedule/)
- [WordPress 7.0 Release Candidate 3 - WordPress News](https://wordpress.org/news/2026/05/wordpress-7-0-release-candidate-3/)
- [Help Test WordPress 7.0 - Make WordPress Test](https://make.wordpress.org/test/2026/02/20/help-test-wordpress-7-0/)
- [Real-time collaboration will not ship in WordPress 7.0 - Make WordPress Core](https://make.wordpress.org/core/2026/05/08/rtc-removed-from-7-0/)
- [Dropping support for PHP 7.2 and 7.3 - Make WordPress Core](https://make.wordpress.org/core/2026/01/09/dropping-support-for-php-7-2-and-7-3/)
- [Iframed Editor Changes in WordPress 7.0 - Make WordPress Core](https://make.wordpress.org/core/2026/02/24/iframed-editor-changes-in-wordpress-7-0/)
- [Block Visibility in WordPress 7.0 - Make WordPress Core](https://make.wordpress.org/core/2026/03/15/block-visibility-in-wordpress-7-0/)
- [Custom CSS for Individual Block Instances in WordPress 7.0 - Make WordPress Core](https://make.wordpress.org/core/2026/03/15/custom-css-for-individual-block-instances-in-wordpress-7-0/)
- [Breadcrumb block filters - Make WordPress Core](https://make.wordpress.org/core/2026/03/04/breadcrumb-block-filters/)
- [Customizable Navigation Overlays in WordPress 7.0 - Make WordPress Core](https://make.wordpress.org/core/2026/03/04/customisable-navigation-overlays-in-wordpress-7-0/)
- [Dimensions Support Enhancements in WordPress 7.0 - Make WordPress Core](https://make.wordpress.org/core/2026/03/15/dimensions-support-enhancements-in-wordpress-7-0/)
- [New Block Support: Text Indent (textIndent) - Make WordPress Core](https://make.wordpress.org/core/2026/03/15/new-block-support-text-indent-textindent/)
- [What's new for developers? (May 2026) - WordPress Developer Blog](https://developer.wordpress.org/news/2026/05/whats-new-for-developers-may-2026/)

### 補助情報

今回は主要判断を WordPress 公式情報に寄せています。補助的にホスティング会社や技術ブログのまとめも検索しましたが、Real-time Collaboration の扱いなど古い予定のままの記事が混在していたため、ドキュメントの根拠には採用していません。
