<?php
/**
 * 投稿・ページ関連の関数
 *
 * @package ystandard
 * @author  yosiakatsuki
 * @license GPL-3.0+
 */
defined( 'ABSPATH' ) || die();


/**
 * 投稿ヘッダー情報を隠すか
 */
function ys_is_active_post_header() {
	return \ystandard\Post_Header::is_active_post_header();
}

/**
 * 投稿抜粋文を作成
 *
 * @param string $sep 抜粋最後の文字.
 * @param integer $length 抜粋長さ.
 * @param integer $post_id 投稿ID.
 *
 * @return string
 */
function ys_get_custom_excerpt( $sep = ' …', $length = 0, $post_id = 0 ) {
	return \ystandard\utils\Post::get_custom_excerpt( $sep, $length, $post_id );
}


/**
 * 投稿フッター情報を隠すか
 */
function ys_is_active_post_footer() {
	return \ystandard\Post_Footer::is_active_post_footer();
}


