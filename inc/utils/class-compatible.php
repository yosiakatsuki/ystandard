<?php
/**
 * 互換性関連
 *
 * @package ystandard
 * @author  yosiakatsuki
 * @license GPL-3.0+
 */

namespace ystandard\utils;

defined( 'ABSPATH' ) || die();

/**
 * Class Compatible
 *
 * @package ystandard
 */
class Compatible {
	/**
	 * Polyfill array_key_first
	 *
	 * @param array $arr array.
	 *
	 * @return int|string|null
	 */
	public static function array_key_first( $arr ) {
		if ( ! function_exists( 'array_key_first' ) ) {
			foreach ( $arr as $key => $unused ) {
				return $key;
			}

			return null;
		}

		return array_key_first( $arr );
	}
}
