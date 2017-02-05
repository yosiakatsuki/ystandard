<?php
//------------------------------------------------------------------------------
//
//	機能読み込み
//
//------------------------------------------------------------------------------

//設定
require_once TEMPLATEPATH . '/inc/theme-settings.php';
// utilities
require_once TEMPLATEPATH . '/inc/utilities.php';
// AMP
require_once TEMPLATEPATH . '/inc/amp.php';
// 初期化・初期設定
require_once TEMPLATEPATH . '/inc/setup.php';
// テーマカスタマイザー
require_once TEMPLATEPATH . '/inc/customizer.php';
// スクリプトの読み込み・停止
require_once TEMPLATEPATH . '/inc/enqueue.php';
// フィルタ関連
require_once TEMPLATEPATH . '/inc/extras.php';
//簡易VPカウント
require_once TEMPLATEPATH . '/inc/viewcount.php';
//投稿表示関連
require_once TEMPLATEPATH . '/inc/template-tags.php';
// コメント欄
require_once TEMPLATEPATH . '/inc/custom-comment.php';
// ページネーション
require_once TEMPLATEPATH . '/inc/pagination.php';
// パンくずリスト
require_once TEMPLATEPATH . '/inc/breadcrumb.php';
// ウィジェット
require_once TEMPLATEPATH . '/inc/widgets.php';
// 管理画面関連
require_once TEMPLATEPATH . '/inc/admin.php';
// 管理画面メニュー
if(is_admin()){
	require_once TEMPLATEPATH . '/inc/theme-option/theme-option-add.php';
	require_once TEMPLATEPATH . '/lib/theme-update-checker';
}



?>