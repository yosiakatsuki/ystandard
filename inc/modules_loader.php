<?php
/**
 * クラス読み込み
 */
require_once get_template_directory() . '/inc/classes/widgets/class.ys-ad-text-widget.php';
require_once get_template_directory() . '/inc/classes/widgets/class.ys-ranking-widget.php';
require_once get_template_directory() . '/inc/classes/class.ys-styles.php';

/**
 *
 * 機能読み込み
 *
 */

/**
 * 変数
 */
require_once get_template_directory() . '/inc/variables/variables.php';


//設定
require_once get_template_directory() . '/inc/theme-settings.php';
// utilities
require_once get_template_directory() . '/inc/utilities.php';
// AMP
require_once get_template_directory() . '/inc/amp.php';
// 初期化・初期設定
require_once get_template_directory() . '/inc/setup.php';
// テーマカスタマイザー
require_once get_template_directory() . '/inc/customizer.php';
// スクリプトの読み込み・停止
require_once get_template_directory() . '/inc/enqueue.php';
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

// 管理画面メニュー
if(is_admin()){
	require_once get_template_directory() . '/inc/theme-option/theme-option-add.php';
	require_once get_template_directory() . '/library/theme-update-checker/theme-update-checker.php';
	// 管理画面関連
	require_once get_template_directory() . '/inc/admin.php';
}