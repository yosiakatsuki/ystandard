<?php
/**
 * JS読み込みクラス
 *
 * @package ystandard
 * @author  yosiakatsuki
 * @license GPL-2.0+
 */

namespace ystandard;

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
		add_filter( 'script_loader_tag', [ $this, 'script_loader_tag' ], PHP_INT_MAX, 3 );
	}

	/**
	 * Enqueue js
	 */
	public function enqueue_scripts() {
		if ( AMP::is_amp() ) {
			return;
		}
		wp_enqueue_script(
			self::JS_HANDLE,
			get_template_directory_uri() . '/js/ystandard.js',
			[],
			Utility::get_ystandard_version(),
			true
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

	/**
	 * 属性追加
	 *
	 * @param string $tag    The script tag.
	 * @param string $handle The script handle.
	 *
	 * @return string Script HTML string.
	 */
	public function script_loader_tag( $tag, $handle ) {
		$attributes = apply_filters(
			'ys_script_attributes',
			self::SCRIPT_ATTRIBUTES
		);
		foreach ( $attributes as $attr ) {
			$data = wp_scripts()->get_data( $handle, $attr );
			if ( ! $data ) {
				continue;
			}
			if ( ! preg_match( ":\s$attr(=|>|\s):", $tag ) ) {
				$replace = '';
				if ( is_bool( $data ) ) {
					$replace = " $attr";
				}
				if ( is_string( $data ) ) {
					$replace = " $attr=\"$data\"";
				}
				$tag = preg_replace( ':(?=></script>):', $replace, $tag, 1 );
			}
			break;
		}

		return $tag;
	}
}

new Enqueue_Scripts();
