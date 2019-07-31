<?php
/**
 * ブログカード ショートコード
 *
 * @package ystandard
 * @author  yosiakatsuki
 * @license GPL-2.0+
 */

/**
 * ブログカードショートコード
 *
 * @param array $args    パラメーター.
 *
 * @return string
 */
function ys_shortcode_blog_card( $args ) {
	$sc = new YS_Shortcode_Blog_Card( $args );
	return $sc->get_html();
}

add_shortcode( 'ys_blog_card', 'ys_shortcode_blog_card' );