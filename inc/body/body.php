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
	 * ワンカラム / サイドバーあり
	 */
	if ( ys_is_one_column() ) {
		$classes[] = 'col-1';
	} else {
		$classes[] = 'has-sidebar';
	}
	/**
	 * Type: singular
	 */
	if ( is_singular() && ! is_front_page() ) {
		$classes[] = 'singular';
	}

	/**
	 * アーカイブレイアウト
	 */
	if ( is_archive() || is_home() || is_search() ) {
		$classes[] = 'entry-list--' . ys_get_option( 'ys_archive_type', 'list' );
	}

	/**
	 * フロントページタイプ
	 */
	if ( is_front_page() && 'normal' !== ys_get_option( 'ys_front_page_type', 'normal' ) ) {
		$classes[] = 'front-page--custom';
		$classes[] = 'front-page--' . ys_get_option( 'ys_front_page_type', 'normal' );
	}

	/**
	 * カスタムヘッダー
	 */
	if ( ys_is_active_custom_header() ) {
		$classes[] = 'has-custom-header';
		$classes[] = 'custom-header--' . ys_get_custom_header_type();
		if ( ys_get_option_by_bool( 'ys_wp_header_media_full', false ) ) {
			$classes[] = 'custom-header--full';
		}
	}

	/**
	 * タイトルなしテンプレート
	 */
	if ( ys_is_no_title_template() ) {
		$classes[] = 'one-column-no-title';
	}

	/**
	 * フル幅テンプレート
	 */
	if ( ys_is_full_width() ) {
		$classes[] = 'full-width';
	}

	/**
	 * 背景色あり
	 */
	if ( YS_Color::get_site_bg_default() !== YS_Color::get_site_bg() ) {
		$classes[] = 'has-bg-color';
	}

	/**
	 * モバイルフッターあり
	 */
	if ( has_nav_menu( 'mobile-footer' ) ) {
		$classes[] = 'has-mobile-footer';
	}

	/**
	 * ヘッダー固定
	 */
	if ( ys_get_option_by_bool( 'ys_header_fixed', false ) ) {
		$classes[] = 'has-fixed-header';
	}

	return $classes;
}

add_filter( 'body_class', 'ys_body_class' );
