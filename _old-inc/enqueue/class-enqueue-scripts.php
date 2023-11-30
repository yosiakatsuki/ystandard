<?php
/**
 * JS読み込みクラス
 *
 * @package ystandard
 * @author  yosiakatsuki
 * @license GPL-2.0+
 */

namespace ystandard;

defined( 'ABSPATH' ) || die();

/**
 * Class Enqueue_Scripts
 *
 * @package ystandard
 */
class Enqueue_Scripts {

	/**
	 * Handle
	 */
	const JS_HANDLE = 'ystandard';

	/**
	 * Scriptタグにつけられる属性
	 */
	const SCRIPT_ATTRIBUTES = [
		'async',
		'defer',
		'crossorigin',
		'custom-element',
	];

	/**
	 * Enqueue_Scripts constructor.
	 */
	public function __construct() {

		add_action( 'wp_enqueue_scripts', [ $this, 'enqueue_scripts' ] );
		add_action( 'wp_enqueue_scripts', [ $this, 'add_data' ] );
	}

	/**
	 * Enqueue js
	 */
	public function enqueue_scripts() {
		if ( AMP::is_amp() ) {
			return;
		}
		wp_enqueue_script( 'jquery' );
		wp_enqueue_script(
			self::JS_HANDLE,
			get_template_directory_uri() . '/js/ystandard.js',
			[],
			Utility::get_ystandard_version(),
			true
		);
		wp_localize_script(
			self::JS_HANDLE,
			'ystdScriptOption',
			[
				'isPermalinkBasic' => ! get_option( 'permalink_structure', '' ),
			]
		);
		do_action( 'ys_enqueue_script' );
	}

	/**
	 * Add Data
	 */
	public function add_data() {
		if ( AMP::is_amp() ) {
			return;
		}
		Enqueue_Utility::add_defer( self::JS_HANDLE );

		do_action( 'ys_script_add_data' );
	}
}

new Enqueue_Scripts();
