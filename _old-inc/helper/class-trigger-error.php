<?php
/**
 * Trigger Error
 *
 * @package ystandard
 * @author  yosiakatsuki
 * @license GPL-2.0+
 */

namespace ystandard\helper;

defined( 'ABSPATH' ) || die();

/**
 * Class Trigger Error
 *
 * @package ystandard
 */
class Trigger_Error {
	/**
	 * エラー表示
	 *
	 * @param string $message     メッセージ.
	 * @param int    $error_level エラーレベル.
	 *
	 * @return void
	 */
	public static function trigger_error( $message, $error_level = E_USER_NOTICE ) {
		if ( ! defined( 'WP_DEBUG' ) || ! WP_DEBUG ) {
			return;
		}
		trigger_error( $message, $error_level );
	}
}
