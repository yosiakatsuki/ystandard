<?php
/**
 *
 *	高速化設定
 *
 */
function ys_customizer_performance_tuning( $wp_customize ){
	/**
	 * パネルの追加
	 */
	$wp_customize->add_panel(
										'ys_customizer_panel_performance_tuning',
										array(
											'priority'        => 1120,
											'title'           => '[ys]サイト高速化設定',
											'description'     => 'サイト高速化を行うための設定',
											'active_callback' => array()
										)
									);
	/**
	 * WordPress標準機能で読み込むCSS・javascriptの無効化
	 */
	ys_customizer_performance_tuning_add_disable_wp_scripts( $wp_customize );
}

/**
 * WordPress標準機能で読み込むCSS・javascriptの無効化
 */
function ys_customizer_performance_tuning_add_disable_wp_scripts( $wp_customize ) {
	/**
	 * セクション追加
	 */
	$wp_customize->add_section(
										'ys_customizer_section_disable_wp_scripts',
										array(
											'title'           => 'WordPress標準機能で読み込むCSS・javascriptの無効化',
											'panel'           => 'ys_customizer_panel_performance_tuning',
											'priority'        => 1,
											'description'     => 'WordPress標準機能で読み込むCSS・javascriptの無効化設定',
											'active_callback' => array()
										)
									);
	/**
	 * 絵文字を出力しない
	 */
	ys_customizer_add_setting_checkbox(
		$wp_customize,
		array(
			'id'        => 'ys_performance_tuning_disable_emoji',
			'label'       => '絵文字関連スタイルシート・スクリプトを出力しない',
			'default'   => 1,
			'section'   => 'ys_customizer_section_disable_wp_scripts',
		)
	);
	/**
	 * 絵文字を出力しない
	 */
	ys_customizer_add_setting_checkbox(
		$wp_customize,
		array(
			'id'        => 'ys_performance_tuning_disable_oembed',
			'label'       => 'oembed関連スタイルシート・スクリプトを出力しない',
			'default'   => 1,
			'section'   => 'ys_customizer_section_disable_wp_scripts',
		)
	);
}