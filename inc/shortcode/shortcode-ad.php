<?php
/**
 * ショートコード: 404と検索0件で非表示になるユニット
 *
 * @package ystandard
 * @author yosiakatsuki
 * @license GPL-2.0+
 */

/**
 * 404と検索0件で非表示になるユニット
 *
 * @param array  $args    パラメーター.
 * @param string $content コンテンツ.
 * @return string
 */
function ys_shortcode_ad_block( $args, $content = null ) {
	if ( is_null( $content ) ) {
		return '';
	}
	global $wp_query;
	$html = '';
	$args = shortcode_atts(
		array(
			'id'    => '',
			'class' => 'ys-ad-block',
		),
		$args
	);
	/**
	 * パラメータ取得
	 */
	$id    = '';
	$class = '';
	if ( '' !== $args['id'] ) {
		$id = sprintf( ' id="%s"', $args['id'] );
	}
	if ( '' !== $args['class'] ) {
		$class = sprintf( ' class="%s"', $args['class'] );
	}
	if ( ! is_404() && ! ( is_search() && 0 == $wp_query->found_posts ) && ys_is_active_advertisement() ) {
		$html = sprintf(
			'<div%s%s>%s</div>',
			$id,
			$class,
			$content
		);
	}
	return apply_filters( 'ys_shortcode_ad_block', $html, $args['id'] );
}
add_shortcode( 'ys_ad_block', 'ys_shortcode_ad_block' );
