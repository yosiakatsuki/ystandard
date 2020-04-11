<?php
/**
 * Polyfill
 *
 * @package ystandard
 * @author  yosiakatsuki
 * @license GPL-2.0+
 */

namespace ystandard;

/**
 * Class Enqueue_Polyfill
 *
 * @package ystandard
 */
class Enqueue_Polyfill {

	/**
	 * Polyfillが必要か
	 *
	 * @return bool
	 */
	public static function is_use_polyfill() {
		return ( self::is_ie() || self::is_edge() );
	}

	/**
	 * IEチェック
	 *
	 * @return bool
	 */
	public static function is_ie() {
		$ua = [
			'Trident',
			'MSIE',
		];

		return Utility::check_user_agent( $ua );
	}

	/**
	 * Edgeチェック
	 *
	 * @return bool
	 */
	public static function is_edge() {
		$ua = [
			'Edge',
		];

		return Utility::check_user_agent( $ua );
	}

}
