<?php
/**
 * ショートコード: 記事ランキング
 *
 * @package ystandard
 * @author  yosiakatsuki
 * @license GPL-2.0+
 */

/**
 * 記事ランキング
 *
 * @param array $args    パラメータ.
 * @param null  $content 内容.
 *
 * @return string
 */
function ys_shortcode_post_ranking( $args, $content = null ) {
	$shortcode = new YS_Shortcode_Post_Ranking( $args );

	return $shortcode->get_html( $content );
}
add_shortcode( 'ys_post_ranking', 'ys_shortcode_post_ranking' );
