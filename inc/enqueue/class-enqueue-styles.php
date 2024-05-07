<?php
/**
 * CSS読み込みクラス
 *
 * @package ystandard
 * @author  yosiakatsuki
 * @license GPL-2.0+
 */

namespace ystandard;

use ystandard\utils\CSS;
use ystandard\utils\Theme;

defined( 'ABSPATH' ) || die();

/**
 * Class Enqueue_Styles
 *
 * @package ystandard
 */
class Enqueue_Styles {

	/**
	 * Main CSS.
	 */
	const HANDLE_MAIN = 'ystandard';

	/**
	 * Blocks CSS.
	 */
	const HANDLE_BLOCKS = 'ys-blocks';

	/**
	 * Enqueue_Styles constructor.
	 */
	public function __construct() {
		add_action( 'wp_enqueue_scripts', [ $this, 'enqueue_css' ] );
		add_action( 'wp_enqueue_scripts', [ $this, 'dequeue_css' ] );
		add_action( 'wp_enqueue_scripts', [ $this, 'enqueue_style_css' ], 100 );
		add_filter( 'wp_get_custom_css', [ $this, 'minify_custom_css' ] );
		add_action( 'wp_enqueue_scripts', [ $this, 'dequeue_core_style' ], PHP_INT_MAX );
	}

	/**
	 * CSS enqueue
	 */
	public function enqueue_css() {
		/**
		 * メインCSS
		 */
		wp_enqueue_style(
			self::HANDLE_MAIN,
			get_template_directory_uri() . '/css/ystandard.css',
			[],
			filemtime( get_template_directory() . '/css/ystandard.css' )
		);
		/**
		 * カスタムプロパティ
		 */
		wp_register_style( 'ystandard-custom-properties', false, [ self::HANDLE_MAIN ], true, true );
		wp_add_inline_style( 'ystandard-custom-properties', self::get_css_vars_selector() );
		wp_add_inline_style( 'ystandard-custom-properties', self::get_css_vars_selector_preset() );
		wp_enqueue_style( 'ystandard-custom-properties' );
		/**
		 * 設定・その他連携機能で追加されたCSS
		 */
		wp_register_style( 'ystandard-custom-inline', false, [ 'ystandard-custom-properties' ], true, true );
		wp_add_inline_style( 'ystandard-custom-inline', $this->get_inline_css() );
		wp_enqueue_style( 'ystandard-custom-inline' );

		// 位置調整.
		wp_enqueue_style(
			self::HANDLE_BLOCKS,
			get_template_directory_uri() . '/css/blocks.css',
			[],
			filemtime( get_template_directory() . '/css/blocks.css' )
		);
		wp_add_inline_style(
			self::HANDLE_BLOCKS,
			$this->get_blocks_inline_css()
		);

		do_action( 'ys_enqueue_css' );

		/**
		 * Style css
		 */
		wp_enqueue_style(
			'style-css',
			get_stylesheet_uri(),
			[],
			Theme::get_theme_version( true )
		);

		$this->style_add_data();
	}

	/**
	 * Add Data
	 */
	private function style_add_data() {
		wp_style_add_data( self::HANDLE_MAIN, 'inline', true );
		do_action( 'ys_style_add_data' );
	}

	/**
	 * インラインCSSを取得
	 *
	 * @return string
	 */
	private function get_inline_css() {

		return CSS::minify(
			apply_filters( 'ys_get_inline_css', '' )
		);
	}

	/**
	 * インラインCSSを取得
	 *
	 * @return string
	 */
	private function get_blocks_inline_css() {

		return CSS::minify( apply_filters( Enqueue_Utility::FILTER_BLOCKS_INLINE_CSS, '' ) );
	}

	/**
	 * CSSカスタムプロパティを作成する
	 *
	 * @param string $selector Selector.
	 *
	 * @return string
	 */
	public static function get_css_vars_selector( $selector = 'body:where([class])' ) {

		/**
		 * 旧フック : ys_css_vars
		 *
		 * @deprecated v5.0.0
		 */
		$vars = apply_filters( 'ys_css_vars', [] );
		/**
		 * CSSカスタムプロパティに指定する値
		 * name,value
		 */
		$vars = apply_filters( 'ys_get_css_custom_properties_args', $vars );
		if ( empty( $vars ) ) {
			return '';
		}
		$result = self::create_custom_properties_css( $vars );

		if ( ! $result ) {
			return '';
		}

		return "{$selector}{ {$result} }";
	}

	/**
	 * WP preset 上書き用CSSカスタムプロパティを作成する
	 *
	 * @param string $selector Selector.
	 *
	 * @return string
	 */
	public static function get_css_vars_selector_preset( $selector = 'body' ) {
		/**
		 * 旧フック : ys_css_vars_presets
		 *
		 * @deprecated v5.0.0
		 */
		$vars = apply_filters( 'ys_css_vars_presets', [] );
		/**
		 * CSSカスタムプロパティに指定する値
		 * name,value
		 */
		$vars = apply_filters( 'ys_get_css_custom_properties_args_presets', $vars );
		if ( empty( $vars ) ) {
			return '';
		}
		$result = self::create_custom_properties_css( $vars );

		if ( ! $result ) {
			return '';
		}

		return "{$selector} { {$result} }";
	}

	/**
	 * カスタムプロパティCSSを作成
	 *
	 * @param array $vars Variables.
	 *
	 * @return string
	 */
	private static function create_custom_properties_css( $vars ) {
		$result = '';
		foreach ( $vars as $key => $value ) {
			if ( ! empty( $key ) && '' !== $value ) {
				// 一度プレフィックスをクリア.
				$key = str_replace( '--ystd--', '', $key );
				// 結合.
				$result .= "--ystd--{$key}: {$value};";
			}
		}

		return $result;
	}

	/**
	 * Style.cssの位置調整
	 */
	public function enqueue_style_css() {
		wp_dequeue_style( 'style-css' );
		wp_enqueue_style(
			'style-css',
			get_stylesheet_uri(),
			[],
			Theme::get_theme_version( true )
		);
	}

	/**
	 * 不要なコアCSS削除.
	 *
	 * @return void
	 */
	public function dequeue_core_style() {
		// 6.1で追加.
		wp_dequeue_style( 'classic-theme-styles' );
	}

	/**
	 * CSS dequeue
	 */
	public function dequeue_css() {
		wp_dequeue_style( 'wp-block-library-theme' );
	}

	/**
	 * 追加CSSのminify
	 *
	 * @param string $css CSS.
	 *
	 * @return string
	 */
	public function minify_custom_css( $css ) {
		return CSS::minify( $css );
	}
}

new Enqueue_Styles();
