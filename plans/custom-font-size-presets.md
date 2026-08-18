# カスタム文字サイズプリセット追加計画

## ステータス

- 計画済み
- 実装未着手
- 作業ブランチ: `feature/custom-font-size-presets`
- 基準ブランチ: `5.0.0`
- 対象: カスタマイザー「[ys]ブロックエディター」とブロックエディターの文字サイズプリセット

## 背景

yStandard v5は、`theme.json`バージョン3の`settings.typography.fontSizes`でテーマ標準の文字サイズプリセットを提供している。一方、カスタマイザーから文字サイズプリセットを追加する機能はなく、利用者がサイトごとの文字サイズをプリセットとして先頭へ追加できない。

既存の`Block_Editor_Font_Size`には、旧`editor-font-sizes`テーマサポートと独自CSS生成が残っている。ただし、`theme.json`を持つテーマでは`theme.json`の設定がテーマサポートより優先され、Global StylesがプリセットのCSSカスタムプロパティと`.has-{slug}-font-size`クラスを生成する。

今回の追加機能は`theme.json`の既存プリセットを正本として維持し、カスタマイザーの保存値から動的に生成した最大6件を先頭へ差し込む。あわせて、`theme.json`と役割が重複する`editor-font-sizes`テーマサポートと独自CSS生成を削除し、文字サイズプリセットの提供経路をGlobal Stylesへ一本化する。

## 目的

- カスタマイザーの「[ys]ブロックエディター」で文字サイズプリセットを最大6件登録できるようにする
- 登録済みプリセットを設定番号の若い順で、テーマ標準プリセットより前に表示する
- 固定値とWordPressの流体タイポグラフィを選択できるようにする
- 入力値を安全に検証し、ブロックエディターとフロントで同じ値を使用する
- `editor-font-sizes`テーマサポートと重複する独自CSS生成を削除する
- 既存のテーマ標準プリセット、既存コンテンツのslug、カラーパレット設定へ影響を与えない

## 対象外

- `theme.json`に定義済みのテーマ標準文字サイズの変更や削除
- プリセットのドラッグによる並び替え
- 7件以上への上限変更
- yStandard BlocksやyStandard Toolbox側の変更
- 既存コンテンツが使用している文字サイズslugの移行

## 確定仕様

### 登録数と順序

- 設定枠は1〜6の固定6件とする
- 有効な設定だけをプリセットへ変換する
- 複数件が有効な場合は設定1、設定2、…、設定6の順に並べる
- 登録済みプリセットを先頭へ追加し、その後ろに現在の`theme.json`のプリセットを並べる
- 管理対象slugは`ystd-font-size-preset-1`〜`ystd-font-size-preset-6`で固定する
- 同じ管理対象slugが既存データに含まれる場合は、現在のカスタマイザー設定で置き換えて重複を作らない

### 保存データ

ラベル、種類、固定値、min、max、単位は既存カスタマイザーコントロールへ個別に接続できるよう、別々のoptionとして保存する。option名は`ys-block-editor-font-size-preset-{連番}-{項目名}`とする。

| 項目名 | option例 | 型 | 初期値 | 用途 |
|---|---|---|---|---|
| `label` | `ys-block-editor-font-size-preset-1-label` | string | 空文字 | ブロックエディターへ表示する名前 |
| `type` | `ys-block-editor-font-size-preset-1-type` | string | `static` | `static`または`fluid` |
| `static` | `ys-block-editor-font-size-preset-1-static` | string | 空文字 | 固定値のCSS文字サイズ |
| `min` | `ys-block-editor-font-size-preset-1-min` | string | 空文字 | fluidの最小値 |
| `max` | `ys-block-editor-font-size-preset-1-max` | string | 空文字 | fluidの最大値 |
| `unit` | `ys-block-editor-font-size-preset-1-unit` | string | `rem` | fluidの`rem`または`px` |

表示方式を切り替えても入力済みの別方式の値は消さず、現在選択中の`type`に必要な値だけをプリセット生成に使用する。

### 有効判定

- ラベルが空の枠は登録しない
- `static`は固定値が空または不正な場合に登録しない
- `fluid`はminまたはmaxが空、不正、またはminがmaxを超える場合に登録しない
- 未完成の枠はテーマ標準プリセットへ影響を与えない
- 入力値自体が仕様外の場合はカスタマイザーの検証エラーとして保存を止め、無言で別の値へ丸めない

### 固定値

- 入力欄はテキストとする
- 数値だけの場合はプリセット生成時に`px`を付ける
- 単位付きのCSS長さ、`calc()`、`clamp()`を受け付ける
- CSS宣言の追加、コメント、URL参照、不正な括弧、許可していない関数を拒否する
- `fluid`は明示的に`false`を設定する

変換例:

| 入力 | `size` |
|---|---|
| `16` | `16px` |
| `1.25rem` | `1.25rem` |
| `calc(1rem + 0.5vw)` | `calc(1rem + 0.5vw)` |
| `clamp(1rem, 2vw, 1.5rem)` | `clamp(1rem, 2vw, 1.5rem)` |

### fluid

- minとmaxは数値入力とする
- 最小値は0、最大値は999とする
- 単位の初期値は`rem`とする
- `rem`は小数を許可し、stepを0.1とする
- `px`は整数だけを許可し、stepを1とする
- minとmaxへ同じ単位を適用する
- `size`にはmaxと同じ値を設定し、`fluid.min`と`fluid.max`で実際の下限・上限を指定する

生成例:

```php
[
	'name'  => '本文大',
	'slug'  => 'ystd-font-size-preset-1',
	'size'  => '1.8rem',
	'fluid' => [
		'min' => '1.2rem',
		'max' => '1.8rem',
	],
]
```

WordPressの流体タイポグラフィへ計算を委ね、テーマ側で独自の`clamp()`式は生成しない。

## Global Stylesへの追加方式

`Block_Editor_Font_Size`で`wp_theme_json_data_theme`をフィルターし、現在のtheme originの`settings.typography.fontSizes`を次の順序へ組み替える。

```text
カスタマイザー設定1〜6の有効なプリセット
  ↓
管理対象slugを除外した既存のtheme.jsonプリセット
```

`fontSizes`はブロックエディター側でcustom、theme、defaultの優先順位による上書き対象として扱われる。カスタマイザーの6件だけを`wp_theme_json_data_user`のcustom originへ追加するとtheme originの既存プリセットが選択肢から隠れるため、今回はtheme originの配列へ先頭追加する。

この方式により、次の出力はWordPressのGlobal Stylesへ委ねる。

- `--wp--preset--font-size--ystd-font-size-preset-{連番}`
- `.has-ystd-font-size-preset-{連番}-font-size`
- ブロックエディターへ渡すプリセット一覧
- fluid指定に対応する`clamp()`値

## テーマサポートと独自CSS生成の整理

`theme.json`に同じname、slug、sizeが定義済みで、Global StylesがプリセットCSSも生成するため、`Block_Editor_Font_Size`から次の処理を削除する。

- `after_setup_theme`での`editor-font-sizes`テーマサポート登録
- `get_editor_font_sizes()`と`add_theme_support()`
- `get_theme_support( 'editor-font-sizes' )`を参照する`get_font_size_css()`
- CSSカスタムプロパティを再生成する`get_font_size_presets_css()`
- フロントへ独自クラスを追加する`add_font_sizes_css()`
- ブロックエディターへ独自クラスを追加する`add_block_editor_font_sizes_css()`
- プリセット変数を追加する`add_font_sizes_presets()`
- 上記メソッドを登録している`ys_get_blocks_inline_css`、`ys_get_css_custom_properties_args_presets`、`ys_block_editor_assets_inline_css`の各コールバック

関連処理の削除により、次の独自フックも廃止する。

- `ys_editor_font_sizes`
- `ys_is_enqueue_font_size`
- `ys_is_enqueue_block_editor_font_size`

`ys_get_blocks_inline_css`、`ys_get_css_custom_properties_args_presets`、`ys_block_editor_assets_inline_css`自体は他機能が利用しているため削除しない。

### 互換性影響

リポジトリ内では、削除対象のテーマサポート、メソッド、独自フックを`Block_Editor_Font_Size`以外から参照していない。さらに、旧テーマサポートが持つ`x-small`、`small`、`normal`、`medium`、`large`、`x-large`、`xx-large`は、同じ表示名と値で`theme.json`に定義済みのため、通常の既存コンテンツとプリセットクラスはGlobal Styles経由で維持できる。

影響があるのは、主に子テーマやプラグインが次を行っている場合とする。

- `ys_editor_font_sizes`でプリセットを追加または変更している
- 削除対象のpublic staticメソッドを直接呼んでいる
- `editor-font-sizes`テーマサポートの配列を直接参照している
- テーマ独自CSSの出力タイミングやセレクターへ依存している

下位互換性は原則として維持せず、READMEの「v5.0.0 - 廃止されたフック」と開発資料へ廃止内容を明記する。ただし、実装時のWordPress 6.5検証でtheme.jsonの既存プリセット、プリセットクラス、CSSカスタムプロパティのいずれかが欠落する場合は削除を止め、段階的廃止または最小限の互換処理を再検討する。

## カスタマイザーUI

### セクション構成

- 「[ys]ブロックエディター」内に「文字サイズ定義」のセクションラベルを追加する
- 説明文で最大6件、ラベルが空の設定は登録されないこと、登録済み設定が先頭へ表示されることを伝える
- 設定1〜6を固定順で表示する
- 各設定には「ラベル」「種類」「固定値」または「min・max・単位」を表示する

### コントロール

種類と単位だけを扱う汎用`Toggle_Group_Control`を新設する。それ以外は既存の`Customize_Control`が提供するコントロールを使用する。

- ラベルと固定値は既存の`add_text()`を使う
- minとmaxは既存の`add_number()`を使う
- 種類は`ToggleGroupControl`で`固定値`と`fluid`を横並び表示する
- 単位は`ToggleGroupControl`で`rem`と`px`を横並び表示する
- `ToggleGroupControl`は`isBlock`を有効にし、狭いカスタマイザーでも各選択肢を同じ幅で表示する
- 固定値、min、maxはそれぞれ独立した既存コントロールとし、複合Reactコントロールは作らない
- PHPの`active_callback`で初期表示を決め、種類の変更に応じて既存コントロールの固定値またはmin・max・単位を表示する
- 単位を切り替えたらmin・maxのstepを即時更新する
- min・maxの`min`と`max`は常に0と999を設定する
- 不正なstep、範囲外、minとmaxの逆転は入力欄とカスタマイザーの設定検証で利用者へ通知する
- キーボード操作とラベルの関連付けはWordPress Componentsの標準実装を利用する

### スクリプトとスタイル

- `wp-scripts`の独立entryとしてビルドする
- 生成される`.asset.php`を読み、依存スクリプトとバージョンを登録する
- ToggleGroupControlの固定文言はPHP側で翻訳して`to_json()`から渡す
- 種類による表示切り替えと単位によるstep変更は既存の`customizer-control.js`から各settingを監視して行う
- WordPress Componentsと既存カスタマイザーコントロールの標準スタイルを優先し、独自CSSはToggleGroupControlに必要な範囲だけにする
- `css/`と`js/`はGit管理外のため、ビルド生成物は検証に使用し、ソース差分へは含めない

## サーバー側の検証

各optionへ既存または専用のsanitize callbackとvalidate callbackを設定する。min・maxの検証では、同じ保存操作で変更された単位を正しく参照できるよう、保存済みoptionだけでなくカスタマイザーの未サニタイズ値も確認する。

### sanitize callback

- ラベルへ`sanitize_text_field()`を適用する
- `type`は既存の選択肢サニタイズで`static`または`fluid`へ限定する
- `unit`は既存の選択肢サニタイズで`rem`または`px`へ限定する
- static値を安全なCSS文字サイズへ限定する
- minとmaxを文字列として正規化し、`0`を空値と誤判定しない

### validate callback

- staticの入力値がCSS文字サイズとして不正な場合は保存を止める
- remのmin・maxが0.1刻みでない場合は保存を止める
- pxのmin・maxが整数でない場合は保存を止める
- min・maxが0〜999の範囲外の場合は保存を止める
- minがmaxを超える場合は保存を止める
- ラベルや必要値が未入力の枠は「未登録」として保存を許可する

`Customize_Control::get_setting_args()`がvalidate callbackを渡せるように、既存設定へ影響しない形で引数マッピングを追加する。種類や単位の選択値はToggleGroupControl側でも固定選択肢に限定するが、PHP側の検証を正本とする。

## 変更予定ファイル

### PHP

- `inc/block-editor/class-block-editor-font-size.php`
  - 上限、option名、slugの定義
  - 保存値の取得、検証、プリセット生成
  - `wp_theme_json_data_theme`への先頭追加
  - カスタマイザー設定1〜6の登録
  - `editor-font-sizes`テーマサポートと重複CSS生成の削除
- `inc/customizer/class-toggle-group-control.php`
  - 汎用ToggleGroupControl、選択肢データ、アセット読み込み
- `inc/customizer/class-customize-control.php`
  - ToggleGroupControl追加メソッド
  - validate callbackの設定引数対応
- `inc/customizer/class-customizer.php`
  - 新しいJSコントロールタイプの登録
- `inc/customizer/index.php`
  - 新しいコントロールクラスの読み込み

### JavaScriptとCSS

- `src/scripts/admin/customizer-control-ys-toggle-group-control.jsx`
  - 汎用ToggleGroupControlと単一settingの接続
- `src/scripts/admin/customizer-control.js`
  - 種類による既存コントロールの表示切り替え
  - 単位によるmin・maxのstep切り替え
- `src/styles/project/customizer/_toggle-group-control.scss`
  - ToggleGroupControlに必要な最小限のスタイル
- `src/styles/project/customizer/_index.scss`
  - 新しいSCSSの読み込み
- `webpack.app.config.js`
  - 新しいカスタマイザーコントロールentry

### テストとドキュメント

- `tests/test-block-editor-font-size.php`
  - 保存値からのプリセット生成、順序、固定値、fluid、Global Styles、旧処理削除を検証
- `tests/test-customizer.php`
  - 6件分の既存コントロールとToggleGroupControl、初期値、アセット登録を検証
  - tear downで新しいoptionを削除
- `docs/design-system.md`
  - カスタム文字サイズプリセットのデータ構造とGlobal Styles経路を追記
- `docs/v5-dev.md`
  - カスタマイザーのブロックエディター設定仕様を追記
- `README.md`
  - v5.0.0の作成済み内容へ追加機能と上限を追記
  - 「v5.0.0 - 廃止されたフック」へ文字サイズ関連フックを追記

## テスト設計

### プリセット生成

- 6件すべて有効な場合に6件生成される
- 空の設定は生成されない
- slugが連番の固定値になる
- 設定番号順になる
- 同じ管理対象slugが既存配列にあっても重複しない
- 管理対象外の既存プリセットは順序と内容を維持する
- ユーザー設定が既存プリセットより前に並ぶ

### 固定値

- 数値だけの`16`が`16px`になる
- `1.25rem`を維持する
- `calc()`と`clamp()`を維持する
- CSS宣言追加、コメント、`url()`、不正な括弧、未許可関数を拒否する
- `fluid`が`false`になる

### fluid

- 初期単位が`rem`になる
- remの小数と0.1刻みを受け付ける
- pxの整数を受け付ける
- pxの小数を拒否する
- 0と999を受け付け、範囲外を拒否する
- minがmaxを超える値を拒否する
- `size`がmax、`fluid.min`と`fluid.max`が選択単位付きで生成される

### Global SettingsとGlobal Styles

- `wp_get_global_settings( [ 'typography', 'fontSizes', 'theme' ] )`の先頭にユーザー設定が並ぶ
- theme.jsonの既存プリセットが後続に残る
- `wp_get_global_stylesheet( [ 'variables', 'presets' ] )`に新しいCSSカスタムプロパティとクラスが含まれる
- static値がそのままCSSへ反映される
- fluid値からWordPressが`clamp()`を生成する
- 設定を空へ戻すと管理対象プリセットとCSSが消え、テーマ標準だけに戻る

### テーマサポートと重複CSS

- `get_theme_support( 'editor-font-sizes' )`が登録されていない
- `Block_Editor_Font_Size`が`ys_get_blocks_inline_css`へ文字サイズCSSを追加しない
- `Block_Editor_Font_Size`が`ys_get_css_custom_properties_args_presets`へプリセット変数を追加しない
- `Block_Editor_Font_Size`が`ys_block_editor_assets_inline_css`へ文字サイズCSSを追加しない
- theme.jsonの`x-small`〜`xx-large`がGlobal Settingsに残る
- Global Stylesが既存slugのCSSカスタムプロパティとプリセットクラスを生成する
- 廃止対象の独自フックとpublic staticメソッドがテーマ内から参照されていない

### カスタマイザー

- 設定1〜6が「[ys]ブロックエディター」へ登録される
- optionの初期値が`static`、`rem`、その他空文字になる
- ラベルと固定値が既存テキストコントロール、minとmaxが既存数値コントロールになる
- 種類と単位だけがToggleGroupControlになる
- ToggleGroupControlへ翻訳済みラベルと選択肢が渡る
- スクリプトの依存関係とバージョンが`.asset.php`に一致する
- validate callbackが不正値を`WP_Error`として返す

## 実行するチェック

- 変更PHPファイルの`php -l`
- 変更PHPファイルを対象にしたPHPCS
- 新規JSXを対象にしたESLint
- `npm run build:script:app`
- `npm run build:css`
- フォントサイズ関連の個別PHPUnit
- カスタマイザー関連の個別PHPUnit
- `npm run test:php`
- `git diff --check`

依存関係の追加は行わず、現在の`@wordpress/components`、`@wordpress/element`、`@wordpress/i18n`と既存ビルド構成を使用する。

## 手動検証

WordPress 6.5以上の検証環境で次を確認する。

- カスタマイザーに設定1〜6が順番どおり表示される
- 種類と単位が横並びの選択コントロールになる
- staticとfluidの切り替えで必要な入力欄だけが表示される
- rem選択時は0.1刻み、px選択時は1刻みになる
- 不正値を保存しようとすると対象設定にエラーが表示される
- 有効な設定だけがブロックエディターの文字サイズ一覧先頭へ設定番号順で表示される
- テーマ標準の文字サイズがユーザー設定の後ろに残る
- 選択したブロックへ固定値とfluid値が正しく反映される
- 保存後のフロントでも同じ文字サイズになる
- 設定を空へ戻すと対象プリセットが選択肢とCSSから消える
- 既存の`x-small`〜`xx-large`を含むテーマ標準プリセットが引き続き選択できる
- `editor-font-sizes`テーマサポートを削除しても既存プリセットの表示とフロント出力が変わらない

Codexがブラウザ操作で確認する場合は、実装時に確認目的、使用ツール、対象画面を提示して改めて許可を得る。

## 実装順序

- theme.jsonに旧テーマサポートと同じ既存slug・値があることをテストで固定する
- `editor-font-sizes`テーマサポートと独自CSS生成を削除する
- 個別optionのsanitize callback、validate callbackのテストを追加する
- 保存値からtheme.json形式へ変換する処理を実装する
- `wp_theme_json_data_theme`で既存プリセットを維持しながら先頭追加する
- 既存コントロールとToggleGroupControlで6件分の設定を登録する
- 汎用ToggleGroupControlと必要最小限のSCSSを実装する
- 既存`customizer-control.js`へ表示とstepの連動処理を追加する
- webpack entryを追加してJSとCSSをビルドする
- Global Settings、Global Styles、カスタマイザーの回帰テストを通す
- READMEと設計ドキュメントを更新する
- 許可された環境で手動確認する

## 完了条件

- カスタマイザーで最大6件の文字サイズプリセットを登録できる
- 有効な登録値が設定番号順でテーマ標準プリセットより前に表示される
- slugが`ystd-font-size-preset-{連番}`で固定される
- staticの数値だけの入力へ`px`が補われ、`calc()`と`clamp()`も利用できる
- fluidのrem・px制約、範囲、step、min・max関係がUIとPHPの両方で検証される
- WordPressのGlobal StylesがエディターとフロントのプリセットCSSを生成する
- `editor-font-sizes`テーマサポートと文字サイズ用の重複CSS生成が存在しない
- `ys_editor_font_sizes`など廃止するフックがREADMEへ記録されている
- 既存のテーマ標準プリセット、カラーパレット、既存コンテンツへ回帰がない
- 自動チェック結果と手動確認結果を実装完了報告へ記録できる

## 参考資料

- [Typography - Theme Handbook](https://developer.wordpress.org/themes/global-settings-and-styles/settings/typography/)
- [Global Settings & Styles - Block Editor Handbook](https://developer.wordpress.org/block-editor/how-to-guides/themes/global-settings-and-styles/)
- [Theme.json Version 3 Reference](https://developer.wordpress.org/block-editor/reference-guides/theme-json-reference/theme-json-living/)
- [Theme Support - Block Editor Handbook](https://developer.wordpress.org/block-editor/how-to-guides/themes/theme-support/)
