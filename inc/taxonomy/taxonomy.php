<?php
/**
 * タクソノミー全般に関連する関数
 *
 * @package ystandard
 * @author yosiakatsuki
 * @license GPL-2.0+
 */

/**
 * タクソノミー説明の処理カスタマイズ
 */
function ys_tax_dscr_allowed_option() {
	remove_filter( 'pre_term_description', 'wp_filter_kses' );
	add_filter( 'pre_term_description', 'wp_filter_post_kses' );
	if ( ! is_admin() ) {
		add_filter( 'term_description', 'do_shortcode' );
	}
}