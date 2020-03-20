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
	 * Twitter
	 */
	const HANDLE_TWITTER = 'twitter-wjs';
	/**
	 * Facebook
	 */
	const HANDLE_FACEBOOK = 'facebook-jssdk';

	/**
	 * Enqueue_Scripts constructor.
	 */
	public function __construct() {
		if ( ! AMP::is_amp() ) {
			add_action( 'wp_enqueue_script', [ $this, 'enqueue_scripts' ] );
			add_action( 'wp_enqueue_script', [ $this, 'enqueue_sns_scripts' ] );
			add_action( 'wp_enqueue_script', [ $this, 'add_data' ] );
		}
		add_filter( 'script_loader_tag', [ $this, 'script_loader_tag' ], PHP_INT_MAX, 3 );
	}

	/**
	 * Add defer.
	 *
	 * @param string $handle handle.
	 */
	public static function add_defer( $handle ) {
		wp_script_add_data( $handle, 'defer', true );
	}

	/**
	 * Add async.
	 *
	 * @param string $handle handle.
	 */
	public static function add_async( $handle ) {
		wp_script_add_data( $handle, 'async', true );
	}

	/**
	 * Add crossorigin.
	 *
	 * @param string $handle handle.
	 */
	public static function add_crossorigin( $handle, $value ) {
		wp_script_add_data( $handle, 'crossorigin', $value );
	}

	/**
	 * Enqueue js
	 */
	public function enqueue_scripts() {
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
	 * SNS関連のスクリプト
	 */
	public function enqueue_sns_scripts() {
		/**
		 * Twitter関連スクリプト読み込み
		 */
		if ( ys_get_option_by_bool( 'ys_load_script_twitter', false ) ) {
			wp_enqueue_script(
				self::HANDLE_TWITTER,
				'//platform.twitter.com/widgets.js',
				[],
				Utility::get_ystandard_version(),
				true
			);
		}
		/**
		 * Facebook関連スクリプト読み込み
		 */
		if ( ys_get_option_by_bool( 'ys_load_script_facebook', false ) ) {
			wp_enqueue_script(
				self::HANDLE_FACEBOOK,
				'//connect.facebook.net/ja_JP/sdk.js#xfbml=1&version=v5.0',
				[],
				Utility::get_ystandard_version(),
				true
			);
		}
		do_action( 'ys_enqueue_sns_script' );
	}

	/**
	 * Add Data
	 */
	public function add_data() {
		self::add_defer( self::JS_HANDLE );
		self::add_defer( self::HANDLE_TWITTER );
		self::add_defer( self::HANDLE_FACEBOOK );

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
			[ 'async', 'defer', 'crossorigin' ]
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
