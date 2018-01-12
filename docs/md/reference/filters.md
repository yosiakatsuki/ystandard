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


## conditional-branch

### ys_is_load_amp_ad_js
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


## head

### ys_get_the_head_tag
- file: inc/head/head.php
- description: OGPパラメーター付きの<head>開始タグ編集用フィルタ

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


## archive

### ys_get_the_archive_title
- file: inc/archive/archive.php
- description: archiveページのタイトル編集用フィルタ

### ys_the_amp_script
- file: inc/amp/ys_amp_head.php
- description: AMP用scriptタグ読み込みの編集フィルタ


## footer-sns

### ys_get_footer_sns_list
- file: inc/footer/footer-sns.php
- description: フッターに表示するSNSフォロー用リンクの配列編集用フィルタ


## post-meta

### ys_get_post_meta
- file: inc/post-meta/post-meta.php
- description: get_post_meta結果の編集フィルタ


## customizer-color

### ys_customize_css
- file: inc/customizer/customizer-color.php
- description: カスタマイザーの色設定を使用したインラインCSS編集フィルタ


## util

### ys_util_get_the_custom_excerpt
- file: inc/util/util.php
- description: カスタム投稿抜粋の結果編集用フィルタ


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


## class.ys-enqueue

### ys_enqueue_minify_css
- file: inc/classes/class.ys-styles.php
- description: インラインCSSのminify結果の編集フィルタ

### ys_enqueue_inline_styles
- file: inc/classes/class.ys-styles.php
- description: インラインCSSを作成する前にインライン化するCSSの配列を操作する為のフィルタ

### ys_enqueue_non_critical_css
- file: inc/classes/class.ys-styles.php
- description: ファーストビュー以外のCSSの配列を操作する為のフィルタ

### ys_enqueue_onload_scripts
- file: inc/classes/class.ys-styles.php
- description: onloadイベントで読み込むjavascriptの配列を操作する為のフィルタ

### ys_enqueue_lazyload_scripts
- file: inc/classes/class.ys-styles.php
- description: スクロール発火で読み込むjavascriptの配列を操作する為のフィルタ

### ys_enqueue_lazyload_css
- file: inc/classes/class.ys-styles.php
- description: スクロール発火で読み込むcssの配列を操作する為のフィルタ
