<?php
/**
 * Post Class 関連
 *
 * @package ystandard
 * @author yosiakatsuki
 * @license GPL-2.0+
 */

/**
 * [hentryクラスを削除する]
 *
 * @param  array $classes Classes.
 * @return array
 */
function ys_remove_hentry( $classes ) {
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
	return $classes;
}
add_filter( 'post_class', 'ys_remove_hentry' );