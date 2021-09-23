<?php
/**
 * Polyfill
 *
 * @package ystandard
 * @author  yosiakatsuki
 * @license GPL-2.0+
 */

namespace ystandard;

defined( 'ABSPATH' ) || die();

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
		add_action( 'wp_enqueue_scripts', [ $this, 'enqueue_polyfill_scripts' ] );
		add_action( 'wp_enqueue_scripts', [ $this, 'enqueue_polyfill_styles' ], PHP_INT_MAX );
	}

	/**
	 * Polyfill関連のenqueue
	 */
	public function enqueue_polyfill_scripts() {
		$polyfill = false;
		if ( self::is_ie() || self::is_edge() ) {
			wp_enqueue_script(
				'ys-ie-polyfill',
				get_template_directory_uri() . '/js/polyfill.js',
				[],
				Utility::get_ystandard_version()
			);
			$polyfill = true;
		}
		if ( self::is_safari() || self::is_ie() || self::is_edge() ) {
			wp_enqueue_script(
				'ys-smooth-scroll-polyfill',
				get_template_directory_uri() . '/library/smoothscroll/smoothscroll.js',
				[],
				Utility::get_ystandard_version()
			);
			$polyfill = true;
		}
		if ( $polyfill ) {
			do_action( 'ys_enqueue_polyfill_scripts' );
		}
	}

	/**
	 * Polyfill関連のenqueue
	 */
	public function enqueue_polyfill_styles() {
		$polyfill = false;
		if ( self::is_ie() || self::is_edge() ) {
			wp_enqueue_style(
				'ys-polyfill-styles',
				get_template_directory_uri() . '/css/ystandard-polyfill.css',
				[],
				Utility::get_ystandard_version()
			);
			$polyfill = true;
		}
		if ( $polyfill ) {
			do_action( 'ys_enqueue_polyfill_styles' );
		}
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

	/**
	 * Safari チェック
	 *
	 * @return bool
	 */
	public static function is_safari() {
		if ( self::is_ie() || self::is_edge() ) {
			return false;
		}
		if ( Utility::check_user_agent( [ 'chrome' ] ) ) {
			return false;
		}
		if ( Utility::check_user_agent( [ 'firefox' ] ) ) {
			return false;
		}

		return Utility::check_user_agent( [ 'safari' ] );
	}

}

new Enqueue_Polyfill();
