<?php
/**
 * Post Class 関連
 *
 * @package ystandard
 * @author  yosiakatsuki
 * @license GPL-2.0+
 */

/**
 * Post Classを操作する
 *
 * @param  array $classes Classes.
 *
 * @return array
 */
function ys_ystd_post_class( $classes ) {
	/**
	 * [hentryの削除]
	 */
	if ( apply_filters( 'ystd_remove_hentry', true ) ) {
		$classes = array_diff( $classes, array( 'hentry' ) );
	}
	/**
	 * アイキャッチ画像の有無
	 */
	if ( is_singular() ) {
		if ( ys_is_active_post_thumbnail() ) {
			$classes[] = 'has-thumbnail';
		}
	}

	return $classes;
}

add_filter( 'post_class', 'ys_ystd_post_class' );



