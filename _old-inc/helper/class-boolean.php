<?php
/**
 * Helper : Boolean
 *
 * @package ystandard
 * @author  yosiakatsuki
 * @license GPL-2.0+
 */

namespace ystandard\helper;

defined( 'ABSPATH' ) || die();

/**
 * Class : Boolean
 */
class Boolean {

	/**
	 * Boolに変換
	 *
	 * @param mixed $value 変換する値.
	 *
	 * @return bool
	 */
	public static function to_bool( $value ) {
		if ( true === $value || 'true' === $value || 1 === $value || '1' === $value ) {
			return true;
		} else {
			return false;
		}
	}
}
