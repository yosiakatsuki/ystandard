<?php
/**
 * Utility
 *
 * @package ystandard
 * @author  yosiakatsuki
 * @license GPL-2.0+
 */

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
	return apply_filters(
		'ys_get_sns_icons',
		array(
			'twitter'   => array(
				'class'      => 'twitter',
				'option_key' => 'twitter',
				'icon'       => 'fab fa-twitter',
				'color'      => 'twitter',
				'title'      => 'twitter',
				'label'      => 'Twitter',
			),
			'facebook'  => array(
				'class'      => 'facebook',
				'option_key' => 'facebook',
				'icon'       => 'fab fa-facebook-f',
				'color'      => 'facebook',
				'title'      => 'facebook',
				'label'      => 'Facebook',
			),
			'instagram' => array(
				'class'      => 'instagram',
				'option_key' => 'instagram',
				'icon'       => 'fab fa-instagram',
				'color'      => 'instagram',
				'title'      => 'instagram',
				'label'      => 'Instagram',
			),
			'tumblr'    => array(
				'class'      => 'tumblr',
				'option_key' => 'tumblr',
				'icon'       => 'fab fa-tumblr',
				'color'      => 'tumblr',
				'title'      => 'tumblr',
				'label'      => 'Tumblr',
			),
			'youtube'   => array(
				'class'      => 'youtube',
				'option_key' => 'youtube',
				'icon'       => 'fab fa-youtube',
				'color'      => 'youtube-play',
				'title'      => 'youtube',
				'label'      => 'YouTube',
			),
			'github'    => array(
				'class'      => 'github',
				'option_key' => 'github',
				'icon'       => 'fab fa-github',
				'color'      => 'github',
				'title'      => 'github',
				'label'      => 'GitHub',
			),
			'pinterest' => array(
				'class'      => 'pinterest',
				'option_key' => 'pinterest',
				'icon'       => 'fab fa-pinterest-p',
				'color'      => 'pinterest',
				'title'      => 'pinterest',
				'label'      => 'Pinterest',
			),
			'linkedin'  => array(
				'class'      => 'linkedin',
				'option_key' => 'linkedin',
				'icon'       => 'fab fa-linkedin-in',
				'color'      => 'linkedin',
				'title'      => 'linkedin',
				'label'      => 'LinkedIn',
			),
			'amazon'    => array(
				'class'      => 'amazon',
				'option_key' => 'amazon',
				'icon'       => 'fab fa-amazon',
				'color'      => 'amazon',
				'title'      => 'amazon',
				'label'      => 'Amazon',
			),
		)
	);
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
		'v5.11.2'
	);

	return apply_filters(
		'ys_get_font_awesome_cdn_css_url',
		'https://use.fontawesome.com/releases/' . $version . '/css/all.css',
		$version
	);
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
	$version = apply_filters( 'ys_get_font_awesome_cdn_svg_version', 'v5.11.2' );

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

add_action( 'pre_ping', 'ys_no_self_ping' );


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

/**
 * Twitter用JavaScript URL取得
 *
 * @return string
 */
function ys_get_twitter_widgets_js() {
	return apply_filters( 'ys_get_twitter_widgets_js', '//platform.twitter.com/widgets.js' );
}

/**
 * Facebook用JavaScript URL取得
 *
 * @return string
 */
function ys_get_facebook_sdk_js() {
	return apply_filters( 'ys_get_facebook_sdk_js', '//connect.facebook.net/ja_JP/sdk.js#xfbml=1&version=v2.8' );
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

/**
 * [get_posts] で使うクエリパラメータを作る : ランダムに取得
 *
 * @param integer $posts_per_page 記事数.
 * @param array   $args           パラメータ.
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

/**
 * ファイル内容の取得
 *
 * @param string $file ファイルパス.
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

/**
 * 非推奨メッセージを表示する
 *
 * @param string $func    関数.
 * @param string $since   いつから.
 * @param string $comment コメント.
 */
function ys_deprecated( $func, $since, $comment = '' ) {
	$message = sprintf(
		'<span style="color:red"><code>%s</code>は%sで非推奨になった関数です。</span>',
		$func,
		$since
	);
	if ( $comment ) {
		$message .= '<br><span style="color:#999">' . $comment . '</span>';
	}
	echo $message;
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