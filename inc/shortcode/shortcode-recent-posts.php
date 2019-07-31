<?php
/**
 * ショートコード: 新着記事一覧
 *
 * @package ystandard
 * @author  yosiakatsuki
 * @license GPL-2.0+
 */

/**
 * 新着記事一覧
 *
 * @param array $args    パラメーター.
 * @param null  $content 内容.
 *
 * @return string
 */
function ys_shortcode_recent_posts( $args, $content = null ) {
	$shortcode = new YS_Shortcode_Recent_Posts( $args );

	return $shortcode->get_html( $content );
}

add_shortcode( 'ys_recent_posts', 'ys_shortcode_recent_posts' );
add_shortcode( 'ys_tax_posts', 'ys_shortcode_recent_posts' ); // 旧ショートコードの互換.
