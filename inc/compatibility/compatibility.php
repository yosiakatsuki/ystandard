<?php
/**
 * 互換性に関する関数群
 *
 * @package ystandard
 * @author  yosiakatsuki
 * @license GPL-2.0+
 */

if ( ! function_exists( 'wp_body_open' ) ) {
	/**
	 * WP 5.2以前で wp_body_open を動作させる
	 */
	function wp_body_open() {
		do_action( 'wp_body_open' );
	}
}

if ( ! function_exists( 'wp_targeted_link_rel' ) ) {
	/**
	 * WP 5.1で追加関数
	 *
	 * @param string $content Content.
	 *
	 * @return mixed
	 */
	function wp_targeted_link_rel( $content ) {
		return $content;
	}
}

if ( ! function_exists( 'apply_shortcodes' ) ) {
	/**
	 * Search content for shortcodes and filter shortcodes through their hooks.
	 *
	 * This function is an alias for do_shortcode().
	 *
	 * @param string $content     Content to search for shortcodes.
	 * @param bool   $ignore_html When true, shortcodes inside HTML elements will be skipped.
	 *                            Default false.
	 *
	 * @return string Content with shortcodes filtered out.
	 * @see   do_shortcode()
	 *
	 * @since 5.4.0
	 *
	 */
	function apply_shortcodes( $content, $ignore_html = false ) {
		return do_shortcode( $content, $ignore_html );
	}
}
