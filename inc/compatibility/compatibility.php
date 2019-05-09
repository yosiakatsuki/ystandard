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