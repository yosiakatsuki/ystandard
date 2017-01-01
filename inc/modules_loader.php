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
// サニタイズ
require_once TEMPLATEPATH . '/inc/sanitize.php';
// オプション取得
require_once TEMPLATEPATH . '/inc/option.php';
// 初期化・初期設定
require_once TEMPLATEPATH . '/inc/setup.php';
// テーマカスタマイザー
require_once TEMPLATEPATH . '/inc/customizer.php';
// スクリプトの読み込み・停止
require_once TEMPLATEPATH . '/inc/enqueue.php';
// フィルタ関連
require_once TEMPLATEPATH . '/inc/filter.php';
// 画像関連
require_once TEMPLATEPATH . '/inc/image.php';
//簡易VPカウント
require_once TEMPLATEPATH . '/inc/viewcount.php';
//WP_Query関連
require_once TEMPLATEPATH . '/inc/query.php';
//投稿表示関連
require_once TEMPLATEPATH . '/inc/template-tags.php';
// wp_head関連
require_once TEMPLATEPATH . '/inc/wphead.php';
// wp_footer関連
require_once TEMPLATEPATH . '/inc/wpfooter.php';
// コメント欄
require_once TEMPLATEPATH . '/inc/custom-comment.php';
// ページネーション
require_once TEMPLATEPATH . '/inc/pagination.php';
// パンくずリスト
require_once TEMPLATEPATH . '/inc/breadcrumb.php';
// html,headタグ部分
require_once TEMPLATEPATH . '/inc/ys_head_tag.php';
// 管理画面関連
require_once TEMPLATEPATH . '/inc/admin.php';
// クラスの出力など、スタイルに関係する部分
require_once TEMPLATEPATH . '/inc/style.php';
// 管理画面メニュー
if(is_admin()){
	require_once TEMPLATEPATH . '/inc/theme-option/theme-option-add.php';
}



?>