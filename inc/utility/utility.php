<?php
/**
 * Utility
 *
 * @package ystandard
 * @author  yosiakatsuki
 * @license GPL-2.0+
 */

/**
 * クラス読み込み
 */
require_once __DIR__ . '/class-utility.php';

/**
 * 投稿タイプ取得
 */
function ys_get_post_type() {
	return ystandard\Content::get_post_type();
}

/**
 * 現在ページのURLを取得
 *
 * @return string
 */
function ys_get_page_url() {
	$protocol = 'https://';
	if ( ! is_ssl() ) {
		$protocol = 'http://';
	}

	return $protocol . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
}

/**
 * サイト内設定で使用するSNSのリスト
 *
 * @return array
 */
function ys_get_sns_icons() {
	return ystandard\SNS::get_sns_icons();
}


/**
 * 配列を区切り文字で文字列にして表示
 *
 * @param array  $arg       配列.
 * @param string $separator 区切り文字.
 */
function ys_the_array_implode( $arg, $separator = ', ' ) {
	echo ys_get_array_implode( $arg, $separator );
}

/**
 * 配列を区切り文字で文字列にして返却
 *
 * @param array  $arg       配列.
 * @param string $separator 区切り文字.
 *
 * @return string
 */
function ys_get_array_implode( $arg, $separator = ', ' ) {
	return implode( $separator, $arg );
}



/**
 * Feedly 購読URL作成
 *
 * @param string $type feed type.
 *
 * @return string
 */
function ys_get_feedly_subscribe_url( $type = '' ) {
	return 'https://feedly.com/i/subscription/feed/' . urlencode( get_feed_link( $type ) );
}

/**
 * [get_posts] で使うクエリパラメータを作る : 基本部分
 *
 * @param integer $posts_per_page 記事数.
 * @param array   $args           パラメータ.
 *
 * @return array
 */
function ys_get_posts_args( $posts_per_page = 4, $args = [] ) {
	$defaults = [
		'post_type'           => 'post',
		'posts_per_page'      => $posts_per_page,
		'no_found_rows'       => true,
		'ignore_sticky_posts' => true,
	];
	$args     = wp_parse_args( $args, $defaults );

	return $args;
}

/**
 * [get_posts] で使うクエリパラメータを作る : ランダムに取得
 *
 * @param integer $posts_per_page 記事数.
 * @param array   $args           パラメータ.
 *
 * @return array
 */
function ys_get_posts_args_rand( $posts_per_page = 4, $args = [] ) {
	$rand_args = [ 'orderby' => 'rand' ];
	$rand_args = wp_parse_args( $rand_args, $args );

	/**
	 * ランダムなクエリを取得
	 */
	return ys_get_posts_args( $posts_per_page, $rand_args );
}


/**
 * ファイルシステムの初期化
 *
 * @return bool|null
 */
function ys_init_filesystem() {
	global $wp_filesystem;
	if ( empty( $wp_filesystem ) ) {
		require_once ABSPATH . '/wp-admin/includes/file.php';
	}

	return WP_Filesystem();
}

/**
 * 配列チェック
 *
 * @param mixed $needle   needle.
 * @param array $haystack haystack.
 *
 * @return bool
 */
function ys_in_array( $needle, $haystack ) {
	return in_array( $needle, $haystack, true );
}

/**
 * Boolに変換
 *
 * @param mixed $value 変換する値.
 *
 * @return bool
 */
function ys_to_bool( $value ) {
	return ystandard\Utility::to_bool( $value );
}
