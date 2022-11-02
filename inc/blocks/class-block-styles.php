<?php
/**
 * ブロックスタイル
 *
 * @package ystandard
 * @author  yosiakatsuki
 * @license GPL-2.0+
 */

namespace ystandard;

defined( 'ABSPATH' ) || die();

/**
 * Block Style.
 */
class Block_Styles {
	/**
	 * Constructor.
	 */
	public function __construct() {
		add_action( 'init', [ $this, 'register_block_styles' ] );
	}

	/**
	 * ブロックスタイル追加
	 *
	 * @return void
	 */
	public function register_block_styles() {
		if ( ! function_exists( 'register_block_style' ) ) {
			return;
		}
		$theme  = $this->get_theme_block_style();
		$custom = apply_filters( 'ys_register_block_styles', [] );
		if ( ! is_array( $custom ) ) {
			$custom = [];
		}
		$block_styles = array_merge( $theme, $custom );
		foreach ( $block_styles as $item ) {
			register_block_style( $item['block_name'], $item );
		}
	}

	/**
	 * テーマで追加したスタイル.
	 *
	 * @return array[]
	 */
	private function get_theme_block_style() {
		return [
			[
				'block_name' => 'core/gallery',
				'name'       => 'stacked-on-mobile',
				'label'      => __( 'モバイルで1列', 'ystandard' ),
			],
		];
	}
}

new Block_Styles();
