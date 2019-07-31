<?php
/**
 * ショートコード: 投稿者一覧
 *
 * @package ystandard
 * @author yosiakatsuki
 * @license GPL-2.0+
 */

/**
 * 投稿者一覧表示
 *
 * @param array $args パラメータ.
 *
 * @return string
 */
function ys_shortcode_author_list( $args ) {
	$sc = new YS_Shortcode_Author_List( $args );

	return $sc->get_html();
}
add_shortcode( 'ys_author_list', 'ys_shortcode_author_list' );