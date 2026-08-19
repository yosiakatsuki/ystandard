# yStandard

![yStandard](./screenshot.png 'yStandard')

## カスタマイズありきの一風変わったWordPressテーマ「yStandard」

yStandardは「自分色に染めた、自分だけのサイトを作る楽しさ」を感じてもらうために作った一風変わったテーマです

詳しくは公式サイトをご覧ください

[yStandard](https://wp-ystandard.com/)

## 「yStandard」の由来

「標準」といった意味の「Standard」に作者が自作物やハンドルネームによく使う「ys」というフレーズをくっつけて、「yStandard」にしました。

先頭の「y」に意味はなく、発音する必要も無いと思っておりましたが、「yStandard」を「y」の部分まで発音すると「why
standard」に聞こえることから"一風変わった"というコンセプトを掲げています

## 必要な動作環境

- WordPress : 6.5以上
- PHP : 7.4以上

## 変更履歴

### v5.x.x

### v5.0.0 :

#### 計画中の改修案

- 設定：アーカイブ：投稿タイプ別の設定にいれる

#### マニュアルの見直し

- manual/post-layout : 廃止。manual/post_type_layoutに移行
- manual/page-layout : 廃止。manual/post_type_layoutに移行

#### 作成済みの内容

##### 全体・システム

- [変更] クラシックテーマからハイブリッドテーマへの切り替え
- [変更] 動作に必要なWordPressバージョンを6.5に引き上げ
- [変更] ファイル整理。変更内容は「v5.0.0 - ファイル移動表」を参照
- [削除] polyfill削除（`Enqueue_Polyfill`クラスの削除）
- [削除] おすすめプラグイン機能廃止
- [変更] テンプレートパーツ読み込みをWordPressコアの`get_template_part()`へ統一
- [追加] `/block-styles.json`からブロックスタイルを追加できる機能追加.
- [変更] パンくずリストを404ページで表示しないように変更

##### 設定関連

- [変更] カスタマイザーの色設定をWordPress Componentsの`ColorPalette`へ変更し、不透明度の選択に対応
	- v4から引き継いだ`WP_Customize_Color_Control`の拡張を削除
	- ブロックエディターと同じ色見本付きコントロールからカラーパレットを開くポップオーバー形式へ変更
	- パレットを「テーマ」「デフォルト」「カスタム」の色定義元ごとにグループ表示
	- WordPressのバージョンに依存せずコントロールの境界が表示されるよう調整
	- JSXのimportから生成した`.asset.php`を使用し、依存スクリプトとバージョンを自動反映
	- 保存済みの設定IDと6桁HEX値は引き継ぐ
	- 新しい設定値として4桁・8桁HEXを保存可能
- [変更] カスタマイザーのブロックエディター用カラーパレット設定をユーザー定義色のみに変更
	- テーマ標準のカラーパレットは`theme.json`で提供し、カスタマイザーでは変更不可
	- v4のユーザー定義色3件（`ys-color-palette-ys-user-1`〜`ys-color-palette-ys-user-3`）は設定値を引き継ぐ
	- ユーザー定義色を6件（`ys-color-palette-ys-user-1`〜`ys-color-palette-ys-user-6`）まで拡張
	- カスタマイザーのトップレベルに「[ys]ブロックエディター」セクションとして配置し、1クリックで設定を表示
	- セクション内に背景色付きの「色定義」見出しを表示
- [追加] カスタマイザーの「[ys]ブロックエディター」に文字サイズプリセット設定を追加
	- 固定値またはfluidを選び、ユーザー定義文字サイズを6件まで登録可能
	- 有効な設定を`ystd-font-size-preset-1`〜`ystd-font-size-preset-6`としてテーマ標準プリセットの先頭へ追加
	- 固定値は単位なしの数値、単位付きの長さ、`calc()`、`clamp()`に対応
	- fluidはremまたはpxを選択し、WordPressのGlobal StylesでCSSを生成
- [追加] カスタマイザーの「[ys]ブロックエディター」に余白プリセット設定を追加
	- ユーザー定義余白を6件まで登録可能
	- 有効な設定を`ystd-spacing-preset-1`〜`ystd-spacing-preset-6`としてテーマ標準プリセットより前へ追加
	- 単位なしの数値、単位付きの長さ、`calc()`、`clamp()`、`min()`、`max()`に対応
	- WordPressのGlobal StylesでCSSカスタムプロパティを生成し、padding、margin、block gapなどの余白設定から選択可能
- [変更] ブロックエディターの文字サイズプリセットを`theme.json`へ一本化
	- `editor-font-sizes`テーマサポートと重複するフロント・エディター用CSS生成を削除
- [変更] カスタマイザー「[ys]デザイン」内の「サイト背景」を、トップレベルの「[ys]サイト背景」へ移動
	- サイト背景色・背景画像の保存値とセクションIDは引き継ぐ
- [変更] 「モバイル表示でサイドバーを非表示にする」を投稿タイプ別設定へ移動
	- 詳細ページとアーカイブページで個別に設定可能
	- v4の`ys_hide_sidebar_mobile`は、新しい設定が保存されるまで互換値として引き継ぐ
	- 旧「モバイル表示」セクションは削除
- [変更] カスタマイザーの「目次」をトップレベルの「[ys]目次」へ移動
	- 投稿タイプ別設定に「目次を自動で作成する」を追加
	- 新設定が未保存の場合はv4の投稿タイプ別無効化設定を参照
	- `[ys_toc]`ショートコードは維持し、目次ウィジェットと旧「[ys]デザイン」パネルは削除
- [変更] 設定初期値変更
	- 色を設定する項目の初期値を初期値なし（空白）に変更
	- デザイン -> 投稿ページ -> ページレイアウト : 1カラムをデフォルトに変更
	- デザイン -> 固定ページ -> ページレイアウト : 1カラムをデフォルトに変更
	- デザイン -> アーカイブページ -> ページレイアウト : 1カラムをデフォルトに変更
- [変更] 「CSSインライン読み込み」オプション削除
- [変更] カスタマイザー 「[ys]デザイン」-> 「フッター」 -> 「サブフッター上下余白」変更
- [変更] カスタマイザー 「[ys]デザイン」-> 「サイト背景色」 -> 「本文エリア背景色」仕様変更
	- 投稿・固定ページごとに設定する仕様に変更。本文エリア背景色を設定した場合、全幅ブロックがページいっぱいに広がらない仕様に変更
- [変更] カスタマイザー 「[ys]デザイン」-> 「アーカイブページ」仕様変更
	- 投稿・固定ページごとに設定する仕様に変更。
- [変更] カスタマイザーでの色設定の初期値を無し(空白)に変更
- [追加] グローバルメニュー文字サイズ設定追加(ys_global_nav_font_size).
- [追加] グローバルメニュー ホバー文字太さ設定追加（ys_global_nav_hover_current_text_weight）
- [追加] グローバルメニュー 2層目背景色設定追加（ys_global_nav_sub_menu_background_color）
- [追加] グローバルメニュー 2層目背景色不透明度設定追加（ys_global_nav_sub_menu_background_opacity）
- [追加] グローバルメニュー 2層目文字色設定追加（ys_global_nav_sub_menu_text_color）
- [追加] グローバルメニュー 2層目文字太さ設定追加（ys_global_nav_sub_menu_text_weight）
- [追加] グローバルメニュー 2層目ホバー・カレント文字色設定追加（ys_global_nav_sub_menu_hover_current_text_color）
- [追加] グローバルメニュー 2層目ホバー・カレント文字太さ設定追加（ys_global_nav_sub_menu_hover_current_text_weight）
- [追加] ドロワーメニュー 文字サイズ設定追加(ys_drawer_menu_font_size)
- [追加] ドロワーメニュー サブメニュー文字サイズ設定追加(ys_drawer_menu_sub_menu_font_size)
- [追加] アーカイブページ 表示タイプが「シンプル」のときにカテゴリーラベルの文字色・背景色の設定を追加(ys_{$post_type}_archive_simple_layout_category_text_color, ys_{$post_type}_archive_simple_layout_category_background_color)

##### HTML・CSS関連

- [変更] CSSカスタムプロパティ名変更
	- プレフィックスに`ystd`を追加
	- CSSカスタムプロパティ名の変更前・変更後は下記「v5.0.0 - カスタムプロパティ変換表」を参照
- [変更] カスタムプロパティの詳細度を変更 `:root` -> `body:where([class])`
- [変更] `.container`クラスの分解
	- `.content-container`
	- `.header-container`
	- `.sub-footer-container`
	- `.footer-container`
	- `.breadcrumbs-container`
	- `.footer-mobile-nav-container`
	- `.info-bar-container`
- [変更] クラス命変更
	- `footer-sub` -> `sub-footer`：CSSクラス等に影響あり
	- `footer-copy` -> `footer-copyright`：CSSクラス等に影響あり
	- `global-nav__dscr` -> `global-nav__description`：CSSクラス等に影響あり
	- `archive__dscr` -> `archive__description`：CSSクラス等に影響あり
	- `footer-mobile-nav__dscr` -> `footer-mobile-nav__description`：CSSクラス等に影響あり
- [変更] アーカイブ：ページネーションを`archive__main`の外側に移動.
- [追加] ブロックの設定で装飾無しにした場合、その中のテキストも装飾無しにするスタイル指定追加

#### v5.0.0 - カスタムプロパティ変換表

| 変更前                              | 変更後                                           |
|----------------------------------|-----------------------------------------------|
| --ystd-layout-gap                | --ystd--layout-gap                            |
| --ystd-container-margin-vertical | --ystd--container--margin-vertical            |
| --ystd-content-margin-bottom     | --ystd--content--margin-bottom                |
| --ystd-content-horizon-margin    | --ystd--content--margin-horizon               |
| --ystd-content-padding           | --ystd--content--padding                      |
| --ystd-body-padding-top          | --ystd--body--padding-top                     |
| --ystd-container-width           | --ystd--container--width                      |
| --ystd-container-gutter          | --ystd--container--gutter                     |
| --ystd-content-default-width     | --ystd--content--width                        |
| --ystd-content-min-width         | --ystd--content--min-width                    |
| --ystd-sidebar-max-width         | --ystd--sidebar--2col--max-width              |
| --ystd-sidebar-padding           | --ystd--sidebar--padding                      |
| --ystd-archive-gap               | --ystd--archive--gap                          |
| --ystd-archive-padding           | --ystd--archive--padding                      |
| --ystd-archive-item-width        | --ystd--archive--item--width                  |
| --ystd-archive-thumbnail-width   | --ystd--archive--thumbnail--width             |
| --ystd-block-gap                 | --ystd--block-gap                             |
| --font-family                    | --ystd--font-family                           |
| --font-family-code               | --ystd--font-family--code                     |
| --font-color                     | --ystd--text-color                            |
| --font-white                     | --ystd--text-color--white                     |
| --font-gray                      | --ystd--text-color--gray                      |
| --site-bg                        | --ystd--site--background                      |
| --site-bg-gray                   | --ystd--site--background--gray                |
| --site-bg-light-gray             | --ystd--site--background--light-gray          |
| --site-border-gray               | --ystd--site--border-color--gray              |
| --site-border-gray-light         | --ystd--site--border-color--light-gray        |
| --link-text                      | --ystd--link--text-color                      |
| --link-text-hover                | --ystd--link--text-color--hover               |
| --header-bg                      | --ystd--header--background                    |
| --header-text                    | --ystd--header--text-color                    |
| --header-dscr                    | --ystd--header--description-color             |
| --header-shadow                  | --ystd--header--shadow                        |
| --global-nav-search-cover        | --ystd--global-nav--search--cover--background |
| --global-nav-margin              | --ystd--global-nav--gap                       |
| --global-nav-bold                | --ystd--global-nav--font-weight               |
| --mobile-nav-container-padding   | --ystd--drawer-menu--container--padding-y     |
| --mobile-global-nav-width        | --ystd--drawer-menu--width                    |
| --mobile-nav-bg                  | --ystd--drawer-menu--background               |
| --mobile-nav-text                | --ystd--drawer-menu--text-color               |
| --mobile-nav-open                | --ystd--drawer-menu--button-color--open       |
| --mobile-nav-close               | --ystd--drawer-menu--button-color--close      |
| --breadcrumbs-text               | --ystd--breadcrumbs--text-color               |
| --info-bar-bg                    | --ystd--info-bar--background                  |
| --info-bar-text                  | --ystd--info-bar--text-color                  |
| --content-bg                     | --ystd--content--background                   |
| --content-meta                   | --ystd--content--meta--color                  |
| --ystd-archive-category-bg-color | --ystd--archive--category--background         |
| --post-paging-text               | --ystd--post-paging--text-color               |
| --pagination-text                | --ystd--pagination--text-color                |
| --advertisement-title            | --ystd--advertisement--title--text-color      |
| --toc-text                       | --ystd--toc--text-color                       |
| --toc-bg                         | --ystd--toc--background                       |
| --toc-border                     | --ystd--toc--border-color                     |
| --toc-list-border                | --ystd--toc--list--border-color               |
| --tagcloud-bg                    | --ystd--tagcloud--background                  |
| --tagcloud-text                  | --ystd--tagcloud--text-color                  |
| --tagcloud-icon                  | --ystd--tagcloud--icon                        |
| --fixed-sidebar-top              | --ystd--sidebar--fixed-position--top          |
| --footer-bg                      | --ystd--footer--background                    |
| --footer-text                    | --ystd--footer--text-color                    |
| --footer-text-gray               | --ystd--footer--text-color--gray              |
| --sub-footer-bg                  | --ystd--sub-footer--background                |
| --sub-footer-text                | --ystd--sub-footer--text-color                |
| --sub-footer-padding             | --ystd--sub-footer--padding                   |
| --mobile-footer-text             | --ystd--mobile-footer--text-color             |
| --mobile-footer-bg               | --ystd--mobile-footer--background             |
| --form-text                      | --ystd--form--text-color                      |
| --form-bg-white                  | --ystd--form--background                      |
| --form-border-gray               | --ystd--form--border-color                    |
| --ystd-button-text-color         | --ystd--button--text-color                    |
| --ystd-button-background-color   | --ystd--button--background-color              |
| --ystd-button-display            | --ystd--button--display                       |
| --ystd-button-padding            | --ystd--button--padding                       |
| --ystd-button-border-width       | --ystd--button--border-width                  |
| --ystd-button-border-style       | --ystd--button--border-style                  |
| --ystd-button-border-color       | --ystd--button--border-color                  |
| --ystd-button-border-radius      | --ystd--button--border-radius                 |
| --ystd-button-font-size          | --ystd--button--font-size                     |
| --ystd-button-box-shadow         | --ystd--button--box-shadow                    |
| --ystd-button-hover-text-color   | --ystd--button--hover--text-color             |
| --ystd-posts-item-gap            | --ystd--posts-item--gap                       |
| --ystd-posts-item-width          | --ystd--posts-item--width                     |
| --ystd-posts-inner-gap           | --ystd--posts--gap--inner                     |
| --ystd-posts-item-border         | --ystd--posts-item--border-color              |
| --ystd-posts-thumbnail-width     | --ystd--posts--thumbnail--width               |
| --ystd-posts-content-gap         | --ystd--posts--content--gap                   |
| --z-index-header                 | --ystd--z-index--header                       |
| --z-index-global-nav             | --ystd--z-index--drawer-nav                   |
| --z-index-global-nav-button      | --ystd--z-index--global-nav--button           |
| --z-index-global-nav-sub-menu    | --ystd--z-index--global-nav--sub-menu         |
| --z-index-mobile-footer          | --ystd--z-index--mobile-footer                |
| --z-index-back-to-top            | --ystd--z-index--back-to-top                  |
| --sns-color-\*                   | --ystd--sns--color--\*                        |

#### v5.0.0 - 廃止されたカスタムプロパティ

- --ystd-content-align-wide-width
- --ystd-sidebar-width
- --mobile-nav-toggle-top

#### v5.0.0 - 廃止されたオプション

- ys_drawer_menu_toggle_top：メニュー開閉ボタンの縦位置調整
- `ys-color-palette-ys-{標準色スラッグ}`：テーマ標準色を個別に変更する設定（ユーザー定義色の`ys-color-palette-ys-user-1`〜`ys-color-palette-ys-user-6`は継続）
- ys_color_content_bg：本文エリア背景色（投稿・固定ページ別の設定に変更）
- ys_show\_{post_type}\_header_category：投稿上部 カテゴリー情報の表示設定（表示するタクソノミーの選択に変更 ys\_{post_type}\_header_taxonomy）
- ys_share_button_type_header：シェアボタン表示設定（投稿タイプ別の設定に変更 ys\_{post_type}\_share_button_type_header）
- ys_share_button_type_footer：シェアボタン表示設定（投稿タイプ別の設定に変更 ys\_{post_type}\_share_button_type_footer）
- ys_show\_{post_type}\_category：記事下カテゴリー表示（投稿タイプ別に表示するタクソノミーの選択方式に変更）

#### v5.0.0 - 廃止された関数・クラスメソッド

- `ys_get_template_part()`：WordPressコアの`get_template_part()`へ移行
- `\ystandard\Template::get_template_part()`：WordPressコアの`get_template_part()`へ移行
- 独自APIが追加していた投稿タイプ・タクソノミー別テンプレート候補と絶対パス読み込みも廃止

#### v5.0.0 - 廃止されたフック

- `ys_get_template_part_slug`：テンプレートパーツのスラグを変更するフィルター
- `ys_get_template_part_name`：テンプレートパーツの名前を変更するフィルター
- `ys_get_template_part_args`：テンプレートパーツへ渡す引数を変更するフィルター
- `ys_customizer_color_palette`：カラーパレット設定への項目追加アクション
- `ys_customizer_custom_color_palette`：テーマ標準のカラーパレット設定出力を停止するフィルター
- `ys_editor_color_palette`：ブロックエディター用カラーパレットを変更するフィルター
- `ys_get_color_palette_css_types`：カラーパレット用CSSの出力形式を変更するフィルター
- `ys_is_enqueue_color_pallet`：フロント用カラーパレットCSSの出力状態を示すフィルター
- `ys_is_enqueue_block_editor_color_pallet`：ブロックエディター用カラーパレットCSSの出力状態を示すフィルター
- `ys_editor_font_sizes`：ブロックエディター用文字サイズプリセットを変更するフィルター
- `ys_is_enqueue_font_size`：フロント用文字サイズCSSの出力状態を示すフィルター
- `ys_is_enqueue_block_editor_font_size`：ブロックエディター用文字サイズCSSの出力状態を示すフィルター

#### v5.0.0 - ファイル移動表

| 変更前                                              | 変更後                                                     |
|--------------------------------------------------|---------------------------------------------------------|
| template-parts/footer/footer-sub.php             | template-parts/footer/sub-footer.php                    |
| template-parts/footer/footer-copy.php            | template-parts/copyright/copyright.php                  |
| template-parts/header/global-nav.php             | template-parts/navigation/global-nav.php                |
| template-parts/header/global-nav-search-form.php | template-parts/navigation/global-nav-search-form.php    |
| template-parts/parts/share-button.php            | template-parts/sns-share-button/share-button.php        |
| template-parts/parts/share-button-circle.php     | template-parts/sns-share-button/share-button-circle.php |
| template-parts/parts/share-button-icon.php       | template-parts/sns-share-button/share-button-icon.php   |
| template-parts/parts/share-button-official.php   | template-parts/sns-share-button/share-button-icon.php   |
| template-parts/parts/share-button-square.php     | template-parts/sns-share-button/share-button-icon.php   |

### v4以前の変更履歴

v4以前の変更履歴は以下をご確認ください。
[https://github.com/yosiakatsuki/ystandard/tree/master/docs/release-note/v4.md](https://github.com/yosiakatsuki/ystandard/tree/master/docs/release-note/v4.md)

## Third-party resources

### Simple Icons

License: CC0 - 1.0  
Source : <https://github.com/simple-icons/simple-icons>

### Feather

License: MIT  
Source : <https://github.com/feathericons/feather>

### Theme Update Checker Library

License: MIT License  
Source : <https://github.com/YahnisElsts/plugin-update-checker>

### \_decimal.scss

License: MIT License  
Source : <https://gist.github.com/terkel/4373420>
