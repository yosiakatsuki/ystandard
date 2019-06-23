<?php
/**
 * ショートコード: フォローボックス
 *
 * @package ystandard
 * @author  yosiakatsuki
 * @license GPL-2.0+
 */

/**
 * フォローボックス表示
 *
 * @param array $args パラメータ.
 *
 * @return string
 */
function ys_shortcode_follow_box( $args ) {
	$sc = new YS_Shortcode_Follow_Box( $args );

	return $sc->get_html();
}

add_shortcode( 'ys_follow_box', 'ys_shortcode_follow_box' );