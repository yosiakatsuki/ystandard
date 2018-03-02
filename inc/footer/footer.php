<?php
/**
 * @package ystandard
 * @author yosiakatsuki
 * @license GPL-2.0+
 */
/**
 * フッター
 */
if( ! function_exists( 'ys_the_uc_custom_append_body' ) ) {
	function ys_the_uc_custom_append_body() {
		get_template_part( 'user-custom-append-body' );
	}
}
add_action( 'wp_footer', 'ys_the_uc_custom_append_body', 11 );