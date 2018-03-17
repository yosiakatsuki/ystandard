<?php
/**
 * Body関連
 *
 * @package ystandard
 * @author yosiakatsuki
 * @license GPL-2.0+
 */

/**
 * Body class
 *
 * @param array $classes body classes.
 */
function ys_body_classes( $classes ) {

	/**
	 * 背景画像があればクラス追加
	 */
	if ( get_background_image() ) {
		$classes[] = 'custom-background-image';
	}

	/**
	 * AMPならクラス追加
	 */
	if ( ys_is_amp() ) {
		$classes[] = 'amp';
	} else {
		$classes[] = 'no-amp';
	}

	/**
	 * 1カラム,AMPの場合
	 */
	if ( ys_is_one_column() || ys_is_amp() ) {
		$classes[] = 'one-col';
	}

	/**
	 * アーカイブレイアウト
	 */
	if ( is_archive() || is_home() || is_search() ) {
		$classes[] = 'entry-list--' . ys_get_option( 'ys_archive_type' );
	}

	return $classes;
}
add_filter( 'body_class', 'ys_body_classes' );