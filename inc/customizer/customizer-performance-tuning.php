<?php
/**
 * 高速化設定
 *
 * @package ystandard
 * @author yosiakatsuki
 * @license GPL-2.0+
 */

/**
 * 高速化設定の追加
 *
 * @param  WP_Customize_Manager $wp_customize wp_customize.
 */
function ys_customizer_performance_tuning( $wp_customize ) {
	/**
	 * パネルの追加
	 */
	$wp_customize->add_panel(
		'ys_customizer_panel_performance_tuning',
		array(
			'priority'        => 1120,
			'title'           => '[ys]サイト高速化設定',
			'description'     => 'サイト高速化を行うための設定',
			'active_callback' => array(),
		)
	);
	/**
	 * WordPress標準機能で読み込むCSS・JavaScriptの無効化
	 */
	ys_customizer_performance_tuning_add_disable_wp_scripts( $wp_customize );
	/**
	 * CSS読み込みの最適化
	 */
	ys_customizer_performance_tuning_add_optimize_load_css( $wp_customize );
}

/**
 * WordPress標準機能で読み込むCSS・JavaScriptの無効化
 *
 * @param  WP_Customize_Manager $wp_customize wp_customize.
 */
function ys_customizer_performance_tuning_add_disable_wp_scripts( $wp_customize ) {
	/**
	 * セクション追加
	 */
	$wp_customize->add_section(
		'ys_customizer_section_disable_wp_scripts',
		array(
			'title'           => 'WordPress標準機能で読み込むCSS・JavaScriptの無効化',
			'panel'           => 'ys_customizer_panel_performance_tuning',
			'priority'        => 1,
			'description'     => 'WordPress標準機能で読み込むCSS・JavaScriptの無効化設定',
			'active_callback' => array(),
		)
	);
	/**
	 * 絵文字を出力しない
	 */
	ys_customizer_add_setting_checkbox(
		$wp_customize,
		array(
			'id'      => 'ys_performance_tuning_disable_emoji',
			'label'   => '絵文字関連のスタイルシート・スクリプトを無効化する',
			'default' => 1,
			'section' => 'ys_customizer_section_disable_wp_scripts',
		)
	);
	/**
	 * 絵文字を出力しない
	 */
	ys_customizer_add_setting_checkbox(
		$wp_customize,
		array(
			'id'      => 'ys_performance_tuning_disable_oembed',
			'label'   => 'oembed関連のスタイルシート・スクリプトを無効化する',
			'default' => 1,
			'section' => 'ys_customizer_section_disable_wp_scripts',
		)
	);
}
/**
 * CSS読み込みの最適化
 *
 * @param  WP_Customize_Manager $wp_customize wp_customize.
 */
function ys_customizer_performance_tuning_add_optimize_load_css( $wp_customize ) {
	/**
	 * セクション追加
	 */
	$wp_customize->add_section(
		'ys_customizer_section_optimize_load_css',
		array(
			'title'           => 'CSS読み込み最適化設定（上級者向け）',
			'panel'           => 'ys_customizer_panel_performance_tuning',
			'priority'        => 1,
			'description'     => 'CSSの読み込み方式を最適化します。',
			'active_callback' => array(),
		)
	);
	/**
	 * CSSの読み込みを最適化する
	 */
	ys_customizer_add_setting_checkbox(
		$wp_customize,
		array(
			'id'          => 'ys_performance_tuning_optimize_load_css',
			'label'       => 'CSSの読み込みを最適化する',
			'default'     => 0,
			'description' => 'この設定をONにすると、CSSが「ファーストビューに関わる部分」「ファーストビュー以外」の2つに別れます。※詳しい説明は別途用意します',
			'section'     => 'ys_customizer_section_optimize_load_css',
		)
	);
}