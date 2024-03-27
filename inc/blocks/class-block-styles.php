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
		// テーマで追加したスタイル.
		$theme = $this->get_theme_block_style();
		// JSONで追加したスタイル,
		$from_json = $this->get_block_style_from_json();
		if ( ! is_array( $from_json ) ) {
			$custom = [];
		}
		// フックで追加したスタイル.
		$custom = apply_filters( 'ys_register_block_styles', [] );
		if ( ! is_array( $custom ) ) {
			$custom = [];
		}
		// ブロックスタイルをマージ.
		$block_styles = array_merge( $theme, $from_json, $custom );
		foreach ( $block_styles as $item ) {
			if ( isset( $item['block_name'] ) ) {
				register_block_style( $item['block_name'], $item );
			}
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

	/**
	 * JSONファイルからブロックスタイルを取得する
	 *
	 * @return array
	 */
	private function get_block_style_from_json() {

		// ファイルパス.
		$file_path = apply_filters( 'ys_block_style_json_file_path', '/block-styles.json' );

		// テーマ・子テーマ内のパスを取得.
		$path = get_theme_file_path( $file_path );

		if ( ! file_exists( $path ) ) {
			return [];
		}

		// JSONファイルを配列形式で読み込む.
		$block_styles = wp_json_file_decode( $path, [ 'associative' => true ] );
		if ( empty( $block_styles ) ) {
			return [];
		}
		$result = [];
		// ブロックスタイルの形式が正しいかチェック.
		foreach ( $block_styles as $item ) {
			if ( isset( $item['block_name'] ) && isset( $item['name'] ) && isset( $item['label'] ) ) {
				$result[] = $item;
			}
		}

		return $result;
	}
}

new Block_Styles();
