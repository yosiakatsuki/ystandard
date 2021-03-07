<?php
/**
 * グローバルナビゲーション
 *
 * @package ystandard
 * @author  yosiakatsuki
 * @license GPL-2.0+
 */

namespace ystandard;

defined( 'ABSPATH' ) || die();

/**
 * Class Global_Nav
 *
 * @package ystandard
 */
class Global_Nav {

	/**
	 * グローバルナビゲーションクラス
	 *
	 * @param string $class class.
	 *
	 * @return string
	 */
	public static function get_global_nav_class( $class ) {
		$class   = [ $class ];
		$bg      = Option::get_option( 'ys_color_header_bg', '#ffffff' );
		$default = Option::get_default( 'ys_color_header_bg', '#ffffff' );
		if ( $bg !== $default ) {
			$class[] = 'has-background';
		}
		if ( ! has_nav_menu( 'global' ) ) {
			$class[] = 'is-no-global-nav';
		}

		return trim( implode( ' ', $class ) );
	}

	/**
	 * グローバルナビゲーションを表示するか
	 *
	 * @return boolean
	 */
	public static function has_global_nav() {
		$result = has_nav_menu( 'global' );

		return Utility::to_bool( apply_filters( 'ys_has_global_nav', $result ) );
	}

	/**
	 * グローバルナビゲーションワーカー
	 *
	 * @return \Walker_Nav_Menu
	 */
	public static function global_nav_walker() {
		$result = new \YS_Walker_Global_Nav_Menu();

		return apply_filters( 'ys_global_nav_walker', $result );
	}
}

