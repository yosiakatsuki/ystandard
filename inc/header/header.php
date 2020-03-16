<?php
/**
 * ヘッダー関連
 *
 * @package ystandard
 * @author  yosiakatsuki
 * @license GPL-2.0+
 */

/**
 * ヘッダーロゴ取得
 */
function ys_get_header_logo() {
	if ( has_custom_logo() && ! ys_get_option_by_bool( 'ys_logo_hidden', false ) ) {
		$logo = ys_get_custom_logo();
	} else {
		$logo = get_bloginfo( 'name' );
	}

	return sprintf(
		'<a href="' . esc_url( home_url( '/' ) ) . '" rel="home">%s</a>',
		apply_filters( 'ys_get_header_logo', $logo )
	);
}

/**
 * サイトキャッチフレーズを取得
 */
function ys_the_blog_description() {
	if ( ys_get_option_by_bool( 'ys_wp_hidden_blogdescription', false ) ) {
		return;
	}
	echo sprintf(
		'<p class="site-description header__dscr text-sub">%s</p>',
		apply_filters( 'ys_the_blog_description', get_bloginfo( 'description', 'display' ) )
	);
}
