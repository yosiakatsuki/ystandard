<?php
/**
 * シェアボタン ショートコード
 *
 * @package ystandard
 * @author  yosiakatsuki
 * @license GPL-2.0+
 */

/**
 * シェアボタンショートコード
 *
 * @param array $args    パラメーター.
 *
 * @return string
 */
function ys_shortcode_share_button( $args ) {
	$sc_share_btn = new YS_Shortcode_Share_Button( $args );
	return $sc_share_btn->get_html();
}

add_shortcode( 'ys_share_button', 'ys_shortcode_share_button' );