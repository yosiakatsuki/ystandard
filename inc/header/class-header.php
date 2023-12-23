<?php
/**
 * ヘッダー
 *
 * @package ystandard
 * @author  yosiakatsuki
 * @license GPL-3.0+
 */

namespace ystandard;

defined( 'ABSPATH' ) || die();

/**
 * Class Header.
 *
 * @package ystandard
 */
class Header {

	/**
	 * ヘッダーロゴ取得
	 *
	 * @return string
	 */
	public static function get_header_logo() {
		$logo = sprintf(
			'<a href="%s" class="custom-logo-link" rel="home">%s</a>',
			esc_url( home_url( '/' ) ),
			get_bloginfo( 'name', 'display' )
		);

		// ロゴがある場合はロゴを表示.
		$logo = has_custom_logo() ? get_custom_logo() : $logo;

		$logo = apply_filters( 'ys_get_header_logo', $logo );
		// ヘッダーのサイトタイトルのHTMLタグ
		$tag = ( is_front_page() || is_home() || is_404() ) ? 'h1' : 'div';
		$tag = apply_filters( 'ys_get_header_logo_tag', $tag );

		return apply_filters(
			'ys_get_header_logo_html',
			"<{$tag} class=\"site-title\">{$logo}</{$tag}>"
		);
	}

	/**
	 * サイトキャッチフレーズ取得
	 *
	 * @return string
	 */
	public static function get_blog_description() {
		if ( Option::get_option_bool( 'ys_wp_hidden_blogdescription', false ) ) {
			return '';
		}

		$description = apply_filters(
			'ys_the_blog_description',
			get_bloginfo( 'description', 'display' )
		);

		if ( empty( $description ) ) {
			return '';
		}

		return sprintf(
			'<p class="site-description">%s</p>',
			$description
		);
	}
}
