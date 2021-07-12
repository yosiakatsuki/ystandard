<?php
/**
 * Body関連
 *
 * @package ystandard
 * @author  yosiakatsuki
 * @license GPL-2.0+
 */

namespace ystandard;

/**
 * Class Body
 *
 * @package ystandard
 */
class Body {
	/**
	 * Body constructor.
	 */
	public function __construct() {
		add_filter( 'body_class', [ $this, 'add_body_class' ] );
	}

	/**
	 * Body class
	 *
	 * @param array $classes body classes.
	 *
	 * @return array
	 */
	public function add_body_class( $classes ) {
		$classes[] = 'ystandard';
		$classes[] = 'ystd'; // CSS用.
		/**
		 * 背景画像があればクラス追加
		 */
		if ( get_background_image() ) {
			$classes[] = 'has-custom-background-image';
		}

		/**
		 * AMPならクラス追加
		 */
		if ( AMP::is_amp() ) {
			$classes[] = 'is-amp';
		}

		/**
		 * ワンカラム / サイドバーあり
		 */
		if ( Template::is_one_column() ) {
			$classes[] = 'is-one-column';
		} else {
			$classes[] = 'has-sidebar';
		}
		/**
		 * フロントページ
		 */
		if ( Template::is_single_front_page() ) {
			$classes[] = 'is-front-page';
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
			$classes[] = 'is-archive--' . Archive::get_archive_type();
		}

		/**
		 * カスタムヘッダー
		 */
		if ( Header_Media::is_active_header_media() ) {
			$classes[] = 'has-custom-header';
			$classes[] = 'custom-header--' . Header_Media::get_header_media_type();
		}

		/**
		 * タイトルなしテンプレート
		 */
		if ( Template::is_no_title_template() ) {
			$classes[] = 'no-title';
		}
		if ( Template::is_single_front_page() && Template::is_one_column() ) {
			$classes[] = 'no-title';
		}

		/**
		 * ワイド幅テンプレート
		 */
		if ( Template::is_wide() ) {
			$classes[] = 'is-wide';
		}

		/**
		 * 背景色あり
		 */
		if ( Site_Background::is_custom_bg_color() ) {
			$classes[] = 'has-bg-color';
		}
		/**
		 * なんか背景あり
		 */
		if ( Site_Background::is_custom_bg_color() || get_background_image() ) {
			$classes[] = 'has-background';
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
		if ( Header::is_header_fixed() ) {
			$classes[] = 'has-fixed-header';
		}
		/**
		 * ヘッダータイプ
		 */
		$classes[] = 'header-type--' . Header::get_header_type();

		/**
		 * カスタマイズプレビュー
		 */
		if ( is_customize_preview() ) {
			$classes[] = 'is-customize-preview';
		}

		return $classes;
	}
}

new Body();
