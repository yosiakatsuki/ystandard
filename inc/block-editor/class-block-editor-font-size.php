<?php
/**
 * フォントサイズ定義関連
 *
 * @package yStandard_blocks
 * @author  yosiakatsuki
 * @license GPL-2.0+
 */

namespace ystandard;

use ystandard\helper\Style_Sheet;

defined( 'ABSPATH' ) || die();

/**
 * Class Block_Editor_Font_Size
 *
 * @package ystandard
 */
class Block_Editor_Font_Size {

	/**
	 * Font_Size constructor.
	 */
	public function __construct() {
		add_action( 'after_setup_theme', [ $this, 'add_theme_support' ] );
		add_filter( Enqueue_Utility::FILTER_BLOCKS_INLINE_CSS, [ $this, 'add_font_sizes_css' ] );
		add_filter( Block_Editor_Assets::BLOCK_EDITOR_ASSETS_HOOK, [ $this, 'add_block_editor_font_sizes_css' ] );
	}


	/**
	 * フロント用フォントサイズCSS
	 *
	 * @param string $css CSS.
	 *
	 * @return string
	 */
	public function add_font_sizes_css( $css ) {
		add_filter( 'ys_is_enqueue_font_size', '__return_true' );

		return $css . self::get_font_size_css( '.ystd' );
	}

	/**
	 * 編集画面用フォントサイズCSS
	 *
	 * @param string $css CSS.
	 *
	 * @return string
	 */
	public function add_block_editor_font_sizes_css( $css ) {
		add_filter( 'ys_is_enqueue_block_editor_font_size', '__return_true' );

		return $css . self::get_font_size_css( '.editor-styles-wrapper' );
	}

	/**
	 * フォントサイズ用のCSS作成
	 *
	 * @param string $prefix Prefix.
	 *
	 * @return string
	 */
	public static function get_font_size_css( $prefix = '' ) {
		$font_size = get_theme_support( 'editor-font-sizes' );
		if ( empty( $font_size ) || ! is_array( $font_size ) ) {
			return '';
		}
		$prefix = empty( $prefix ) ? '' : $prefix . ' ';
		$result = '';
		foreach ( $font_size[0] as $value ) {
			$unit = isset( $value['unit'] ) ? $value['unit'] : 'px';
			$size = is_numeric( $value['size'] ) ? "{$value['size']}${unit}" : $value['size'];
			// CSS.
			$result .= "${prefix}.has-{$value['slug']}-font-size{font-size:${size};}";
		}

		return Style_Sheet::minify( $result );
	}

	/**
	 * ブロックエディター文字サイズ設定
	 *
	 * @return array
	 */
	public static function get_editor_font_sizes() {
		$size = [
			[
				'name'      => __( '極小', 'ystandard' ),
				'shortName' => __( 'x-small', 'ystandard' ),
				'size'      => 12,
				'slug'      => 'x-small',
			],
			[
				'name'      => __( '小', 'ystandard' ),
				'shortName' => __( 'small', 'ystandard' ),
				'size'      => 14,
				'slug'      => 'small',
			],
			[
				'name'      => __( '標準', 'ystandard' ),
				'shortName' => __( 'normal', 'ystandard' ),
				'size'      => 16,
				'slug'      => 'normal',
			],
			[
				'name'      => __( '中', 'ystandard' ),
				'shortName' => __( 'medium', 'ystandard' ),
				'size'      => 18,
				'slug'      => 'medium',
			],
			[
				'name'      => __( '大', 'ystandard' ),
				'shortName' => __( 'large', 'ystandard' ),
				'size'      => 20,
				'slug'      => 'large',
			],
			[
				'name'      => __( '極大', 'ystandard' ),
				'shortName' => __( 'x-large', 'ystandard' ),
				'size'      => 22,
				'slug'      => 'x-large',
			],
			[
				'name'      => __( '巨大', 'ystandard' ),
				'shortName' => __( 'xx-large', 'ystandard' ),
				'size'      => 26,
				'slug'      => 'xx-large',
			],
		];

		return apply_filters( 'ys_editor_font_sizes', $size );
	}

	/**
	 * Add Theme Support
	 */
	public function add_theme_support() {
		/**
		 * ブロックエディターの文字サイズ選択設定
		 */
		add_theme_support( 'editor-font-sizes', self::get_editor_font_sizes() );
	}
}

new Block_Editor_Font_Size();
