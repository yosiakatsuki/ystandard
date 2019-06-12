<?php
/**
 * ショートコード: 投稿カテゴリー・タグ表示
 *
 * @package ystandard
 * @author  yosiakatsuki
 * @license GPL-2.0+
 */

/**
 * 投稿カテゴリー・タグ表示
 *
 * @param array $args パラメータ.
 *
 * @return string
 */
function ys_shortcode_ys_post_tax( $args ) {
	$sc = new YS_Shortcode_Post_Taxonomy( $args );

	return $sc->get_html();
}

add_shortcode( 'ys_post_tax', 'ys_shortcode_ys_post_tax' );