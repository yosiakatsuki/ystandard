<?php
/**
 * Body関連
 *
 * @package ystandard
 * @author  yosiakatsuki
 * @license GPL-2.0+
 */

/**
 * Body class
 *
 * @param array $classes body classes.
 *
 * @return array
 */
function ys_body_class( $classes ) {

	$classes[] = 'ystandard';
	$classes[] = 'ystd'; // CSS用.
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
		if ( is_singular() || is_404() ) {
			if ( is_page_template( 'page-template/template-one-column-no-title.php' ) ) {
				$classes[] = 'one-col--no-title';
			} else {
				$classes[] = 'one-col--singular';
			}
		} else {
			$classes[] = 'one-col--archive';
		}
	}

	/**
	 * アーカイブレイアウト
	 */
	if ( is_archive() || is_home() || is_search() ) {
		$classes[] = 'entry-list--' . ys_get_option( 'ys_archive_type' );
	}

	/**
	 * フロントページタイプ
	 */
	if ( is_front_page() && 'normal' !== ys_get_option( 'ys_front_page_type' ) ) {
		$classes[] = 'front-page--custom';
		$classes[] = 'front-page--' . ys_get_option( 'ys_front_page_type' );
	}

	/**
	 * カスタムヘッダー
	 */
	if ( ys_is_active_custom_header() ) {
		$classes[] = 'has-custom-header';
		$classes[] = 'custom-header--' . ys_get_custom_header_type();
		if ( ys_get_option( 'ys_wp_header_media_full' ) ) {
			$classes[] = 'custom-header--full';
		}
	}

	return $classes;
}

add_filter( 'body_class', 'ys_body_class' );
