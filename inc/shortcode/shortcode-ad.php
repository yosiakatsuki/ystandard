<?php
/**
 * 広告表示用ショートコード
 *
 * @package ystandard
 * @author  yosiakatsuki
 * @license GPL-2.0+
 */

/**
 * 広告表示用ショートコード
 *
 * @param array  $args    パラメーター.
 * @param string $content コンテンツ.
 *
 * @return string
 */
function ys_shortcode_ad_block( $args, $content = null ) {
	$sc_ad = new YS_Shortcode_Advertisement( $args );
	return apply_filters(
		'ys_ad_block',
		$sc_ad->get_html( $content ),
		$sc_ad->get_args()
	);
}

add_shortcode( 'ys_ad_block', 'ys_shortcode_ad_block' );
