<?php
/**
 * ショートコードサンプル
 *
 * @package ystandard
 * @author yosiakatsuki
 * @license GPL-2.0+
 */

/**
 * サンプル
 */
function ys_shortcode_sample() {
	return "it's ys_shortcode_sample";
}
add_shortcode( 'ys_sample', 'ys_shortcode_sample' );
/**
 * サンプル変数指定
 *
 * @param array $args args.
 */
function ys_shortcode_sample_2( $args ) {
	/**
	 * デフォルト値をセットして、変数に代入
	 */
	$args = shortcode_atts(
		array(
			'arg' => 0,
		),
		$args
	);
	return $arg;
}
add_shortcode( 'ys_sample2', 'ys_shortcode_sample_2' );
/**
 * サンプル囲み
 *
 * @param array  $args args.
 * @param string $content content.
 */
function ys_shortcode_sample_3( $args, $content = null ) {
	if ( ! is_null( $content ) ) {
		$content = '<span>' . $content . '</span>';
	} else {
		$content = '';
	}
	return $content;
}
add_shortcode( 'ys_sample3', 'ys_shortcode_sample_3' );