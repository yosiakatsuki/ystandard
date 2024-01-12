<?php
/**
 * theme.json関連
 *
 * @package ystandard
 * @author  yosiakatsuki
 * @license GPL-3.0+
 */

namespace ystandard\utils;

defined( 'ABSPATH' ) || die();

/**
 * Class Theme_Json.
 *
 * @package ystandard
 */
class Theme_Json {

	/**
	 * @return array
	 */
	public static function get_layout_size() {
		// クラスがない場合は固定値を返す.
		if ( ! class_exists( 'WP_Theme_JSON_Resolver' ) ) {
			return self::get_default_layout_size();
		}
		// theme.jsonの設定を取得.
		$data     = \WP_Theme_JSON_Resolver::get_theme_data( [], [ 'with_supports' => false ] );
		$settings = $data->get_settings();
		// 設定確認.
		if ( ! is_array( $settings ) || ! isset( $settings['layout'] ) ) {
			return self::get_default_layout_size();
		}

		return apply_filters( 'ys_get_layout_size', $settings['layout'] );
	}

	/**
	 * デフォルトのレイアウトサイズを取得
	 *
	 * @return array
	 */
	private static function get_default_layout_size() {
		return apply_filters(
			'ys_get_layout_size',
			[
				'contentSize' => '800px',
				'wideSize'    => '1200px',
			]
		);
	}
}
