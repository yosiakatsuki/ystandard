<?php
/**
 * クラス読み込み
 */
require_once get_template_directory() . '/inc/classes/widgets/class.ys-ad-text-widget.php';
require_once get_template_directory() . '/inc/classes/widgets/class.ys-ranking-widget.php';
require_once get_template_directory() . '/inc/classes/customizer/class.ys-image-label-radio.php';
require_once get_template_directory() . '/inc/classes/class.ys-enqueue.php';
require_once get_template_directory() . '/inc/classes/class.ys-global-nav.php';

/**
 *
 * 機能読み込み
 *
 */

/**
 * 変数
 */
require_once get_template_directory() . '/inc/variables/variables.php';
/**
 * 設定
 */
require_once get_template_directory() . '/inc/option/option.php';
/**
 * utilities
 */
require_once get_template_directory() . '/inc/util/util.php';
/**
 * sanitize
 */
require_once get_template_directory() . '/inc/sanitize/sanitize.php';
/**
 * 投稿タイプ
 */
require_once get_template_directory() . '/inc/post-type/post-type.php';
/**
 * 条件分岐
 */
require_once get_template_directory() . '/inc/conditional-branch/conditional-branch.php';
/**
 * 初期化
 */
require_once get_template_directory() . '/inc/init/init.php';
/**
 * enqueue
 */
require_once get_template_directory() . '/inc/enqueue/enqueue.php';
/**
 * post-meta
 */
require_once get_template_directory() . '/inc/post-meta/post-meta.php';
/**
 * テーマカスタマイザー
 */
require_once get_template_directory() . '/inc/customizer/customizer.php';
/**
 * テンプレート
 */
require_once get_template_directory() . '/inc/template/template.php';
/**
 * 投稿
 */
require_once get_template_directory() . '/inc/entry/entry-meta.php';
/**
 * アーカイブ
 */
require_once get_template_directory() . '/inc/archive/archive.php';
/**
 * <head>
 */
require_once get_template_directory() . '/inc/head/head.php';
/**
 * ヘッダー
 */
require_once get_template_directory() . '/inc/header/header.php';
/**
 * フッター
 */
require_once get_template_directory() . '/inc/footer/footer-sns.php';
/**
 * フィルター関連
 */
require_once get_template_directory() . '/inc/filters/content.php';
require_once get_template_directory() . '/inc/filters/body.php';
/**
 * 広告
 */
require_once get_template_directory() . '/inc/advertisement/advertisement.php';
/**
 * SNS
 */
require_once get_template_directory() . '/inc/sns/share-button.php';
require_once get_template_directory() . '/inc/sns/subscribe.php';
/**
 * author
 */
require_once get_template_directory() . '/inc/author/author.php';
require_once get_template_directory() . '/inc/author/author-option-page.php';
/**
 * パンくずリスト
 */
require_once get_template_directory() . '/inc/breadcrumbs/breadcrumbs.php';
/**
 * copyright
 */
require_once get_template_directory() . '/inc/copyright/copyright.php';
/**
 * カテゴリー関連
 */
require_once get_template_directory() . '/inc/category/category.php';
/**
 * AMP
 */
require_once get_template_directory() . '/inc/amp/amp-util.php';
require_once get_template_directory() . '/inc/amp/amp-convert.php';
require_once get_template_directory() . '/inc/amp/amp-filter.php';
require_once get_template_directory() . '/inc/amp/amp-head.php';
require_once get_template_directory() . '/inc/amp/amp-google-analytics.php';
/**
 * ショートコード
 */
require_once get_template_directory() . '/inc/shortcode/shortcode.php';
/**
 * ウィジェット
 */
require_once get_template_directory() . '/inc/widgets/widgets.php';
/**
 * 簡易VPカウント
 */
require_once get_template_directory() . '/inc/post-view/post-view.php';
/**
 * ページネーション
 */
require_once get_template_directory() . '/inc/pagination/pagination.php';


// utilities
require_once get_template_directory() . '/inc/utilities.php';



// フィルタ関連
require_once get_template_directory() . '/inc/extras.php';

//投稿表示関連
require_once get_template_directory() . '/inc/template-tags.php';
// コメント欄
require_once get_template_directory() . '/inc/custom-comment.php';






// 管理画面メニュー
if( is_admin() ){
	/**
	 * テーマ設定画面
	 */
	require_once get_template_directory() . '/inc/theme-option/theme-option-add.php';
	/**
	 * アップデートチェック
	 */
	require_once get_template_directory() . '/inc/update-checker/update-checker.php';
}


/**
 * v2でいずれ廃止予定
 */
//設定
require_once get_template_directory() . '/inc/migration-v1-v2/v1/theme-settings.php';