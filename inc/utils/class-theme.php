<?php
/**
 * テーマ関連
 *
 * @package ystandard
 * @author  yosiakatsuki
 * @license GPL-3.0+
 */

namespace ystandard\utils;

defined( 'ABSPATH' ) || die();


/**
 * Class Theme
 *
 * @package ystandard
 */
class Theme {

	/**
	 * テーマバージョン取得
	 *
	 * @param boolean $parent 親テーマ情報かどうか.
	 *
	 * @return string
	 */
	public static function get_theme_version( $parent = false ) {

		/**
		 * 子テーマ情報
		 */
		$theme = wp_get_theme();
		if ( $parent && get_template() !== get_stylesheet() ) {
			/**
			 * 親テーマ情報
			 */
			$theme = wp_get_theme( get_template() );
		}

		return $theme->get( 'Version' );
	}

	/**
	 * テーマ本体のバージョン取得
	 *
	 * @return string
	 */
	public static function get_ystandard_version() {

		return self::get_theme_version( true );
	}
}
