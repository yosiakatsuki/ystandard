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
