<?php
/**
 * HTML HEADタグ内の処理
 *
 * @package ystandard
 * @author  yosiakatsuki
 * @license GPL-3.0+
 */

namespace ystandard;

defined( 'ABSPATH' ) || die();

/**
 * Class Head
 *
 * @package ystandard
 */
class Head {

	/**
	 * Head constructor.
	 */
	public function __construct() {
		add_action( 'wp_head', [ $this, 'add_charset' ], 1 );
		add_action( 'wp_head', [ $this, 'add_head_meta' ], 1 );
		add_action( 'after_setup_theme', [ $this, 'remove_meta' ] );
	}

	/**
	 * <head>タグにcharsetを追加
	 */
	public function add_charset() {
		printf( '<meta charset="%s" />' . PHP_EOL, get_bloginfo( 'charset' ) );
	}

	/**
	 * <head>タグにmetaタグを追加
	 */
	public function add_head_meta() {
		$meta = [
			[
				'name'    => 'viewport',
				'content' => 'width=device-width, initial-scale=1.0',
			],
			[
				'name'    => 'format-detection',
				'content' => 'telephone=no',
			],
		];
		$meta = apply_filters( 'ys_head_meta_items', $meta );
		if ( ! is_array( $meta ) || empty( $meta ) ) {
			return;
		}
		// 各metaタグを出力.
		foreach ( $meta as $item ) {
			printf( '<meta name="%s" content="%s" />' . PHP_EOL, $item['name'], $item['content'] );
		}
	}

	/**
	 * <head>タグにつける属性取得
	 */
	public static function get_head_attr() {
		$attr = [];

		return implode( ' ', apply_filters( 'ys_get_head_attr', $attr ) );
	}

	/**
	 * 不要なメタ情報を削除
	 *
	 * @return void
	 */
	public function remove_meta() {
		// WPのバージョン削除.
		remove_action( 'wp_head', 'wp_generator' );
	}
}

new Head();
