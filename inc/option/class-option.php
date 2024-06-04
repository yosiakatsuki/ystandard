<?php
/**
 * 設定 クラス
 *
 * @package ystandard
 * @author  yosiakatsuki
 * @license GPL-2.0+
 */

namespace ystandard;

use ystandard\utils\Convert;

defined( 'ABSPATH' ) || die();

/**
 * Class Option
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

		$result = get_option( $name, self::get_default( $name, $default ) );

		/**
		 * 指定のタイプで取得
		 */
		if ( false !== $type ) {
			switch ( $type ) {
				case 'bool':
				case 'boolean':
					$result = Convert::to_bool( $result );
					break;
				case 'int':
					$result = intval( $result );
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
	public static function get_option_by_bool( $name, $default = false ) {
		return self::get_option( $name, $default, 'bool' );
	}

	/**
	 * 設定取得(int)
	 *
	 * @param string $name option key.
	 * @param mixed $default デフォルト値.
	 *
	 * @return mixed
	 */
	public static function get_option_by_int( $name, $default = 0 ) {
		return self::get_option( $name, $default, 'int' );
	}

	/**
	 * 設定の存在確認.
	 *
	 * @param string $name option key.
	 *
	 * @return bool
	 */
	public static function exists_option( $name ) {
		// 仮想のデフォルト値を作成.
		$default = substr( md5( wp_date( 'YmdHis' ) ), 0, 20 );
		$option  = self::get_option( $name, $default );
		// デフォルト値が返ってきたら設定は存在しない
		if ( $option === $default ) {
			return false;
		}

		return true;
	}
}
