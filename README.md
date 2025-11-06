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
- 設定：モバイル表示：投稿タイプ別のレイアウト設定に移動

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
- [追加] `/block-styles.json`からブロックスタイルを追加できる機能追加.
- [変更] パンくずリストを404ページで表示しないように変更

##### 設定関連

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
- ys_color_content_bg：本文エリア背景色（投稿・固定ページ別の設定に変更）
- ys_show\_{post_type}\_header_category：投稿上部 カテゴリー情報の表示設定（表示するタクソノミーの選択に変更 ys\_{post_type}\_header_taxonomy）
- ys_share_button_type_header：シェアボタン表示設定（投稿タイプ別の設定に変更 ys\_{post_type}\_share_button_type_header）
- ys_share_button_type_footer：シェアボタン表示設定（投稿タイプ別の設定に変更 ys\_{post_type}\_share_button_type_footer）
- ys_show\_{post_type}\_category：記事下カテゴリー表示（投稿タイプ別に表示するタクソノミーの選択方式に変更）

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
