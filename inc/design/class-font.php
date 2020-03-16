<?php
/**
 * フォント関連管理 クラス
 *
 * @package ystandard
 * @author  yosiakatsuki
 * @license GPL-2.0+
 */

namespace ystandard;

/**
 * Class Font
 *
 * @package ystandard
 */
class Font {

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
}
