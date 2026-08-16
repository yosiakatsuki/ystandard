# カラーパレットのtheme.json一本化設計

## ステータス

- 実装済み
- 基準コミット: `ab10781b`（ブロックエディターにユーザー定義色を追加）
- 対象: yStandard v5のカラーパレット関連処理

## 背景

yStandard v5はPHPテンプレートを使うクラシックテーマだが、`theme.json`バージョン3を持つハイブリッドテーマとしてブロックエディターの設定とプリセットを管理している。最低対応バージョンはWordPress 6.5。

現在はテーマ標準色を`theme.json`で定義し、カスタマイザーで設定したユーザー定義色を`wp_theme_json_data_user`へ追加している。一方で、v4以前の構成を引き継いだ`editor-color-palette`テーマサポートと独自CSS生成も残っており、同じカラーパレットを複数経路で扱っている。

WordPressでは、`theme.json`を持つテーマで`editor-color-palette`テーマサポートを使うことは推奨されていない。`theme.json`のプリセットはGlobal StylesによってCSSカスタムプロパティとプリセットクラスへ自動変換されるため、テーマ側で同じCSSを再生成する必要もない。

## 目的

- カラーパレットの正本をGlobal Settingsへ一本化する
- `theme.json`が代替するテーマサポートと独自CSS生成を削除する
- v4から引き継ぐユーザー定義色1〜3と、v5で追加した4〜6を維持する
- カスタマイザーとブロックエディターで同じ有効色を参照する
- WordPressコアが生成するプリセットCSSを利用し、テーマ独自処理を減らす

## 対象外

- `theme.json`に定義済みのテーマ標準18色の変更
- ユーザー定義色の設定数、オプション名、設定画面構成の変更
- フォントサイズなど、カラーパレット以外に残るテーマサポートの整理
- yStandard BlocksやyStandard Toolbox側の実装変更

## 現状の責務と問題

### テーマ標準色

`theme.json`の`settings.color.palette`で18色を定義している。`settings.color.defaultPalette`は`false`で、WordPress標準色はUIに表示しない。

この部分は現在のまま正本として維持する。

### ユーザー定義色

`ys-color-palette-ys-user-1`〜`ys-color-palette-ys-user-6`を読み込み、`wp_theme_json_data_user`でGlobal Settingsのcustom originへ追加している。

この処理は、既存のユーザー定義色をGlobal Settingsへ渡すために必要なので維持する。Global Stylesなどが同じcustom originへ追加した別の色は残し、`ys-user-1`〜`ys-user-6`だけを現在の設定値で置き換える。

### editor-color-paletteテーマサポート

`after_setup_theme`で`add_theme_support( 'editor-color-palette', ... )`を実行しているが、ブロックエディターでは`theme.json`が優先される。実際にユーザー定義色を表示できた経路もテーマサポートではなく`wp_theme_json_data_user`だった。

このテーマサポートはブロックエディターのためには不要。ただし、現在は次の独自処理が値の保管場所として参照しているため、先に参照先を変更してから削除する必要がある。

- `Block_Editor_Color_Palette::get_color_palette_css()`
- `Customize_Control::add_color()`

### 独自CSS生成

現在はテーマ標準色とユーザー定義色について、フロントとブロックエディターへ次のCSSを独自生成している。

- `.has-{slug}-color`
- `.has-{slug}-background-color`
- `.has-{slug}-border-color`
- `.has-{slug}-fill`
- 上記に状態クラスや`:hover`を組み合わせたセレクター

WordPressのGlobal Stylesは、色プリセットごとに次のCSSを自動生成する。

- `--wp--preset--color--{slug}`
- `.has-{slug}-color`
- `.has-{slug}-background-color`
- `.has-{slug}-border-color`

コアのプリセットクラスは`!important`付きで生成されるため、現在の独自CSSが追加する基本3種類の宣言は重複している。`:hover`時も同じプリセットクラスが有効なため、同じ色を再指定するルールは不要。

`.has-{slug}-fill`と`has-fill-color`はWordPressコアの色プリセットが生成しない独自仕様だが、yStandardテーマ内に利用箇所はない。アドオン固有の塗り色機能が必要な場合は、その機能を提供するアドオン側がCSSを所有する。

### カスタマイザーのカラーピッカー

`Customize_Control::add_color()`は、カラーピッカーの候補色を`get_theme_support( 'editor-color-palette' )`から取得している。テーマサポートを削除すると既定のIrisパレットへ戻るため、Global Settingsから候補色を作る処理へ置き換える。

`wp_get_global_settings()`から`color.palette`を取得し、次の順序で有効色をまとめる。

- `defaultPalette`が`true`の場合だけdefault origin
- theme origin
- custom origin

同じslugが複数originにある場合は、Global Settingsの優先順位に合わせて後のoriginで上書きする。最終的に色コードの配列へ変換して`Color_Control`へ渡す。パレットが空の場合だけ、現在と同じく`true`を渡してIrisの既定パレットを使う。

## 削除する処理

### `inc/block-editor/class-block-editor-color-palette.php`

- `ystandard\utils\CSS`のimport
- `after_setup_theme`への`add_theme_support()`登録
- `add_theme_support()`
- `enqueue_color_palette_css()`
- `enqueue_block_editor_color_palette_css()`
- `get_color_palette_css()`
- `create_color_palette_css()`
- `get_color_palette_css_types()`
- `get_color_palette()`
- `get_color_palette_from_theme_json()`
- 上記CSS生成メソッドを登録している`ys_get_blocks_inline_css`フィルター
- 上記CSS生成メソッドを登録している`ys_block_editor_assets_inline_css`フィルター

これにより、色の定義、Global Settingsへの追加、CSS出力の経路が重複しなくなる。`get_color_palette()`がマージ済みGlobal Settingsへユーザー定義色を再追加する可能性もなくなる。

## 維持する処理

### `inc/block-editor/class-block-editor-color-palette.php`

- `USER_COLOR_LIMIT`
- `wp_theme_json_data_user`フィルター
- `add_user_color_palette_to_theme_json()`
- `get_user_color_palette()`
- `customize_register()`
- v4と同じ`ys-color-palette-ys-user-{連番}`オプション

## 置き換える処理

### `inc/customizer/class-customize-control.php`

`Customize_Control::add_color()`内の`get_theme_support( 'editor-color-palette' )`を、Global Settingsから有効な色を取得するprivateメソッドへ置き換える。

このメソッドはカスタマイザーのための表示用配列だけを返し、カラーパレット自体の正本にはしない。正本は常に`theme.json`と`wp_theme_json_data_user`で構成されるGlobal Settingsとする。

## 廃止する独自フック

次のフックは、削除するテーマサポートまたは独自CSS生成にだけ関係するため廃止する。

- `ys_editor_color_palette`
- `ys_get_color_palette_css_types`
- `ys_is_enqueue_color_pallet`
- `ys_is_enqueue_block_editor_color_pallet`

`ys_get_blocks_inline_css`と`ys_block_editor_assets_inline_css`自体は他機能でも使用しているため削除しない。カラーパレットクラスからのコールバック登録だけを削除する。

互換性を維持しない方針はREADMEの「v5.0.0 - 廃止されたフック」へ追記する。

## テスト設計

### Global Settings

- テーマ標準色がtheme originに残る
- WordPress標準色が`defaultPalette: false`で非表示になる
- 設定済みの`ys-user-1`〜`ys-user-6`だけがcustom originへ追加される
- custom originに既存の別slugがある場合は保持される
- custom originに古い`ys-user-{連番}`がある場合は現在のオプション値で置き換わる
- 未設定のユーザー定義色は追加されない

### Global Stylesheet

`wp_get_global_stylesheet( [ 'variables', 'presets' ] )`に、設定したユーザー定義色の次の出力が含まれることを確認する。

- `--wp--preset--color--ys-user-1`
- `.has-ys-user-1-color`
- `.has-ys-user-1-background-color`
- `.has-ys-user-1-border-color`

これにより、独自CSS生成を削除してもフロントとエディターで必要なプリセットCSSがWordPressから提供されることを固定する。

### テーマサポートと独自CSS

- `get_theme_support( 'editor-color-palette' )`が登録されていない
- カラーパレットクラスが`ys_get_blocks_inline_css`へCSSを追加しない
- カラーパレットクラスが`ys_block_editor_assets_inline_css`へCSSを追加しない

### カスタマイザー

- テーマサポートがなくても`Color_Control`のパレットにtheme originの色が入る
- 設定済みユーザー定義色がcustom originからパレットへ入る
- `defaultPalette: false`の場合はdefault originの色を入れない
- 色設定1〜6の登録、初期値、ラベル、v4オプション継承は既存テストを維持する

### 実行するチェック

- 対象PHPファイルの`php -l`
- 対象PHPファイルのPHPCS
- カラーパレット関連の個別PHPUnit
- `npm run test:php`
- `git diff --check`

既存の`CustomizerTest::test_get_priority`は、`ys_seo`の実装値1530に対して期待値1110のままになっている。この既存失敗は今回の実装へ混ぜず、全体テスト結果では別件として明記する。

## 手動検証

実装後は、ユーザー定義色を設定したWordPress環境で次を確認する。

- 投稿エディターの色パレットにテーマ標準18色が表示される
- 「カスタム」に「色設定1」「色設定2」が表示される
- `#07689f`と`#f2b3b8`が正しい色で表示される
- 文字色、背景色、枠線色へ適用できる
- 保存後のフロントでも同じ色になる
- カスタマイザーの各カラーコントロールでテーマ色とユーザー定義色を候補として選べる

ブラウザ操作による確認をCodexが行う場合は、その時点で改めてブラウザ使用範囲の許可を得る。

## 実装順序

- Global SettingsとGlobal Stylesheetの回帰テストを追加する
- カスタマイザーの候補色取得をGlobal Settingsへ切り替える
- `editor-color-palette`テーマサポートを削除する
- 独自CSS生成と関連メソッド、関連フックを削除する
- 不要になったテストをGlobal Settings基準へ置き換える
- READMEと設計資料の廃止フック一覧を更新する
- 自動テスト後、許可された環境で手動確認する

## 完了条件

- カラーパレットの入力元が`theme.json`とユーザー定義色オプションだけになっている
- ブロックエディターとカスタマイザーがGlobal Settingsの有効色を参照している
- `editor-color-palette`テーマサポートが存在しない
- カラーパレット用の独自プリセットCSSをテーマが生成していない
- ユーザー定義色1〜6がブロックエディターとフロントで利用できる
- テーマ内で不要になったカラーパレット関連フックがREADMEに明記されている

## 参考資料

- [Theme Support - Block Editor Handbook](https://developer.wordpress.org/block-editor/how-to-guides/themes/theme-support/)
- [Global Settings & Styles - Block Editor Handbook](https://developer.wordpress.org/block-editor/how-to-guides/themes/global-settings-and-styles/)
- [Global Styles Filters - Block Editor Handbook](https://developer.wordpress.org/block-editor/reference-guides/filters/global-styles-filters/)
- [wp_get_global_settings()](https://developer.wordpress.org/reference/functions/wp_get_global_settings/)
- [wp_theme_json_data_user](https://developer.wordpress.org/reference/hooks/wp_theme_json_data_user/)
