<?php
/**
 * ショートコード: 投稿者
 *
 * @package ystandard
 * @author  yosiakatsuki
 * @license GPL-2.0+
 */

/**
 * 投稿者表示
 *
 * @param array $args パラメータ.
 *
 * @return string
 */
function ys_shortcode_author_box( $args ) {
	$sc_author = new YS_Shortcode_Author_Box( $args );

	return $sc_author->get_html();
}

add_shortcode( 'ys_author', 'ys_shortcode_author_box' );