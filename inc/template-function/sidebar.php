<?php
/**
 * サイドバー関連の読み込み
 *
 * @package ystandard
 * @author  yosiakatsuki
 * @license GPL-3.0+
 */
defined( 'ABSPATH' ) || die();

/**
 * サイドバーを表示するか
 */
function ys_is_active_sidebar() {
	return \ystandard\Widget::is_active_sidebar();
}

/**
 * サイドバーのクラスを出力
 *
 * @param array|string $classes クラス.
 *
 * @return void
 */
function ys_sidebar_class( $classes = [] ) {
	echo ys_get_sidebar_class( $classes );
}

/**
 * サイドバーのクラスを作成
 *
 * @param array|string $classes クラス.
 *
 * @return string
 */
function ys_get_sidebar_class( $classes = [] ) {
	$sidebar_classes = [
		'sidebar',
		'sidebar-widget',
		'widget-area',
	];
	if ( ! is_array( $classes ) ) {
		$classes = [ $classes ];
	}
	$classes = array_merge( $sidebar_classes, $classes );

	$classes = apply_filters( 'ys_sidebar_class', $classes );

	return esc_attr( implode( ' ', $classes ) );
}
