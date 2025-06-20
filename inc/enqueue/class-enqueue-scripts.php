<?php
/**
 * JS読み込みクラス
 *
 * @package ystandard
 * @author  yosiakatsuki
 * @license GPL-2.0+
 */

namespace ystandard;

use ystandard\utils\Theme;

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
	 * Enqueue_Scripts constructor.
	 */
	public function __construct() {

		add_action( 'wp_enqueue_scripts', [ $this, 'enqueue_scripts' ] );
	}

	/**
	 * Enqueue js
	 */
	public function enqueue_scripts() {
		wp_enqueue_script( 'jquery' );
		// テーマメインJS.
		wp_enqueue_script(
			self::JS_HANDLE,
			get_template_directory_uri() . '/js/ystandard.js',
			[],
			Theme::get_ystandard_version(),
			true
		);
		wp_script_add_data( self::JS_HANDLE, 'defer', true );

		$args = apply_filters(
			'ys_script_options',
			[
				'isPermalinkBasic' => ! get_option( 'permalink_structure', '' ),
			]
		);
		// JSで使うデータをセット.
		wp_localize_script( self::JS_HANDLE, 'ystdScriptOption', $args );
		do_action( 'ys_enqueue_script' );
	}
}

new Enqueue_Scripts();
