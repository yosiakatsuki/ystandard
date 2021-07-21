<?php
/**
 * 設定 クラス
 *
 * @package ystandard
 * @author  yosiakatsuki
 * @license GPL-2.0+
 */

namespace ystandard;

defined( 'ABSPATH' ) || die();

/**
 * Class Option
 */
class Option {

	/**
	 * 設定取得
	 *
	 * @param string $name    option key.
	 * @param mixed  $default デフォルト値.
	 * @param mixed  $type    取得する型.
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
					$result = Utility::to_bool( $result );
					break;
				case 'int':
					$result = intval( $result );
			}
		}

		return apply_filters( "ys_get_option_${name}", $result, $name );
	}

	/**
	 * デフォルト値書き換え
	 *
	 * @param string $name    option key.
	 * @param mixed  $default デフォルト値.
	 *
	 * @return mixed
	 */
	public static function get_default( $name, $default = false ) {
		return apply_filters( "ys_get_option_default_${name}", $default, $name );
	}

	/**
	 * 設定取得(bool)
	 *
	 * @param string $name    option key.
	 * @param mixed  $default デフォルト値.
	 *
	 * @return mixed
	 */
	public static function get_option_by_bool( $name, $default = false ) {
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
	public static function get_option_by_int( $name, $default = 0 ) {
		return self::get_option( $name, $default, 'int' );
	}

	/**
	 * 設定の変更処理
	 *
	 * @param string $old_key     旧設定.
	 * @param mixed  $old_default 旧設定の初期値.
	 * @param string $new_key     新設定.
	 * @param mixed  $new_default 新設定の初期値.
	 */
	private function change_option_key( $old_key, $old_default, $new_key, $new_default ) {
		if ( get_option( $new_key, $new_default ) === $new_default ) {
			if ( get_option( $old_key, $old_default ) !== $old_default ) {
				update_option(
					$new_key,
					get_option( $old_key, $new_default )
				);
				delete_option( $old_key );
			}
		}
	}
}
