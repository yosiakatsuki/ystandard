<?php
/**
 * フッター
 *
 * @package ystandard
 * @author yosiakatsuki
 * @license GPL-2.0+
 */

if ( ! function_exists( 'ys_the_uc_custom_append_body' ) ) {
	/**
	 * ユーザーカスタマイズフッター
	 */
	function ys_the_uc_custom_append_body() {
		ys_get_template_part( 'user-custom-append-body' );
	}
}
add_action( 'wp_footer', 'ys_the_uc_custom_append_body', 11 );
