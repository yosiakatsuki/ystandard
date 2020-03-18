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
		add_filter( 'body_class', [ $this, '_body_class' ] );
	}

	/**
	 * Body class
	 *
	 * @param array $classes body classes.
	 *
	 * @return array
	 */
	public function _body_class( $classes ) {
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
		 * Type: singular
		 */
		if ( is_singular() && ! is_front_page() ) {
			$classes[] = 'singular';
		}

		/**
		 * アーカイブレイアウト
		 */
		if ( is_archive() || is_home() || is_search() ) {
			$classes[] = 'is-archive--' . ys_get_option( 'ys_archive_type', 'list' );
		}

		/**
		 * カスタムヘッダー
		 */
		if ( Custom_Header::is_active_custom_header() ) {
			$classes[] = 'has-custom-header';
			$classes[] = 'custom-header--' . ys_get_custom_header_type();
			if ( ys_get_option_by_bool( 'ys_wp_header_media_full', false ) ) {
				$classes[] = 'custom-header--full';
			}
		}

		/**
		 * タイトルなしテンプレート
		 */
		if ( Template::is_no_title_template() ) {
			$classes[] = 'one-column-no-title';
		}

		/**
		 * フル幅テンプレート
		 */
		if ( Template::is_full_width() ) {
			$classes[] = 'full-width';
		}

		/**
		 * 背景色あり
		 */
		if ( Color::is_custom_bg_color() ) {
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
		if ( Header::is_header_fixed() ) {
			$classes[] = 'has-fixed-header';
		}

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
