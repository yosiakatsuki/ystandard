<?php
/**
 * 上級者向け設定
 *
 * @package ystandard
 * @author yosiakatsuki
 * @license GPL-2.0+
 */

/**
 * 上級者向け設定
 *
 * @param  WP_Customize_Manager $wp_customize wp_customize.
 */
function ys_customizer_advanced( $wp_customize ) {
	/**
	 * パネルの追加
	 */
	$wp_customize->add_panel(
		'ys_customizer_panel_advanced',
		array(
			'priority' => 1200,
			'title'    => '[ys]上級者向け設定',
		)
	);
	/**
	 * SNS用JavaScriptの読み込み
	 */
	ys_customizer_advanced_add_load_script( $wp_customize );
}


/**
 * SNS用JavaScriptの読み込み
 *
 * @param  WP_Customize_Manager $wp_customize wp_customize.
 */
function ys_customizer_advanced_add_load_script( $wp_customize ) {
	/**
	 * セクション追加
	 */
	$wp_customize->add_section(
		'ys_customizer_section_load_script',
		array(
			'title'    => 'SNS用JavaScriptの読み込み',
			'panel'    => 'ys_customizer_panel_advanced',
			'priority' => 1,
		)
	);
	/**
	 * Twitter用JavaScriptを読み込む
	 */
	ys_customizer_add_setting_checkbox(
		$wp_customize,
		array(
			'id'          => 'ys_load_script_twitter',
			'label'       => 'Twitter用JavaScriptを読み込む',
			'description' => '※Twitterのフォローボタンなどをサイト内で使用する場合、こちらにチェックを入れてください。<br>※Twitter用のJavaScriptを&lt;/body&gt;直前で読み込みます',
			'default'     => 0,
			'section'     => 'ys_customizer_section_load_script',
			'transport'   => 'postMessage',
		)
	);
	/**
	 *  Facebook用JavaScriptを読み込む
	 */
	ys_customizer_add_setting_checkbox(
		$wp_customize,
		array(
			'id'          => 'ys_load_script_facebook',
			'label'       => 'Facebook用JavaScriptを読み込む',
			'description' => '※FacebookのいいねボタンやPagePluginなどをサイト内で使用する場合、こちらにチェックを入れてください。<br>※Facebook用のJavaScriptを&lt;/body&gt;直前で読み込みます',
			'default'     => 0,
			'section'     => 'ys_customizer_section_load_script',
			'transport'   => 'postMessage',
		)
	);
}