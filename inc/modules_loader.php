<?php
/**
 * クラス読み込み
 */
require_once get_template_directory() . '/inc/classes/widgets/class.ys-ad-text-widget.php';
require_once get_template_directory() . '/inc/classes/widgets/class.ys-ranking-widget.php';
require_once get_template_directory() . '/inc/classes/class.ys-enqueue.php';

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
 * utilities
 */
require_once get_template_directory() . '/inc/util/util.php';






// utilities
require_once get_template_directory() . '/inc/utilities.php';
// AMP
require_once get_template_directory() . '/inc/amp.php';
// 初期化・初期設定

// テーマカスタマイザー
require_once get_template_directory() . '/inc/customizer.php';

// フィルタ関連
require_once get_template_directory() . '/inc/extras.php';
//簡易VPカウント
require_once get_template_directory() . '/inc/viewcount.php';
//投稿表示関連
require_once get_template_directory() . '/inc/template-tags.php';
// コメント欄
require_once get_template_directory() . '/inc/custom-comment.php';
// ページネーション
require_once get_template_directory() . '/inc/pagination.php';
// パンくずリスト
require_once get_template_directory() . '/inc/breadcrumb.php';
// ウィジェット
require_once get_template_directory() . '/inc/widgets.php';
// ショートコード
require_once get_template_directory() . '/inc/shortcode.php';

/**
 * <head>タグ関連
 */
require_once get_template_directory() . '/inc/head/head.php';
require_once get_template_directory() . '/inc/head/wp_head.php';

/**
 * v2でいずれ廃止予定
 */
//設定
require_once get_template_directory() . '/inc/migration-v1-v2/v1/theme-settings.php';

// 管理画面メニュー
if( is_admin() ){
	require_once get_template_directory() . '/inc/theme-option/theme-option-add.php';
	require_once get_template_directory() . '/library/theme-update-checker/theme-update-checker.php';
	// 管理画面関連
	require_once get_template_directory() . '/inc/admin.php';
}