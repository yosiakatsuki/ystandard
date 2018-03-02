<?php
/**
 * @package ystandard
 * @author yosiakatsuki
 * @license GPL-2.0+
 */
/**
 *	サイト共通設定
 */
function ys_customizer_site( $wp_customize ){
	/**
	 * パネルの追加
	 */
	// $wp_customize->add_panel(
	// 									'ys_customizer_panel_site',
	// 									array(
	// 										'priority'       => 1000,
	// 										'title'          => '[ys]サイト共通設定'
	// 									)
	// 								);
	/**
	 * セクション追加
	 */
	$wp_customize->add_section(
										'ys_customizer_section_site_common',
										array(
											'title'    => '[ys]サイト共通設定',
											'priority' => 1000,
										)
									);
	/**
	 * サイト共通設定
	 */
	ys_customizer_site_add_common( $wp_customize );
}

/**
 * サイト共通設定
 */
function ys_customizer_site_add_common( $wp_customize ) {
	/**
	 * titleタグの区切り文字
	 */
	ys_customizer_add_setting_text(
		$wp_customize,
		array(
			'id'        => 'ys_title_separate',
			'default'   => '-',
			'label'     => 'titleタグの区切り文字',
			'description' => '※区切り文字の前後に半角空白が自動で挿入されます',
			'section'   => 'ys_customizer_section_site_common',
			'transport' => 'postMessage'
		)
	);
	/**
	 * 発行年数
	 */
	ys_customizer_add_setting_number(
		$wp_customize,
		array(
			'id'          => 'ys_copyright_year',
			'default'     => (int)date_i18n( 'Y' ),
			'label'       => '発行年(Copyright)',
			'section'     => 'ys_customizer_section_site_common',
			'input_attrs' => array(
												'min'  => 1900,
												'max'  => 2100
											)
		)
	);
}