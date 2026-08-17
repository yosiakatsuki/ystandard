# カスタマイザー色設定の不透明度対応設計

- 作成日: 2026-08-17
- 対象: yStandard v5のカスタマイザーカラー設定
- 状態: 実装・自動検証済み、手動確認待ち

## 背景

yStandard v4から引き継いだカラーコントロールは、WordPressコアの`WP_Customize_Color_Control`を拡張し、Irisの`wpColorPicker`へGlobal Settingsのパレットを渡している。この構成では不透明度を選択できず、保存時の`sanitize_hex_color()`もアルファ値を含む色を受け付けない。

WordPressの`@wordpress/components`にある`ColorPalette`はカスタマイザーを含むエディター外でも使用でき、`enableAlpha`によって不透明度を選択できる。既存のコントロールを延命せず、カスタマイザー用の独立したコントロールへ置き換える。

## 方針

- `WP_Customize_Color_Control`を継承した`Color_Control`を削除する
- `WP_Customize_Control`を継承した`Color_Palette_Control`を追加する
- UIには`@wordpress/components`の`Dropdown`、`ColorIndicator`、`ColorPalette`を使用し、`@wordpress/block-editor`には依存しない
- Classic Editorなど投稿編集方式を変更するプラグインの状態は判定しない
- `Customize_Control::add_color()`を使用するすべての設定を、新しいコントロールへ一括で切り替える
- 保存済みの設定IDと6桁HEX値はそのまま引き継ぐ
- 新しい保存値として3桁・4桁・6桁・8桁HEXと空文字を許可する
- 不透明度はすべてのカラー設定で有効にする

## コントロール構成

PHP側の`Color_Palette_Control`は、説明とReactのマウント先を出力する。Global Settingsから取得したパレット、ラベル、`enableAlpha`をJSONでJavaScriptへ渡す。

JavaScript側はJSXで実装し、`@wordpress/element`の`createRoot()`で次のコンポーネントを描画する。

- `SlotFillProvider`
- `Dropdown`
- `Button`
- `ColorIndicator`
- `ColorPalette`
- `Popover.Slot`

通常時は色見本と設定名だけをボタンとして表示し、クリックすると`Dropdown`のポップオーバー内に`ColorPalette`を表示する。未設定時の色見本には斜線を表示する。ポップオーバーは外側のクリックとEscで閉じ、キーボード操作と`aria-expanded`に対応する。

`ColorPalette`の`value`にはカスタマイザー設定値を渡し、`onChange`では`control.setting.set()`を実行する。外部から設定値が変わった場合も再描画し、カスタマイザーの設定状態と双方向に同期する。

`@wordpress/components`と`@wordpress/element`はWordPress 6.9向けのバージョンを開発依存へ追加する。webpackではWordPressコアの共有スクリプトとして外部化し、React本体をテーマのバンドルへ含めない。

`wp-scripts`が生成する`customizer-control-ys-color-palette-control.asset.php`をPHPで読み込み、抽出された依存スクリプトとバージョンを使用する。`customize-controls`だけはカスタマイザー固有の依存としてPHP側で追加する。カスタマイザーCSSは`wp-components`へ依存させ、WordPressコアのコンポーネントスタイルを先に読み込む。

## パレット

パレットの正本は引き続きGlobal Settingsとする。`defaultPalette`が有効な場合はdefault originを含め、theme、customの順に同じslugを後勝ちで統合する。

新しい`ColorPalette`では色名を表示できるため、カラーコードだけでなく次の情報を渡す。

- `name`
- `slug`
- `color`

パレットが空の場合は空配列を渡し、カスタム色の選択だけを表示する。

## 保存値とサニタイズ

`Customize_Control::sanitize_color()`を追加し、次の形式だけを許可する。

- 空文字
- `#RGB`
- `#RGBA`
- `#RRGGBB`
- `#RRGGBBAA`

形式が不正な値は`null`を返し、保存しない。既存の6桁HEXは変換せず、そのまま保持する。

## CSS出力の互換性

大半のカラー設定は保存値をCSSカスタムプロパティへ直接出力しているため、8桁HEXをそのまま使用できる。色をRGBへ変換している次の設定だけ個別に調整する。

### モバイルフッター背景色

既存の6桁HEXは従来どおり不透明度`0.95`で出力する。4桁・8桁HEXが保存された場合は選択された不透明度を優先し、値をそのままCSSへ出力する。

### グローバルメニュー2層目背景色

既存の`ys_global_nav_sub_menu_background_opacity`は保存値の互換性のため維持する。

- 背景色が3桁・6桁HEXの場合は、従来どおり別設定の不透明度を適用する
- 背景色が4桁・8桁HEXの場合は、色に含まれる不透明度を優先する
- 背景色が空で別設定の不透明度だけがある場合は、従来どおり白へ適用する

## 削除対象

- `inc/customizer/class-color-control.php`
- `src/scripts/admin/customizer-control-ys-color-control.js`
- `src/styles/project/customizer/_ys-color-control.scss`
- 旧コントロール専用のwebpackエントリー
- `src/scripts/admin/customizer-control.js`にあるIris表示サイズ調整
- ビルド済みの`js/customizer-control-ys-color-control.js`
- ビルド済みの`js/customizer-control-ys-color-control.asset.php`
- `Customizer::customize_register()`にある旧クラスの登録

## テスト

- `add_color()`で`Color_Palette_Control`が登録される
- 全カラー設定で不透明度が有効になる
- Global Settingsの色名、slug、カラーコードがコントロールへ渡る
- 3桁・4桁・6桁・8桁HEXと空文字を保存できる
- 不正な色値を保存できない
- モバイルフッター背景色で既存値とアルファ付き値の出力が正しい
- グローバルメニュー2層目背景色で既存の別設定とアルファ付き値の優先順位が正しい
- PHP、PHPCS、JavaScript lint、ビルド、関連PHPUnitを実行する

## 手動確認

- Classic Editorの有効・無効に関係なくカスタマイザーが開く
- 各カラー設定にテーマ色とユーザー定義色が表示される
- カスタム色で不透明度を選択できる
- 色のクリアと再選択が設定値へ反映される
- ツールチップとポップオーバーがカスタマイザー内で欠けずに表示される
- 色見本と設定名のボタンからパレットを開閉できる
- 外側のクリックとEscでポップオーバーを閉じられる
- 保存後の再読み込みで色と不透明度が復元される
