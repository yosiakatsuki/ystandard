<?php
/**
 * CSS
 *
 * @package ystandard
 * @author  yosiakatsuki
 * @license GPL-3.0+
 */

namespace ystandard;

defined( 'ABSPATH' ) || die();

/**
 * Class Styles
 *
 * @package ystandard
 */
class Styles {

	const HANDLE_BASE = 'ystandard-style-base';

	const HANDLE_MAIN = 'ystandard-style';

	/**
	 * Constructor.
	 */
	public function __construct() {
		add_action( 'wp_enqueue_scripts', [ $this, 'enqueue_base_styles' ], 1 );
		add_action( 'wp_enqueue_scripts', [ $this, 'enqueue_main_styles' ] );
	}

	/**
	 * Enqueue styles.
	 *
	 * @return void
	 */
	public function enqueue_main_styles() {
		self::enqueue_style(
			self::HANDLE_MAIN,
			'/assets/css/ystandard.css'
		);
		// カスタムプロパティ.
		$css = self::get_custom_property_css();
		if ( $css ) {
			wp_add_inline_style(
				self::HANDLE_MAIN,
				sprintf( ':root{%s}', $css )
			);
		}
		// インラインCSS.
		wp_add_inline_style(
			self::HANDLE_MAIN,
			apply_filters( 'ys_add_inline_style', '' )
		);
	}

	/**
	 * リセット等のCSS
	 *
	 * @return void
	 */
	public function enqueue_base_styles() {
		self::enqueue_style(
			self::HANDLE_BASE,
			'/assets/css/ystandard-base.css'
		);
	}

	/**
	 * Enqueue style.
	 *
	 * @param string $handle Handle.
	 * @param string $path Path.
	 *
	 * @return void
	 */
	public static function enqueue_style( $handle, $path ) {
		wp_enqueue_style(
			$handle,
			get_template_directory_uri() . $path,
			[],
			filemtime( get_template_directory() . $path )
		);
		// インライン指定.
		wp_style_add_data(
			$handle,
			'path',
			get_template_directory() . $path
		);
	}

	/**
	 * カスタムプロパティのCSSを取得
	 *
	 * @return string
	 */
	public static function get_custom_property_css() {

		$properties = apply_filters( 'ys_get_custom_property', [] );
		$properties = ! is_array( $properties ) ? [] : $properties;
		// カスタムプロパティ作成.
		$result = '';
		foreach ( $properties as $key => $value ) {
			$result .= sprintf( '--%s: %s;', $key, $value );
		}

		return $result;
	}
}

new Styles();
