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
require_once dirname( __FILE__ ) . '/class-ys-utility.php';

/**
 * 投稿タイプ取得
 */
function ys_get_post_type() {
	global $wp_query;
	$post_type = get_post_type();
	if ( ! $post_type ) {
		if ( isset( $wp_query->query['post_type'] ) ) {
			$post_type = $wp_query->query['post_type'];
		}
	}

	return $post_type;
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
	return \ystandard\SNS::get_sns_icons();
}

/**
 * Font Awesome CSS
 *
 * @return string
 */
function ys_get_font_awesome_css_url() {
	return ys_get_theme_file_uri( '/library/fontawesome/css/all.css' );
}

/**
 * Font Awesome CDN - CSS
 *
 * @return string
 */
function ys_get_font_awesome_cdn_css_url() {
	$version = apply_filters(
		'ys_get_font_awesome_css_version',
		YS_Utility::get_font_awesome_version()
	);

	return apply_filters(
		'ys_get_font_awesome_cdn_css_url',
		'https://use.fontawesome.com/releases/' . $version . '/css/all.css',
		$version
	);
}

/**
 * Font Awesome - SVG 軽量版
 *
 * @return string
 */
function ys_get_font_awesome_svg_light_url() {

	$file_url = apply_filters(
		'ys_get_font_awesome_svg_light_url',
		ys_get_theme_file_uri( '/js/font-awesome-ystd.js' )
	);

	return $file_url;
}

/**
 * Font Awesome - SVG
 *
 * @return string
 */
function ys_get_font_awesome_svg_url() {

	$file_url = ys_get_theme_file_uri( '/library/fontawesome/js/all.js' );

	return $file_url;
}

/**
 * Font Awesome CDN - SVG
 *
 * @return string
 */
function ys_get_font_awesome_cdn_svg_url() {
	$version = apply_filters(
		'ys_get_font_awesome_cdn_svg_version',
		YS_Utility::get_font_awesome_version()
	);

	return apply_filters(
		'ys_get_font_awesome_cdn_svg_url',
		'https://use.fontawesome.com/releases/' . $version . '/js/all.js',
		$version
	);
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
 * テーマバージョン取得
 *
 * @param boolean $template 親テーマ情報かどうか.
 *
 * @return string
 */
function ys_get_theme_version( $template = false ) {
	/**
	 * 子テーマ情報
	 */
	$theme = wp_get_theme();
	if ( $template && get_template() !== get_stylesheet() ) {
		/**
		 * 親テーマ情報
		 */
		$theme = wp_get_theme( get_template() );
	}

	return $theme->get( 'Version' );
}

/**
 * HTML・改行・ショートコードなしのテキストを取得
 *
 * @param string $data content.
 *
 * @return string
 */
function ys_get_plain_text( $data ) {

	return ystandard\Template_Function::get_plain_text( $data );
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
 * テーマ内のファイルURLを取得する
 *
 * @param string $file ファイルパス.
 *
 * @return string
 */
function ys_get_theme_file_uri( $file ) {
	/**
	 * 4.7以下の場合 親テーマのファイルを返す
	 */
	if ( version_compare( $GLOBALS['wp_version'], '4.7-alpha', '<' ) ) {
		return get_template_directory_uri() . $file;
	}

	return get_theme_file_uri( $file );
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
	return \ystandard\Template_Function::to_bool( $value );
}
