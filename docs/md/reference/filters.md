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


## head

### ys_get_the_head_tag
- file: inc/head/head.php
- description: OGPパラメーター付きの<head>開始タグ編集用フィルタ

### ys_get_the_inline_style
- file: inc/head/head.php
- description: インラインCSSのカスタム用フィルタ


## wp_head

### ys_the_noindex
- file: inc/head/wp_head.php
- description: noindexの条件カスタム用フィルタ


## ys_amp_head

### ys_get_the_amp_document_title
- file: inc/amp/ys_amp_head.php
- description: AMPでのページタイトル編集フィルタ

### ys_the_amp_script
- file: inc/amp/ys_amp_head.php
- description: AMP用scriptタグ読み込みの編集フィルタ


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
