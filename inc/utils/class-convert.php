<?php
/**
 * 変換系
 *
 * @package ystandard
 * @author  yosiakatsuki
 * @license GPL-3.0+
 */
namespace ystandard\utils;

defined( 'ABSPATH' ) || die();

/**
 * Class Convert
 *
 * @package ystandard
 */
class Convert {

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
