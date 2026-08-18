# カスタム余白プリセット追加計画

## ステータス

- 計画済み
- 実装済み
- 現在のブランチ: `feature/custom-font-size-presets`
- 実装開始点: `2621643c`（カスタム文字サイズプリセット設定を追加）
- 対象: カスタマイザー「[ys]ブロックエディター」とブロックエディターの余白プリセット

## 背景

yStandard v5は、`theme.json`バージョン3の`settings.spacing.spacingSizes`で約60件のテーマ標準余白プリセットを提供している。これらはブロックエディターのpadding、margin、block gapなど、WordPressの`SpacingSizesControl`を使用する設定で選択できる。

現在はテーマ標準プリセットだけが定義されており、サイトごとに繰り返し使う余白値をカスタマイザーから追加する機能はない。文字サイズプリセットと同様に最大6件の設定枠を用意し、有効な設定を余白選択肢の先頭へ追加する。

## 調査結果

### 既存の余白プリセット

- 正本はルートの`theme.json`にある`settings.spacing.spacingSizes`
- 固定値は`ys-static-{size}`、可変値は`ys-fluid-{min}-{max}`のslugで定義済み
- 値は固定pxと`clamp()`を含む
- `settings.spacing.units`は`%`、`px`、`em`、`rem`、`vh`、`vw`を許可している
- 余白プリセットを独自生成するPHPクラス、カスタマイザー設定、互換フックは現在存在しない

### ブロックエディターの表示順

現在使用している`@wordpress/block-editor`の`useSpacingSizes()`は、余白プリセットを次の順で組み立てる。

```text
WordPressが追加する「なし」
  ↓
custom origin
  ↓
theme origin
  ↓
default origin
```

登録件数が多い場合は、WordPressが選択肢の先頭へ「デフォルト」を追加する。したがって、カスタマイザーの設定はWordPress固定の「デフォルト」「なし」より後、実際の余白プリセットの中ではテーマ標準より前に表示される。

文字サイズと異なり、余白コントロールはcustom、theme、defaultの全originを順番に連結する。このため、テーマ標準プリセットを維持したまま先頭へ追加するには`wp_theme_json_data_user`のcustom originを使用するのが適切である。

## 目的

- カスタマイザーの「[ys]ブロックエディター」で余白プリセットを最大6件登録できるようにする
- 登録済みプリセットを設定番号順でテーマ標準プリセットより前へ表示する
- padding、margin、block gapなどWordPressの余白プリセットを使用するコントロールから選択できるようにする
- 数値、単位付きの長さ、`calc()`、`clamp()`などの計算式を安全に登録できるようにする
- WordPressのGlobal StylesへCSSカスタムプロパティ生成を委ねる
- 既存の約60件のテーマ標準余白プリセットとcustom originの既存値を維持する

## 対象外

- `theme.json`に定義済みのテーマ標準余白プリセットの変更や削除
- プリセットのドラッグによる並び替え
- 7件以上への上限変更
- padding、margin、block gapそれぞれに異なるプリセット一覧を作ること
- ブロックごとに使用可能な余白プリセットを制限すること
- WordPress標準の「デフォルト」「なし」の位置変更
- 余白値からテーマ独自のCSSクラスを生成すること

## 確定仕様

### 登録数と順序

- 設定枠は1〜6の固定6件とする
- ラベルと値が有効な設定だけをプリセットへ変換する
- 複数件が有効な場合は設定1、設定2、…、設定6の順に並べる
- custom origin内では登録済みプリセットを先頭へ追加し、その後ろに既存のcustom originプリセットを並べる
- custom originの後ろに現在の`theme.json`とWordPress標準のプリセットが続く
- 管理対象slugは`ystd-spacing-preset-1`〜`ystd-spacing-preset-6`で固定する
- 同じ管理対象slugが既存データに含まれる場合は、現在のカスタマイザー設定で置き換えて重複を作らない

### 保存データ

設定名と値は別々のoptionとして保存する。

| 項目 | option例 | 型 | 初期値 | 用途 |
|---|---|---|---|---|
| 設定名 | `ys-block-editor-spacing-preset-1-label` | string | 空文字 | ブロックエディターへ表示する名前 |
| 値 | `ys-block-editor-spacing-preset-1-value` | string | 空文字 | 余白として使用するCSS長さ |

### 有効判定

- 設定名が空の枠は登録しない
- 値が空または不正な枠は登録しない
- 未完成の枠は既存のcustom originとテーマ標準プリセットへ影響を与えない
- 仕様外の値はカスタマイザーの検証エラーとして保存を止める
- 不正値を別の値へ丸めたり、無言で初期値へ置き換えたりしない

### 値

- 入力欄は既存のテキストコントロールとする
- 数値だけの場合はプリセット生成時に`px`を付ける
- `0`を有効値として扱う
- 単位付きの非負CSS長さを受け付ける
- `calc()`、`clamp()`、`min()`、`max()`を受け付ける
- 計算式内の`var()`は許可するが、直接の`var()`だけを値にする入力は対象外とする
- CSS宣言の追加、コメント、URL参照、不正な括弧、許可していない関数を拒否する
- 直接入力する負の数値・負の長さはpaddingで使用できないため拒否する

変換例:

| 入力 | `size` |
|---|---|
| `24` | `24px` |
| `1.5rem` | `1.5rem` |
| `10%` | `10%` |
| `calc(1rem + 2vw)` | `calc(1rem + 2vw)` |
| `clamp(1rem, 3vw, 3rem)` | `clamp(1rem, 3vw, 3rem)` |
| `min(4rem, 10vw)` | `min(4rem, 10vw)` |

## Global Stylesへの追加方式

新しい`Block_Editor_Spacing_Size`で`wp_theme_json_data_user`をフィルターし、custom originの`settings.spacing.spacingSizes`を次の順序へ組み替える。

```text
カスタマイザー設定1〜6の有効なプリセット
  ↓
管理対象slugを除外した既存のcustom originプリセット
```

更新データは次の形式とする。

```php
[
	'version'  => 3,
	'settings' => [
		'spacing' => [
			'spacingSizes' => [
				[
					'name' => 'セクション間',
					'slug' => 'ystd-spacing-preset-1',
					'size' => 'clamp(2rem, 5vw, 5rem)',
				],
			],
		],
	],
]
```

この方式により、次の処理はWordPressへ委ねる。

- `--wp--preset--spacing--ystd-spacing-preset-{連番}`の生成
- ブロック属性で使用する`var:preset|spacing|ystd-spacing-preset-{連番}`への変換
- padding、margin、block gapなどの余白コントロールへの表示
- エディターとフロントで使用するCSSへの反映

## カスタマイザーUI

### セクション構成

- 「[ys]ブロックエディター」内に「余白定義」のセクションラベルを追加する
- 説明文は「ブロックエディターで選択できる余白設定を追加できます。」とする
- 設定1〜6を固定順で表示する
- 各設定の先頭へ既存のテキストラベルコントロールで「余白設定1」〜「余白設定6」を表示する
- 設定2〜6の直前へ既存の`Spacer_Control`を60pxで配置する

### 入力コントロール

- 設定名は既存の`add_text()`を使い、ラベルを「設定名（ラベル）」とする
- 値は既存の`add_text()`を使い、ラベルを「値」とする
- 値の説明は既存と同じ「単位付きで入力してください。数値のみを入力した場合は単位はpxになります。」とする
- 新しいReactコントロールやJavaScriptによる表示制御は追加しない

## サーバー側の検証

### sanitize callback

- 設定名へ`sanitize_text_field()`を適用する
- 値を文字列としてtrimする
- 数値、許可単位付きの長さ、許可関数だけを残す
- 数値のみの値は保存時にはそのまま保持し、プリセット生成時にpxを補う

### validate callback

- 空値は未登録の設定枠として保存を許可する
- CSSへ安全に出力できない値は`WP_Error`を返して保存を止める
- エラー文は入力欄で修正方法が分かる内容にする
- 配列など文字列へ安全に変換できない入力を拒否する

文字サイズプリセットと要件が近いが、余白側の実装は新しいクラス内へ閉じる。今回の追加に無関係な文字サイズクラスやレイアウト設定のリファクタリングは行わない。

## 変更予定ファイル

### PHP

- `inc/block-editor/class-block-editor-spacing-size.php`
  - 上限、option名、slugの定義
  - 保存値の取得、検証、プリセット生成
  - `wp_theme_json_data_user`への先頭追加
  - カスタマイザー設定1〜6の登録
- `inc/block-editor/index.php`
  - 新しいクラスの読み込み

### テスト

- `tests/test-block-editor-spacing-size.php`
  - 保存値からのプリセット生成、順序、値の検証、Global Stylesを検証
- `tests/test-customizer.php`
  - 6件分の設定、ラベル、Spacer Control、初期値、入力検証を検証
  - tear downで新しいoptionを削除

### ドキュメント

- `README.md`
  - v5.0.0の作成済み内容へ余白プリセット機能を追記
- `docs/design-system.md`
  - custom origin、option、slug、値の仕様を追記
- `docs/v5-dev.md`
  - カスタマイザーの余白プリセット設定仕様と進捗を追記

JavaScript、JSX、SCSS、webpack entryの追加は予定しない。文字サイズプリセット実装で追加した既存のテキストラベルと`Spacer_Control`を再利用する。

## テスト設計

### プリセット生成

- 6件すべて有効な場合に6件生成される
- 空の設定は生成されない
- slugが`ystd-spacing-preset-{連番}`になる
- 設定番号順になる
- 同じ管理対象slugが既存custom originにあっても重複しない
- 管理対象外の既存custom originプリセットは順序と内容を維持する
- ユーザー設定が既存custom、theme、defaultの各プリセットより前に並ぶ

### 値

- 数値だけの`24`が`24px`になる
- `0`が`0px`になる
- `1.5rem`と`10%`を維持する
- `calc()`、`clamp()`、`min()`、`max()`を維持する
- CSS宣言追加、コメント、`url()`、不正な括弧、未許可関数を拒否する
- 直接入力した負の値を拒否する

### Global SettingsとGlobal Styles

- `wp_get_global_settings( [ 'spacing', 'spacingSizes', 'custom' ] )`の先頭にユーザー設定が並ぶ
- `wp_get_global_settings( [ 'spacing', 'spacingSizes', 'theme' ] )`の既存プリセットが維持される
- `wp_get_global_stylesheet( [ 'variables', 'presets' ] )`に新しいCSSカスタムプロパティが含まれる
- 設定を空へ戻すと管理対象プリセットとCSS変数が消え、既存プリセットだけに戻る
- WordPress 6.5と現在の開発対象バージョンで同じ結果になる

### カスタマイザー

- 設定1〜6が「[ys]ブロックエディター」へ登録される
- optionの初期値がすべて空文字になる
- 「余白設定1」〜「余白設定6」のテキストラベルが表示される
- 設定2〜6の直前に60pxのSpacer Controlが登録される
- 設定名と値が既存テキストコントロールになる
- validate callbackが不正値を`WP_Error`として返す

## 実行するチェック

- 変更PHPファイルの`php -l`
- 変更PHPファイルを対象にしたPHPCS
- 余白プリセット関連の個別PHPUnit
- カスタマイザー関連の個別PHPUnit
- WordPress 6.5での余白プリセット個別PHPUnit
- `npm run test:php`
- `git diff --check`

全PHPUnitで既存失敗が残る場合は、今回の変更による失敗と既存失敗を分けて報告する。

## 手動検証

WordPressの検証環境で次を確認する。

- カスタマイザーに設定1〜6が順番どおり表示される
- 設定2〜6の境界に60pxの余白が表示される
- 有効な設定だけがpadding、margin、block gapの余白一覧へ設定番号順で表示される
- WordPress固定の「デフォルト」「なし」の後、テーマ標準プリセットより前に表示される
- 数値、単位付き値、`calc()`などを保存できる
- 不正値を保存しようとすると対象設定にエラーが表示される
- 選択した値がエディターとフロントで同じ余白として反映される
- 設定を空へ戻すと対象プリセットが選択肢とCSS変数から消える
- 既存のテーマ標準余白プリセットが引き続き選択できる

Codexがブラウザ操作で確認する場合は、確認目的、使用ツール、対象画面を提示して改めて許可を得る。

## 実装順序

- 既存のtheme origin余白プリセット数と代表slugをテストで固定する
- 値のsanitize callbackとvalidate callbackのテストを追加する
- 保存値からtheme.json形式へ変換する処理を実装する
- `wp_theme_json_data_user`で既存custom originを維持しながら先頭追加する
- 既存コントロールで6件分のカスタマイザー設定を登録する
- Global Settings、Global Styles、カスタマイザーの回帰テストを通す
- READMEと設計ドキュメントを更新する

## 完了条件

- カスタマイザーで最大6件の余白プリセットを登録できる
- 有効な登録値が設定番号順でテーマ標準プリセットより前に表示される
- slugが`ystd-spacing-preset-{連番}`で固定される
- 数値だけの入力へpxが補われ、単位付き値と`calc()`なども利用できる
- padding、margin、block gapなどの余白コントロールから選択できる
- Global StylesがエディターとフロントのCSSカスタムプロパティを生成する
- 既存のcustom originとテーマ標準余白プリセットへ回帰がない
- WordPress 6.5と現在の開発対象バージョンで関連テストが成功する

## ブランチ方針

現在は文字サイズ機能のブランチにいるため、この計画段階では追加ブランチを作成しない。余白プリセットを文字サイズ機能と同じ変更単位に含める場合は現在のブランチで実装し、別の変更単位にする場合は実装前に`feature/custom-spacing-presets`を作成する。

## 参考

- `theme.json`の`settings.spacing.spacingSizes`
- `@wordpress/block-editor`の`useSpacingSizes()`
- `inc/block-editor/class-block-editor-font-size.php`
- `inc/block-editor/class-block-editor-color-palette.php`
- `inc/customizer/class-spacer-control.php`
