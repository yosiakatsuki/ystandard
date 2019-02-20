<?php
/**
 * 汎用テキスト ショートコード
 *
 * @package ystandard
 * @author  yosiakatsuki
 * @license GPL-2.0+
 */

/**
 * 汎用テキストショートコード
 *
 * @param array $args    パラメーター.
 * @param null  $content 内容.
 *
 * @return string
 */
function ys_sc_text_shortcode( $args, $content = null ) {
	$sc_text = new YS_Shortcode_Text( $args );

	return apply_filters(
		'ys_sc_text_shortcode',
		$sc_text->get_html( $content ),
		$sc_text->get_args()
	);
}

add_shortcode( 'ys_text_shortcode', 'ys_sc_text_shortcode' );