# 投稿編集メタボックス刷新計画

## ステータス

- 実装済み
- 自動確認済み
- 画面確認待ち
- 対象ブランチ: `feature/post-settings-modal`

## 目的

yStandardが投稿編集画面へ追加している従来のPHPメタボックスをすべて削除し、ブロックエディター上の専用モーダルへ機能を移す。

保存済みの旧投稿メタは削除・書き換えない。新形式の投稿設定がまだ保存されていない投稿では旧投稿メタを確認し、実行時に新しい状態へ変換する。既存投稿を一括更新するデータ移行は行わない。

v5はブロックエディターを前提とし、Classic Editor用メタボックスや保存処理は用意しない。

## 参考実装

設計と共通契約は、先行実装されているyStandard v4とToolboxを参照する。

- yStandard v4の`docs/post-settings-extension-api.md`
- yStandard v4の`inc/block-editor/class-block-editor-post-meta.php`
- yStandard v4の`src/js/block-editor/post-meta.js`
- yStandard v4の`src/js/block-editor/post-settings-extensions.js`
- Toolboxの`docs/post-settings-modal-design.md`
- Toolboxの`src/post-settings/host/`
- Toolboxの`src/post-settings/providers/`

Toolboxは参照元としてのみ扱い、変更しない。共通コンポーネントが必要になった場合は、`ystandard-toolbox/src/aktk-block-components/`から実際に使用するファイルと、その直接依存だけをv5へコピーする。一括コピーやToolboxへの実行時依存は作らない。

## 現在のメタボックス

コード検索で確認できたyStandardの`add_meta_box()`は次の4件である。

| 現在のメタボックス | 対象 | 移行後 |
|---|---|---|
| `[ys]投稿設定` | 通常の公開投稿タイプ | モーダル内の`要素の表示設定`セクション |
| `[ys]SEO設定` | 通常の公開投稿タイプ | モーダル内の`SEO設定`セクション |
| `[ys]SNS設定` | 通常の公開投稿タイプ | モーダル内の`SNS設定`セクション |
| `ショートコード` | `ys-parts` | モーダル内の`ショートコード`セクション |

通常投稿系の3メタボックスは`inc/content/class-post-meta.php`、`ys-parts`の情報メタボックスは`inc/parts/class-parts.php`にある。

## 完成形

### モーダルホスト

ブロックエディターへ1つのyStandardプラグインを登録し、次の3か所から同じモーダルを開く。

- エディターヘッダーのySアイコン
- エディター右上の「︙」メニューにある`yStandard 投稿設定`
- エディター設定サイドバーの`yStandard 投稿設定`パネル

起動UIとモーダルの開閉状態は1つのホストコンポーネントが所有する。モーダルのタイトルは`yStandard 投稿設定`とし、内部へ登録済みセクションと設定コンポーネントを表示順に配置する。

ホストは投稿メタキー、コントロールの種類、値の保存方法を持たない。設定の取得・更新と表示条件は各設定コンポーネントが所有する。

### セクションと設定項目

通常の投稿タイプでは次の標準セクションを登録する。

| セクションID | 表示名 | order |
|---|---|---:|
| `ystandard/post` | `要素の表示設定` | 10 |
| `ystandard/seo` | `SEO設定` | 20 |
| `ystandard/sns` | `SNS設定` | 30 |

`ys-parts`では通常投稿用の3セクションを登録せず、`ショートコード`セクションを登録する。公開後は`[ys_parts parts_id="投稿ID"]`を表示してコピーできるようにする。下書き時は空のモーダルにせず、公開後にショートコードを利用できることを案内する。

設定項目がないセクションは表示しない。yStandardと外部プロバイダーを含めて項目が1件もない場合は、モーダルの起動UIを表示しない。

### 共通JavaScriptフック

yStandard v4とToolboxで先行利用している次のフック名と型を維持する。

- `ystandard.hooks.postSettings.sections`
- `ystandard.hooks.postSettings.items`

共通コンテキストは`apiVersion`、`postType`、`postId`を持つ。設定項目には`postType`と`postId`を渡す。

ホストは次の規則で外部定義を扱う。

- `order`の昇順で表示する
- 同じ`order`では登録順を維持する
- 同じIDは後から登録された有効な定義を採用する
- 不正な定義と未登録セクションを参照する項目は無視する
- yStandard標準セクションと同じIDの外部セクション定義は無視する
- `hookAdded`と`hookRemoved`を監視し、後から読み込まれるToolboxの設定へ追従する
- フィルターまたは設定コンポーネントの例外を分離し、ほかの設定を表示し続ける

Toolboxはv5を検出すると自前のモーダルを出さず、プロバイダーだけを読み込む。共通フックを正本にすることで、Toolboxの設定をyStandardのモーダルへ追加する。

## 投稿メタの登録と保存

### 新しい3状態設定

表示・出力を切り替える10項目は、単純なON・OFFではなく次の3状態にする。

| 保存値 | 表示名 | 動作 |
|---|---|---|
| プロパティなし | `-` | 投稿タイプ別設定やテーマ全体の設定結果を使用する |
| `off` | OFF | テーマ設定がONでも、この投稿では無効にする |
| `on` | ON | テーマ設定がOFFでも、この投稿では有効にする |

新しい設定は単一のオブジェクト型投稿メタ`ys_post_settings`へ保存する。`version`を新形式の識別子として必須保存し、テーマ設定と異なる項目だけをプロパティとして持つ。

```json
{
	"version": 1,
	"toc": "off",
	"share_buttons_header": "off",
	"share_buttons_footer": "on",
	"ogp_title": "OGPタイトル"
}
```

`-`の3状態設定と、空文字の文字列設定はプロパティごと削除する。新形式であることを示す`version`は、ほかのプロパティがなくても残す。

対象プロパティは次のとおりとする。

| プロパティ | 対象機能 | 旧投稿メタ | 旧値`1`の変換先 |
|---|---|---|---|
| `advertisement` | 広告表示 | `ys_hide_ad` | `off` |
| `toc` | 目次の自動作成 | `ys_hide_toc` | `off` |
| `share_buttons_header` | シェアボタン表示（上） | `ys_hide_share` | `off` |
| `share_buttons_footer` | シェアボタン表示（下） | `ys_hide_share` | `off` |
| `publish_date` | 投稿日・更新日表示 | `ys_hide_publish_date` | `off` |
| `author` | 著者情報表示 | `ys_hide_author` | `off` |
| `related_posts` | 関連記事表示 | `ys_hide_related` | `off` |
| `paging` | 前後の記事表示 | `ys_hide_paging` | `off` |
| `noindex` | noindex出力 | `ys_noindex` | `on` |
| `meta_description` | meta description出力 | `ys_hide_meta_dscr` | `off` |

`ys_hide_*`は「非表示にする」旧フラグ、`ys_noindex`は「有効にする」旧フラグで意味が逆になる。旧値からの変換規則を設定定義へ明示し、単純な真偽値変換で処理しない。

### 旧設定のフォールバックと新形式への変換

値取得は次の順に行う。

- `ys_post_settings.version`が対応バージョンなら、新形式だけを使用する
- 新形式で3状態設定のプロパティがなければ`default`として扱う
- 新形式でOGP文字列のプロパティがなければ空文字として扱う
- 新形式が未保存または不正なら、11個の旧投稿メタを確認して実行時に新形式へ変換する
- 旧`ys_hide_*`が`1`相当なら`off`、旧`ys_noindex`が`1`相当なら`on`へ変換する
- 旧OGP文字列が空でなければ、対応する新プロパティへ変換する

有効な新形式が存在する場合は、プロパティがなくても旧投稿メタへ戻らない。これにより、新形式で空にしたOGP設定やテーマ設定へ戻した項目で旧値が復活することを防ぐ。

旧値から変換した状態は表示とフロント出力に使用するが、表示しただけではデータベースへ保存しない。ユーザーがモーダルで初めて設定を変更したときに、旧11キーから変換した全設定を基準として新オブジェクトを作り、変更内容を反映して保存する。

新オブジェクトへ保存するときは、次の圧縮規則を適用する。

- `version`は必ず保存する
- 3状態設定の`default`はプロパティを削除する
- 3状態設定の`off`と`on`だけを保存する
- OGPタイトルとOGP descriptionは、サニタイズ後に空ならプロパティを削除する
- 空でないOGP文字列だけを保存する
- 未知のプロパティは保存しない

例えば旧`ys_ogp_title`に値がある投稿で、モーダルからOGPタイトルを空にした場合、新オブジェクトには`ogp_title`を保存しない。`version`によって新形式を使用中と判断するため、旧`ys_ogp_title`は再参照されない。

旧投稿メタは、新形式の保存後も削除しない。有効な`version`を持つ新形式があれば、個別プロパティの有無にかかわらず常に新形式を優先する。

新形式がない投稿をモーダルへ表示するため、PHPで旧11キーを変換・圧縮した`fallbackSettings`を初期データへ含める。JavaScriptは有効な新形式を最優先し、新形式がない場合だけ`fallbackSettings`を表示値と初回保存の基準にする。表示しただけでは`setMeta()`を実行しない。

### テーマ設定との解決順

フロント側は共通の解決処理を通して最終的な有効状態を取得する。

- プロパティがない場合は現在のテーマ設定を使用する
- `off`はテーマ設定を上書きして無効にする
- `on`はテーマ設定を上書きして有効にする
- 投稿タイプや画面種別による構造上の制約は上書きしない
- 既存の拡張フィルターによる最終判定は維持する

テーマ設定が表示形式や表示位置も持つ機能は、ONにした場合もその形式を使用する。テーマ側が「表示しない」で形式を持たない場合だけ、従来のデフォルトを使用する。

| 機能 | プロパティがない場合に参照するテーマ設定 | テーマ設定がOFFのときに`on`で使用する値 |
|---|---|---|
| 広告 | 設定済みの広告コードと既存の広告表示判定 | 表示判定をONにする。広告コードが空なら何も出力しない |
| 目次 | `ys_create_{post_type}_toc`と`ys_toc_display_type` | 投稿本文内の自動挿入`content` |
| シェアボタン（上） | 投稿タイプ別の本文上部表示タイプ | `circle` |
| シェアボタン（下） | 投稿タイプ別の本文下部表示タイプ | `circle` |
| 投稿日・更新日 | `ys_show_{post_type}_publish_date` | `both` |
| 著者情報 | `ys_show_{post_type}_author` | 表示する |
| 関連記事 | `ys_show_{post_type}_related` | 表示する |
| 前後の記事 | `ys_show_{post_type}_paging` | 表示する |
| noindex | 個別投稿ではnoindexにしない | noindexにする |
| meta description | `ys_option_create_meta_description` | 自動生成して出力する |

フィルターで強制された状態や、404、検索結果、固定ページでの前後記事など、テーマ設定とは別の構造的・外部的な判定は投稿単位設定で上書きしない。

### 設定画面の補足表示

3状態設定には、現在のテーマ設定を投稿タイプごとに解決して補足表示する。単に「テーマ設定を使用」と表示するだけでなく、実際の状態と詳細を示す。

- 広告: 広告コードが設定されている配置の有無
- 目次: 自動作成のON・OFFと表示位置
- シェアボタン（上・下）: それぞれに対応する本文上部・下部の表示タイプ
- 投稿日・更新日: 表示しない、投稿日、更新日、両方のいずれか
- 著者情報、関連記事、前後の記事: 投稿タイプ別設定のON・OFF
- noindex: 個別投稿のテーマ標準はOFF
- meta description: 自動生成のON・OFF

補足は`「-」を選択した場合、テーマ設定「著者情報」の「ON」に従います。`のように、追従する設定名と現在値を判断できる文にする。noindexには対応するテーマ設定がないため、`-`ではnoindexを出力しないことを案内する。PHPで現在のテーマ設定を解決してシリアライズ可能な初期データとして渡し、JavaScript側でテーマの判定ロジックを重複実装しない。

`要素の表示設定`セクションの先頭には、同セクション内の設定をまとめて変更する`既定値に戻す`、`すべてOFF`、`すべてON`ボタンを配置する。固定ページやカスタム投稿タイプでは、その投稿タイプに表示されている項目だけを変更する。

OGPタイトルとOGP descriptionは3状態へ変更せず、`ys_post_settings`内の文字列プロパティとして扱う。それぞれに次の補足を表示する。

- OGPタイトル: 未入力の場合は投稿タイトルを使用する
- OGP description: 未入力の場合は抜粋または投稿本文から自動生成する

### REST API登録

新旧の投稿メタを次の役割で扱う。

| セクション | メタキー | REST型 | 対象 |
|---|---|---|---|
| 共通 | `ys_post_settings` | `object` | 新しい12項目の保存先 |

旧11キーはPHPのフォールバック取得だけに使用し、新UIから更新しない。`inc/content/class-post-meta.php`を投稿メタ定義、旧値変換、圧縮、最終状態解決、REST登録の責務へ絞る。新オブジェクトだけを`register_post_meta()`で登録し、`single`、`default`、`show_in_rest`、`sanitize_callback`、`auth_callback`を明示する。

- `ys_post_settings`は`version`、許可済み10プロパティの`off`・`on`、空でないOGP文字列だけを残す
- 不正なプロパティと不正な状態値は保存しない
- タイトルは`sanitize_text_field()`で処理する
- OGP descriptionは既存どおりHTMLと改行を除去する
- 権限は対象投稿の`edit_post`で確認する

設定コンポーネントは`useEntityProp()`で投稿メタを取得する。変更時は新形式または`fallbackSettings`を基準にし、`ys_post_settings`内の別項目とmetaオブジェクト全体を失わないよう変更内容をマージしてから圧縮する。モーダル専用の保存ボタンは作らず、通常の投稿保存・更新へ統合する。

### 対象投稿タイプ

標準の`post`と`page`には、RESTメタ利用に必要な`custom-fields`サポートをテーマ側で保証する。

公開カスタム投稿タイプは、次の条件を満たす場合だけモーダルとyStandard標準設定を有効にする。

- ブロックエディターを使用している
- `show_in_rest`が有効
- `custom-fields`をサポートしている

他者が登録したカスタム投稿タイプへ、テーマから無条件に`custom-fields`サポートを追加しない。対象投稿タイプは新しい`ys_post_settings_post_types`フィルターで調整できるようにする。

## 従来実装の削除

`inc/content/class-post-meta.php`から次を削除する。

- `admin_menu`での3メタボックス登録
- PHPによる投稿設定・SEO設定・SNS設定のHTML出力
- nonceフィールドと`save_post`検証
- `$_POST`を使うチェックボックス・テキスト・textarea保存処理
- Classic Editor用の保存経路

`inc/parts/class-parts.php`から次を削除する。

- `add_meta_boxes_ys-parts`フック
- ショートコード情報メタボックスの登録とHTML出力

`ys-parts`一覧画面のショートコード列とコピー機能はメタボックスではないため維持する。

PHPのHTML差し込み用フック`ys_meta_box_post`、`ys_meta_box_seo`、`ys_meta_box_sns`は呼び出し元とともに削除する。拡張機能は共通JavaScriptフックへ移行する。

REST保存後の連携点として`ys_save_post_meta_post`、`ys_save_post_meta_seo`、`ys_save_post_meta_sns`は維持する。従来の`$_POST`を前提にせず、登録済み投稿メタを取得するフックとして扱う。

旧`ys_get_meta_box_post_types`と`ys_block_editor_post_meta_fields`はv5では使用せず、新しい投稿タイプフィルターと共通JavaScriptフックへ置き換える。

## aktk-block-componentsの利用

初期実装で必要になる見込みのファイルだけをToolboxからコピーする。

- `wp-controls/modal`
- `wp-controls/editor-pinned-button`
- `wp-controls/plugin-more-menu-item`
- `wp-controls/button`（`editor-pinned-button`の直接依存）

配置先はv5の`src/aktk-block-components/`とし、webpackとTypeScriptに`@aktk/block-components`のaliasを追加する。ySアイコンは投稿設定側の小さな専用コンポーネントとして用意し、Toolbox固有のコンポーネント群はコピーしない。

実装中に別のaktkコンポーネントが必要になった場合も、用途と直接依存を確認してから対象ファイルだけを追加する。コピー後のファイルはv5側で保守し、Toolboxとの自動同期は行わない。

## ファイル構成

### PHP

- `inc/content/class-post-meta.php`: 新旧設定定義、旧値変換、最終状態解決、REST登録、権限、サニタイズ、REST保存後フック
- `inc/block-editor/class-block-editor-post-settings.php`: 編集画面判定、スクリプトと初期コンテキストの読み込み
- `inc/block-editor/index.php`: 投稿設定クラスの読み込み追加
- `inc/parts/class-parts.php`: 従来ショートコードメタボックスの削除
- `inc/advertisement/class-advertisement.php`: 広告の投稿単位状態を共通処理で解決
- `inc/toc/class-toc.php`: 目次の投稿単位状態を共通処理で解決
- `inc/sns/class-share-button.php`: シェアボタンの投稿単位状態を共通処理で解決
- `inc/post-singular/class-post-content.php`: 投稿日・更新日の投稿単位状態を共通処理で解決
- `inc/author/class-author.php`: 著者情報の投稿単位状態を共通処理で解決
- `inc/post-singular/class-post-footer.php`: 関連記事の投稿単位状態を共通処理で解決
- `inc/content/class-paging.php`: 前後の記事の投稿単位状態を共通処理で解決
- `inc/seo/class-no-index.php`: noindexの投稿単位状態を共通処理で解決
- `inc/seo/class-meta-description.php`: meta descriptionの投稿単位状態を共通処理で解決

### TypeScript・SCSS

- `src/scripts/block-editor/post-settings/index.tsx`: エディタープラグイン登録
- `src/scripts/block-editor/post-settings/post-settings-modal.tsx`: 起動UIとモーダルホスト
- `src/scripts/block-editor/post-settings/use-post-settings-items.ts`: 共通フックの取得・監視・正規化
- `src/scripts/block-editor/post-settings/post-settings-item-boundary.tsx`: 項目単位のエラー境界
- `src/scripts/block-editor/post-settings/providers/`: yStandard標準設定と`ys-parts`ショートコード
- `src/scripts/block-editor/post-settings/types.ts`: 共通フック契約
- `src/scripts/block-editor/post-settings/style.scss`: モーダル内レイアウトに必要な最小限のスタイル
- `src/aktk-block-components/`: 実際に使用するaktkコンポーネントだけを配置

webpackへ`block-editor/post-settings`エントリーを追加し、`js/block-editor/post-settings.js`、依存情報の`post-settings.asset.php`、必要なCSSを生成する。PHPは生成されたassetファイルを正本としてWordPress依存ハンドルとバージョンを読み込む。

## テスト計画

### PHPUnit

- `ys_post_settings`が適切なオブジェクトスキーマとREST設定で登録される
- `post`では10プロパティ、`page`では投稿専用2プロパティを除く8プロパティを保存できる
- 有効な`version`を持つ新形式は、個別プロパティがなくても旧投稿メタより優先される
- 新形式が未保存または不正な場合だけ旧11キーを確認する
- 旧`ys_hide_* = 1`を`off`、旧`ys_noindex = 1`を`on`へ変換する
- 新旧どちらもない場合は`default`になる
- `default`へ戻した3状態設定はプロパティを削除し、`version`によって旧投稿メタの再参照を防ぐ
- 空にしたOGP文字列はプロパティを削除し、`version`によって旧OGP文字列の再参照を防ぐ
- すべての任意設定が空でも`version`だけは保存する
- 値取得とREST保存後も旧投稿メタを削除・変更しない
- 不正なプロパティと状態値を保存しない
- `default`、`off`、`on`とテーマ設定から最終状態を正しく解決する
- 文字列、HTML、改行を含む値が期待どおりサニタイズされる
- 編集権限がないユーザーは更新できない
- REST APIから新オブジェクト内の3状態設定とOGP文字列を更新できる
- 対応する投稿編集画面だけでスクリプトを読み込む
- `ys-parts`でもモーダル用スクリプトを読み込む
- yStandard由来の`add_meta_box()`が登録されない
- REST保存後に既存の`ys_save_post_meta_*`アクションが発火する

### JavaScript

- ヘッダーアイコンと「︙」メニューが同じモーダルを開く
- 標準セクションとToolbox相当の外部セクションをorder順で表示する
- 外部項目をyStandard標準セクションへ追加できる
- 同一ID、不正定義、未登録セクションを規則どおり処理する
- フックの後追加・解除へ追従する
- 1項目の例外でモーダル全体が停止しない
- 有効な新形式がなければPHPから渡された旧値変換結果を表示する
- 有効な新形式があれば個別プロパティがなくても旧値変換結果へ戻らない
- `-`ではプロパティを削除し、`OFF`と`ON`だけを保存できる
- シェアボタンの上部・下部を個別に保存・表示できる
- `既定値に戻す`、`すべてOFF`、`すべてON`で要素の表示設定だけを一括変更できる
- OGP文字列を空にすると対応プロパティを削除できる
- 設定変更時に既存metaと`ys_post_settings`内の別項目を維持する
- 各設定に現在のテーマ設定の補足を表示する
- OGP文字列設定に未入力時のフォールバックを表示する
- `post`だけに関連記事とページング設定を表示する
- `ys-parts`では通常投稿設定を表示せず、ショートコードを表示・コピーできる
- 下書きの`ys-parts`で公開後の利用案内を表示する
- 項目がない場合は起動UIを表示しない

JSテストは`@wordpress/scripts`のJest環境を使い、投稿設定用のテストスクリプトを`package.json`へ追加する。既存のLint対象を無関係に広げず、新規TSXとコピーしたコンポーネントを対象に含める。

### 実装後の自動確認

- 投稿設定関連のPHPUnit
- 投稿設定関連のJavaScriptテスト
- 変更PHPファイルの構文チェック
- 変更PHPファイルのPHPCS
- 新規TypeScript・TSXのESLint
- `npm run build`
- `git diff --check`

### 画面確認

- 投稿と固定ページで既存設定値がモーダルへ反映される
- 旧非表示設定がある投稿では対応項目が`OFF`として表示される
- 旧noindex設定がある投稿では`ON`として表示される
- 新設定の`-`、`OFF`、`ON`がフロント表示へ反映される
- 各項目で現在のテーマ設定と表示形式を確認できる
- モーダルで変更した値が投稿更新後も維持される
- 通常保存、自動保存、下書き、公開済み投稿で値が失われない
- Toolbox有効時にToolboxのモーダルが重複せず、設定だけがyStandardモーダルへ追加される
- `ys-parts`の公開前後で案内とショートコードが切り替わる
- ショートコードをキーボード操作でコピーでき、成功・失敗通知が読み上げ可能である
- yStandard由来の従来メタボックスが編集画面に存在しない

ブラウザ系ツールによる画面確認が必要な場合は、実装後に確認目的と対象画面を提示して了承を得てから行う。

## 実装順序

- 新オブジェクト定義、旧値フォールバック、3状態の最終解決処理を実装し、PHPUnitで固める
- 10機能のフロント判定を共通の3状態解決処理へ切り替える
- OGP文字列を含む新オブジェクトをREST APIへ登録する
- 従来の3メタボックス、nonce、`save_post`保存処理を削除する
- 投稿設定用webpackエントリーとPHPの読み込みクラスを追加する
- 必要なaktkコンポーネントだけをToolboxからコピーする
- 共通フック契約、定義の正規化、エラー境界を実装する
- モーダルホストと2つの起動UIを実装する
- yStandard標準3セクション、10個の3状態設定、2個の文字列設定を実装する
- 投稿タイプごとのテーマ設定状態を初期データへ追加し、各項目へ補足表示する
- `ys-parts`のショートコード表示をモーダルへ移し、従来メタボックスを削除する
- JavaScriptテストとPHPUnitを追加する
- 拡張API、v5開発状況、翻訳ファイルを更新する
- 自動確認を実行し、画面確認項目を整理する

## 完了条件

- yStandard内に`add_meta_box()`による投稿編集UIが残っていない
- Classic Editor用メタボックスと`$_POST`保存処理が残っていない
- 旧11キーを移行・削除せず、新形式がない投稿のフォールバックとして使用できる
- 新しい10項目をプロパティなし、`off`、`on`の3状態で扱い、テーマ設定と合わせて最終状態を解決できる
- OGP文字列を`ys_post_settings`へ保存し、空文字ではプロパティを削除できる
- 任意設定をすべて削除しても`version`を残し、旧投稿メタが復活しない
- 各設定に現在のテーマ設定または未入力時の動作が補足表示される
- 通常投稿用3セクションと`ys-parts`のショートコード機能が専用モーダルへ移っている
- yStandardとToolboxが同じJavaScriptフック契約で1つのモーダルを共有できる
- aktkコンポーネントは実際に必要なファイルだけがv5へコピーされている
- 新設定がない既存投稿では、フロント側の表示結果が変更前と一致する
- 対象のLint、ビルド、PHPUnit、JavaScriptテストが成功する
