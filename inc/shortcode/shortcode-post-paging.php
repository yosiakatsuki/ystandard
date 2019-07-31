<?php
/**
 * ショートコード: 前後の記事
 *
 * @package ystandard
 * @author  yosiakatsuki
 * @license GPL-2.0+
 */

/**
 * 前後の記事
 *
 * @param array $args パラメータ.
 *
 * @return string
 */
function ys_shortcode_post_paging( $args ) {
	$sc = new YS_Shortcode_Post_Paging( $args );

	return $sc->get_html();
}

add_shortcode( 'ys_post_paging', 'ys_shortcode_post_paging' );