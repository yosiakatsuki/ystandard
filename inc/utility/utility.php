<?php
/**
 * Utility
 *
 * @package ystandard
 * @author  yosiakatsuki
 * @license GPL-2.0+
 */

if ( ! function_exists( 'ys_no_self_ping' ) ) {
	/**
	 * セルフピンバック対策
	 *
	 * @param array $links links.
	 *
	 * @return void
	 */
	function ys_no_self_ping( &$links ) {
		$home = home_url();
		foreach ( $links as $l => $link ) {
			if ( 0 === strpos( $link, $home ) ) {
				unset( $links[ $l ] );
			}
		}
	}
}
add_action( 'pre_ping', 'ys_no_self_ping' );

if ( ! function_exists( 'ys_get_theme_version' ) ) {
	/**
	 * テーマバージョン取得
	 *
	 * @param boolean $template 親テーマ情報かどうか.
	 */
	function ys_get_theme_version( $template = false ) {
		/**
		 * 子テーマ情報
		 */
		$theme = wp_get_theme();
		if ( $template && get_template() != get_stylesheet() ) {
			/**
			 * 親テーマ情報
			 */
			$theme = wp_get_theme( get_template() );
		}

		return $theme->get( 'Version' );
	}
}

/**
 * HTML・改行・ショートコードなしのテキストを取得
 *
 * @param string $data content.
 */
function ys_get_plain_text( $data ) {
	/**
	 * ショートコード削除
	 */
	$data = strip_shortcodes( $data );
	/**
	 * HTMLタグ削除
	 */
	$data = wp_strip_all_tags( $data, true );

	return $data;
}

if ( ! function_exists( 'ys_get_twitter_widgets_js' ) ) {
	/**
	 * Twitter用JavaScript URL取得
	 */
	function ys_get_twitter_widgets_js() {
		return apply_filters( 'ys_get_twitter_widgets_js', '//platform.twitter.com/widgets.js' );
	}
}

if ( ! function_exists( 'ys_get_facebook_sdk_js' ) ) {
	/**
	 * Facebook用JavaScript URL取得
	 */
	function ys_get_facebook_sdk_js() {
		return apply_filters( 'ys_get_facebook_sdk_js', '//connect.facebook.net/ja_JP/sdk.js#xfbml=1&version=v2.8' );
	}
}

if ( ! function_exists( 'ys_get_feedly_subscribe_url' ) ) {
	/**
	 * Feedly 購読URL作成
	 *
	 * @param string $type feed type.
	 */
	function ys_get_feedly_subscribe_url( $type = '' ) {
		return 'https://feedly.com/i/subscription/feed/' . urlencode( get_feed_link( $type ) );
	}
}

if ( ! function_exists( 'ys_get_posts_args' ) ) {
	/**
	 * Get_posts で使うクエリパラメータを作る : 基本部分
	 *
	 * @param  integer $posts_per_page 記事数.
	 * @param  array   $args           パラメータ.
	 *
	 * @return array
	 */
	function ys_get_posts_args( $posts_per_page = 4, $args = array() ) {
		$defaults = array(
			'post_type'           => 'post',
			'posts_per_page'      => $posts_per_page,
			'no_found_rows'       => true,
			'ignore_sticky_posts' => true,
		);
		$args     = wp_parse_args( $args, $defaults );

		return $args;
	}
}

if ( ! function_exists( 'ys_get_posts_args_rand' ) ) {
	/**
	 * Get_posts で使うクエリパラメータを作る : ランダムに取得
	 *
	 * @param  integer $posts_per_page 記事数.
	 * @param  array   $args           パラメータ.
	 *
	 * @return array
	 */
	function ys_get_posts_args_rand( $posts_per_page = 4, $args = array() ) {
		$rand_args = array( 'orderby' => 'rand' );
		$rand_args = wp_parse_args( $rand_args, $args );

		/**
		 * ランダムなクエリを取得
		 */
		return ys_get_posts_args( $posts_per_page, $rand_args );
	}
}

/**
 * ファイル内容の取得
 *
 * @param  string $file ファイルパス.
 *
 * @return string
 */
function ys_file_get_contents( $file ) {
	$content = false;
	if ( ys_init_filesystem() ) {
		global $wp_filesystem;
		$content = $wp_filesystem->get_contents( $file );
	}

	return $content;
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