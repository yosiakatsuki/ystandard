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
	/**
	 * 著者情報
	 */
	$flag = ! ys_is_display_author_data();
	if ( apply_filters( 'ys_remove_hentry_author', $flag ) ) {
		$classes = array_diff( $classes, array( 'hentry' ) );
	}

	/**
	 * 投稿日・更新日
	 */
	$flag = ! ys_is_active_publish_date();
	if ( apply_filters( 'ys_remove_hentry_publish_date', $flag ) ) {
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



