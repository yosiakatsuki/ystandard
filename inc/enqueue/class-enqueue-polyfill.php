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
	 * Enqueue_Polyfill constructor.
	 */
	public function __construct() {
		add_action( 'wp_enqueue_scripts', [ $this, 'enqueue_polyfill' ] );
	}

	/**
	 * Polyfill関連のenqueue
	 */
	public function enqueue_polyfill() {
		if ( ! self::is_use_polyfill() ) {
			return;
		}
		wp_enqueue_script(
			'ys-polyfill',
			get_template_directory_uri() . '/js/polyfill.js',
			[],
			Utility::get_ystandard_version(),
			true
		);
	}

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

new Enqueue_Polyfill();
