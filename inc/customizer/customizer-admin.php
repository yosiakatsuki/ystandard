<?php
/**
 * サイト運営支援
 *
 * @package ystandard
 * @author  yosiakatsuki
 * @license GPL-2.0+
 */

/**
 * サイト運営支援
 *
 * @param  WP_Customize_Manager $wp_customize wp_customize.
 */
function ys_customizer_admin( $wp_customize ) {
	/**
	 * セクション追加
	 */
	$wp_customize->add_section(
		'ys_customizer_section_admin',
		array(
			'title'       => '[ys]サイト運営支援',
			'priority'    => 1500,
			'description' => 'サイト管理画面内の機能設定',

		)
	);
	/**
	 * サイト運営支援
	 */
	ys_customizer_add_admin( $wp_customize );
}

/**
 * サイト運営支援
 *
 * @param  WP_Customize_Manager $wp_customize wp_customize.
 */
function ys_customizer_add_admin( $wp_customize ) {
	$ys_customizer = new YS_Customizer( $wp_customize );
	/**
	 * ビジュアルエディタ用CSSの追加
	 */
	$ys_customizer->add_label(
		array(
			'id'          => 'ys_admin_enable_tiny_mce_style_label',
			'label'       => 'ビジュアルエディタ用CSSの追加',
			'description' => 'ビジュアルエディタにテーマのCSSを反映する。<br>※クラシックエディターで有効な設定です。',
			'section'     => 'ys_customizer_section_admin',
		)
	);
	$ys_customizer->add_checkbox(
		array(
			'id'        => 'ys_admin_enable_tiny_mce_style',
			'default'   => 1,
			'transport' => 'postMessage',
			'label'     => 'ビジュアルエディタ用CSSを追加する',
			'section'   => 'ys_customizer_section_admin',
		)
	);

}
