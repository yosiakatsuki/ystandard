# フィルターリファレンス

## option

### ys_get_options

- file: inc/option/option.php
- description: yStandard用設定一覧編集用フィルタ

### ys_get_option

- file: inc/option/option.php
- description: yStandard用設定取得内容編集用フィルタ

## init

### ys_custom_logo_param

- file: inc/init/init.php
- description: カスタムロゴのサイズ変更用フィルタ

## advertisement

### ys_advertisement_content

- file: inc/advertisement/advertisement.php
- file: inc/classes/widgets/class.ys-ad-text-widgets.php
- description: 広告内容の編集用フィルタ

### ys_ad_label_text

- file: inc/advertisement/advertisement.php
- description: 広告ラベル編集用フィルタ

### ys_get_ad_block_html

- file: inc/advertisement/advertisement.php
- description: 広告のコード共通フォーマット編集用フィルタ

### ys_get_ad_entry_header

- file: inc/advertisement/advertisement.php
- description: 記事上広告のコード編集用フィルタ

### ys_get_ad_more_tag

- file: inc/advertisement/advertisement.php
- description: 記事moreタグ部分の広告のコード編集用フィルタ

### ys_get_ad_entry_footer

- file: inc/advertisement/advertisement.php
- description: 記事下広告のコード編集用フィルタ

## conditional-branch

### ys_is_load_amp_ad_script

- file: inc/conditional-branch/conditional-branch.php
- description: AMP記事で広告用jsを読み込み判断の結果編集用フィルタ

### ys_is_deregister_jquery

- file: inc/conditional-branch/conditional-branch.php
- description: WordPressのjQueryを停止するかどうかの結果編集用フィルタ

### ys_is_load_cdn_jquery

- file: inc/conditional-branch/conditional-branch.php
- description: CDNのjQueryを読み込むかどうかの結果編集用フィルタ

### ys_is_enable_google_analytics

- file: inc/conditional-branch/conditional-branch.php
- description: Google Analyticsのタグを読み込むかの結果編集用フィルタ

### ys_is_active_sidebar_widget

- file: inc/conditional-branch/conditional-branch.php
- description: サイドバー部分の表示有無の編集用フィルタ

### ys_is_active_emoji

- file: inc/conditional-branch/conditional-branch.php
- description: 絵文字関連スクリプトの出力有無の編集用フィルタ

### ys_is_active_oembed

- file: inc/conditional-branch/conditional-branch.php
- description: oembed関連スクリプトの出力有無の編集用フィルタ

### ys_is_active_post_thumbnail

- file: inc/conditional-branch/conditional-branch.php
- description: アイキャッチ画像の出力有無の編集用フィルタ

### ys_is_active_entry_header_share

- file: inc/conditional-branch/conditional-branch.php
- description: 記事先頭シェアボタンの出力有無の編集用フィルタ

### ys_is_active_entry_footer_share

- file: inc/conditional-branch/conditional-branch.php
- description: 記事下シェアボタンの出力有無の編集用フィルタ

### ys_is_active_entry_footer_widget

- file: inc/conditional-branch/conditional-branch.php
- description: 記事下ウィジェットの出力有無の編集用フィルタ

### ys_is_active_entry_footer_author

- file: inc/conditional-branch/conditional-branch.php
- description: 記事下投稿者ボックスの出力有無の編集用フィルタ

### ys_is_active_footer_widgets

- file: inc/conditional-branch/conditional-branch.php
- description: フッターウィジェットの出力有無の編集用フィルタ

### ys_is_active_advertisement

- file: inc/conditional-branch/conditional-branch.php
- description: 広告の出力有無の編集用フィルタ

### ys_is_active_related_post

- file: inc/conditional-branch/conditional-branch.php
- description: 関連記事表示の編集用フィルタ

## head

### ys_get_the_head_tag

- file: inc/head/head.php
- description: OGPパラメーター付きのhead開始タグ編集用フィルタ

### ys_get_the_inline_style

- file: inc/head/head.php
- description: インラインCSSのカスタム用フィルタ

### ys_the_noindex

- file: inc/head/head.php
- description: noindexの条件カスタム用フィルタ

### ys_get_the_ogp

- file: inc/head/head.php
- description: OGPのmetaタグ編集用フィルタ

### ys_get_the_twitter_card

- file: inc/head/head.php
- description: Twitterカードのmetaタグ編集用フィルタ

### ys_get_ogp_and_twitter_card_param

- file: inc/head/head.php
- description: OGP/Twitterカードの各種パラメータ編集用フィルタ

### ys_get_google_anarytics_tracking_id

- file: inc/head/head.php
- description: Google AnalyticsトラッキングID編集フィルタ

## image

### ys_get_the_image_object

- file: inc/image/image.php
- description: 投稿内で使われている画像のオブジェクト取得結果の編集フィルタ

## template

### ys_get_front_page_template

- file: inc/template/tempalte.php
- description: front-pageで読み込むテンプレートファイルの編集フィルタ

### ys_get_entry_footer_template

- file: inc/template/tempalte.php
- description: 記事フッターで読み込むテンプレートファイルの編集フィルタ

## share-button

### ys_get_share_button_data

- file: inc/sns/share-button.php
- description: シェアボタン用データの編集フィルタ

### ys_show_share_button_twitter

- file: inc/sns/share-button.php
- description: Twitterシェアボタンの表示有無編集フィルタ

### ys_show_share_button_facebook

- file: inc/sns/share-button.php
- description: Facebookシェアボタンの表示有無編集フィルタ

### ys_show_share_button_hatenabookmark

- file: inc/sns/share-button.php
- description: はてなブックマークシェアボタンの表示有無編集フィルタ

### ys_sns_share_button_googlepuls

- file: inc/sns/share-button.php
- description: Google+シェアボタンの表示有無編集フィルタ

### ys_sns_share_button_pocket

- file: inc/sns/share-button.php
- description: Pocketシェアボタンの表示有無編集フィルタ

### ys_sns_share_button_line

- file: inc/sns/share-button.php
- description: LINEシェアボタンの表示有無編集フィルタ

### ys_show_share_button_feedly

- file: inc/sns/share-button.php
- description: Feedlyボタンの表示有無編集フィルタ

### ys_show_share_button_rss

- file: inc/sns/share-button.php
- description: Feedlyボタンの表示有無編集フィルタ

### ys_share_tweet_via_account

- file: inc/sns/share-button.php
- description: Twitterシェアボタンのviaアカウント編集フィルタ

### ys_share_tweet_related_account

- file: inc/sns/share-button.php
- description: Twitterシェアボタンの関連アカウント編集フィルタ

### ys_twitter_button_text

- file: inc/sns/share-button.php
- description: Twitterボタンテキスト編集フィルタ

### ys_facebook_button_text

- file: inc/sns/share-button.php
- description: Facebookボタンテキスト編集フィルタ

### ys_hatenabookmark_button_text

- file: inc/sns/share-button.php
- description: はてブボタンテキスト編集フィルタ

### ys_googleplus_button_text

- file: inc/sns/share-button.php
- description: Google+ボタンテキスト編集フィルタ

### ys_pocket_button_text

- file: inc/sns/share-button.php
- description: Pocketボタンテキスト編集フィルタ

### ys_line_button_text

- file: inc/sns/share-button.php
- description: LINEボタンテキスト編集フィルタ

### ys_feedly_button_text

- file: inc/sns/share-button.php
- description: Feedlyボタンテキスト編集フィルタ

### ys_rss_button_text

- file: inc/sns/share-button.php
- description: RSSボタンテキスト編集フィルタ

## subscribe

### ys_get_subscribe_buttons

- file: inc/sns/subscribe.php
- description: 購読ボタン表示一覧配列編集フィルタ

### ys_get_subscribe_background_image

- file: inc/sns/subscribe.php
- description: 購読ボタン背景画像編集フィルタ

## author

### ys_get_author_sns_list

- file: inc/author/author.php
- description: 投稿者SNS一覧配列編集フィルタ

### ys_get_author_avatar

- file: inc/author/author.php
- description: 投稿者プロフィール画像編集フィルタ

## entry

### ys_get_entry_date

- file: inc/entry/entry-meta.php
- description: 公開日・更新日取得用配列の編集フィルタ

## header

### ys_get_header_logo

- file: inc/header/header.php
- description: ヘッダーロゴ編集用フィルタ

### ys_get_header_logo_format

- file: inc/header/header.php
- description: aタグを含むヘッダーロゴのラッパーHTMLフォーマット編集フィルタ

### ys_the_blog_description

- file: inc/header/header.php
- description: ヘッダーキャッチフレーズ編集用フィルタ

### ys_the_blog_description_format

- file: inc/header/header.php
- description: ヘッダーキャッチフレーズのラッパーHTMLフォーマット編集フィルタ

### ys_get_header_type_class

- file: inc/header/header.php
- description: ヘッダータイプ変更用クラス編集フィルタ

## archive

### ys_get_the_archive_title

- file: inc/archive/archive.php
- description: archiveページのタイトル編集用フィルタ

## footer-sns

### ys_get_footer_sns_list

- file: inc/footer/footer-sns.php
- description: フッターに表示するSNSフォロー用リンクの配列編集用フィルタ

## post-meta

### ys_get_post_meta

- file: inc/post-meta/post-meta.php
- description: get_post_meta結果の編集フィルタ

## breadcrumbs

### ys_get_breadcrumbs

- file: inc/breadcrumbs/breadcrumbs.php
- description: パンくずリスト用配列編集フィルタ

### ys_set_breadcrumb_item

- file: inc/breadcrumbs/breadcrumbs.php
- description: パンくずリスト用配列作成処理フィルタ

### ys_get_breadcrumb_ancestors

- file: inc/breadcrumbs/breadcrumbs.php
- description: パンくずリスト用 親項目取得・並び替え結果 処理フィルタ

### ys_set_breadcrumb_ancestors

- file: inc/breadcrumbs/breadcrumbs.php
- description: パンくずリスト用 親項目取得・並び替え結果セット 処理フィルタ

## pagination

### ys_get_pagination

- file: inc/pagination/pagination.php
- description: ページネーション用配列編集フィルタ

## customizer-color

### ys_customize_css

- file: inc/customizer/customizer-color.php
- description: カスタマイザーの色設定を使用したインラインCSS編集フィルタ

## utility

### ys_get_the_custom_excerpt

- file: inc/utility/utility.php
- description: カスタム投稿抜粋の結果編集用フィルタ

## amp-utility

### ys_amp_convert_image

- file: inc/amp/amp-utility.php
- description: AMP用画像変換の結果編集フィルタ

## amp-head

### ys_get_the_amp_document_title

- file: inc/amp/amp-head.php
- description: AMPでのページタイトル編集フィルタ

### ys_the_amp_script

- file: inc/amp/amp-head.php
- description: AMP用scriptタグ読み込みの編集フィルタ

## amp-google-analytics

### ys_get_amp_google_anarytics_tracking_id

- file: inc/amp/amp-google-analytics.php
- description: AMP用Google AnalyticsトラッキングID編集フィルタ

## blog-card

### ys_blog_card_thumbnail_size

- file: inc/blog-card/blog-card.php
- description: ブログカードで使用するサムネイル画像のサイズ編集フィルタ

### ys_blog_card_thumbnail

- file: inc/blog-card/blog-card.php
- description: ブログカードで使用するサムネイル画像の編集フィルタ

### ys_blog_card_site_thumbnail

- file: inc/blog-card/blog-card.php
- description: ブログカードで使用する外部サイトのサムネイル画像の編集フィルタ（通常は空白）

### ys_blog_card_dscr_length

- file: inc/blog-card/blog-card.php
- description: ブログカードで使用する外部サイトのサムネイル画像の編集フィルタ（通常は空白）

## shortcode

### ys_shortcode_author_id

- file: inc/shortcode/shortcode-author.php
- description: 投稿者表示ショートコード 投稿者ID編集フィルタ

### ys_shortcode_author_html

- file: inc/shortcode/shortcode-author.php
- description: 投稿者表示ショートコード結果編集フィルタ

## class-ys-enqueue

### ys_enqueue_minify_css

- file: inc/classes/class.ys-enqueue.php
- description: インラインCSSのminify結果の編集フィルタ

### ys_enqueue_inline_styles

- file: inc/classes/class.ys-enqueue.php
- description: インラインCSSを作成する前にインライン化するCSSの配列を操作する為のフィルタ

### ys_enqueue_non_critical_css

- file: inc/classes/class.ys-enqueue.php
- description: ファーストビュー以外のCSSの配列を操作する為のフィルタ

### ys_enqueue_onload_scripts

- file: inc/classes/class.ys-enqueue.php
- description: onloadイベントで読み込むjavascriptの配列を操作する為のフィルタ

### ys_enqueue_lazyload_scripts

- file: inc/classes/class.ys-enqueue.php
- description: スクロール発火で読み込むjavascriptの配列を操作する為のフィルタ

### ys_enqueue_lazyload_css

- file: inc/classes/class.ys-enqueue.php
- description: スクロール発火で読み込むcssの配列を操作する為のフィルタ

## class-ys-post-list

### ys_post_list_thmbnail_size

- file: inc/classes/class-ys-post-list.php
- description: 投稿リストの画像サイズ編集フィルタ

### ys_ranking_widget_image

- file: inc/classes/class-ys-post-list.php
- description: 投稿リストの画像タグ編集フィルタ

### ys_post_list_item

- file: inc/classes/class.ys-ranking-widget.php
- description: 投稿リストの投稿HTMLタグ編集フィルタ

### ys_post_list_warp

- file: inc/classes/class.ys-ranking-widget.php
- description: 投稿リストのラッパーHTMLタグ編集フィルタ

## class-ys-ranking-widget

### ys_ranking_widget_option

- file: inc/classes/class.ys-ranking-widget.php
- description: ランキングウィジェットのパラメータ編集フィルタ
