<?php
/**
 * ショートコード: 投稿一覧
 *
 * @package ystandard
 * @author  yosiakatsuki
 * @license GPL-2.0+
 */

/**
 * 投稿一覧
 *
 * @param array $args    パラメータ.
 * @param null  $content 内容.
 *
 * @return string
 */
function ys_shortcode_get_posts( $args, $content = null ) {
	$shortcode = new YS_Shortcode_Get_Posts( $args );

	return $shortcode->get_html( $content );
}

add_shortcode( 'ys_get_posts', 'ys_shortcode_get_posts' );