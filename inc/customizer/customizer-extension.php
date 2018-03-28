<?php
/**
 * 拡張機能
 *
 * @package ystandard
 * @author yosiakatsuki
 * @license GPL-2.0+
 */

/**
 * 拡張機能
 *
 * @param  WP_Customize_Manager $wp_customize wp_customize.
 */
function ys_customizer_extension( $wp_customize ) {
	/**
	 * パネルの追加
	 */
	$wp_customize->add_panel(
		'ys_customizer_panel_extension',
		array(
			'priority'        => 2000,
			'title'           => '[ys]拡張機能設定',
			'description'     => 'yStandard専用プラグイン等による拡張機能の設定',
			'active_callback' => array(),
		)
	);
	apply_filters( 'ys_customizer_add_extension', $wp_customize );
}