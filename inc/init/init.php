<?php
/**
 * Init
 *
 * @package ystandard
 * @author  yosiakatsuki
 * @license GPL-2.0+
 */

/**
 * クラス読み込み
 */
require_once dirname( __FILE__ ) . '/class-ys-init.php';

/**
 * 初期化処理
 */
$ys_init = new YS_Init();


/**
 * ブロックエディター文字サイズ設定
 *
 * @return array
 */
function ys_get_editor_font_sizes() {
	/**
	 * TODO: クラス化
	 */
	$size = array(
		array(
			'name'      => __( '極小', 'ystandard' ),
			'shortName' => __( 'x-small', 'ystandard' ),
			'size'      => 12,
			'slug'      => 'x-small',
		),
		array(
			'name'      => __( '小', 'ystandard' ),
			'shortName' => __( 'small', 'ystandard' ),
			'size'      => 14,
			'slug'      => 'small',
		),
		array(
			'name'      => __( '標準', 'ystandard' ),
			'shortName' => __( 'normal', 'ystandard' ),
			'size'      => 16,
			'slug'      => 'normal',
		),
		array(
			'name'      => __( '中', 'ystandard' ),
			'shortName' => __( 'medium', 'ystandard' ),
			'size'      => 18,
			'slug'      => 'medium',
		),
		array(
			'name'      => __( '大', 'ystandard' ),
			'shortName' => __( 'large', 'ystandard' ),
			'size'      => 20,
			'slug'      => 'large',
		),
		array(
			'name'      => __( '極大', 'ystandard' ),
			'shortName' => __( 'x-large', 'ystandard' ),
			'size'      => 22,
			'slug'      => 'x-large',
		),
		array(
			'name'      => __( '巨大', 'ystandard' ),
			'shortName' => __( 'xx-large', 'ystandard' ),
			'size'      => 26,
			'slug'      => 'xx-large',
		),
	);

	return apply_filters( 'ys_editor_font_sizes', $size );
}

/**
 * 色設定の定義
 *
 * @param bool $all ユーザー定義追加.
 *
 * @return array
 */
function ys_get_editor_color_palette( $all = true ) {
	/**
	 * TODO: クラス化
	 */
	$color = array(
		array(
			'name'        => '青',
			'slug'        => 'ys-blue',
			'color'       => ys_get_option( 'ys-color-palette-ys-blue' ),
			'default'     => '#82B9E3',
			'description' => '',
		),
		array(
			'name'        => '赤',
			'slug'        => 'ys-red',
			'color'       => ys_get_option( 'ys-color-palette-ys-red' ),
			'default'     => '#D53939',
			'description' => '',
		),
		array(
			'name'        => '緑',
			'slug'        => 'ys-green',
			'color'       => ys_get_option( 'ys-color-palette-ys-green' ),
			'default'     => '#92C892',
			'description' => '',
		),
		array(
			'name'        => '黄',
			'slug'        => 'ys-yellow',
			'color'       => ys_get_option( 'ys-color-palette-ys-yellow' ),
			'default'     => '#F5EC84',
			'description' => '',
		),
		array(
			'name'        => 'オレンジ',
			'slug'        => 'ys-orange',
			'color'       => ys_get_option( 'ys-color-palette-ys-orange' ),
			'default'     => '#EB962D',
			'description' => '',
		),
		array(
			'name'        => '紫',
			'slug'        => 'ys-purple',
			'color'       => ys_get_option( 'ys-color-palette-ys-purple' ),
			'default'     => '#B67AC2',
			'description' => '',
		),
		array(
			'name'        => '灰色',
			'slug'        => 'ys-gray',
			'color'       => ys_get_option( 'ys-color-palette-ys-gray' ),
			'default'     => '#757575',
			'description' => '',
		),
		array(
			'name'        => '薄灰色',
			'slug'        => 'ys-light-gray',
			'color'       => ys_get_option( 'ys-color-palette-ys-light-gray' ),
			'default'     => '#F7F7F8',
			'description' => '',
		),
		array(
			'name'        => '黒',
			'slug'        => 'ys-black',
			'color'       => ys_get_option( 'ys-color-palette-ys-black' ),
			'default'     => '#000000',
			'description' => '',
		),
		array(
			'name'        => '白',
			'slug'        => 'ys-white',
			'color'       => ys_get_option( 'ys-color-palette-ys-white' ),
			'default'     => '#ffffff',
			'description' => '',
		),
	);

	/**
	 * ユーザー定義情報の追加
	 */
	for ( $i = 1; $i <= 3; $i ++ ) {
		$option_name = 'ys-color-palette-ys-user-' . $i;
		if ( $all || ys_get_option( $option_name ) !== ys_get_option_default( $option_name ) ) {
			$color[] = array(
				'name'        => 'ユーザー定義' . $i,
				'slug'        => 'ys-user-' . $i,
				'color'       => ys_get_option( $option_name ),
				'default'     => ys_get_option_default( $option_name ),
				'description' => 'よく使う色を設定しておくと便利です。',
			);
		}
	}

	return apply_filters( 'ys_editor_color_palette', $color );
}