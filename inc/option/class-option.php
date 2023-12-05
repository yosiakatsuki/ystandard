<?php
/**
 * 設定
 *
 * @package ystandard
 * @author  yosiakatsuki
 * @license GPL-3.0+
 */

namespace ystandard;

defined( 'ABSPATH' ) || die();

/**
 * Class Option
 *
 * @package ystandard
 */
class Option {

	/**
	 * 設定取得
	 *
	 * @param string $name option key.
	 * @param mixed $default デフォルト値.
	 * @param mixed $type 取得する型.
	 *
	 * @return mixed
	 */
	public static function get_option( $name, $default, $type = false ) {

		$option = \get_option( $name, self::get_default( $name, $default ) );
		/**
		 * 指定のタイプで取得
		 */
		if ( false !== $type ) {
			switch ( $type ) {
				case 'bool':
				case 'boolean':
					$result = filter_var( $option, FILTER_VALIDATE_BOOLEAN );
					break;
				case 'int':
					$result = filter_var( $option, FILTER_VALIDATE_INT );
					break;
			}
		}

		return apply_filters( "ys_get_option_{$name}", $result, $name );
	}


	/**
	 * デフォルト値書き換え
	 *
	 * @param string $name option key.
	 * @param mixed $default デフォルト値.
	 *
	 * @return mixed
	 */
	public static function get_default( $name, $default = false ) {
		return apply_filters( "ys_get_option_default_{$name}", $default, $name );
	}

	/**
	 * 設定取得(bool)
	 *
	 * @param string $name option key.
	 * @param mixed $default デフォルト値.
	 *
	 * @return mixed
	 */
	public static function get_option_bool( $name, $default = false ) {
		return self::get_option( $name, $default, 'bool' );
	}

	/**
	 * 設定取得(int)
	 *
	 * @param string $name    option key.
	 * @param mixed  $default デフォルト値.
	 *
	 * @return mixed
	 */
	public static function get_option_int( $name, $default = 0 ) {
		return self::get_option( $name, $default, 'int' );
	}
}
