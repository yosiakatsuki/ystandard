<?php
/**
 * サイト共通設定
 *
 * @package ystandard
 * @author yosiakatsuki
 * @license GPL-2.0+
 */

/**
 * サイト共通設定
 *
 * @param  WP_Customize_Manager $wp_customize wp_customize.
 */
function ys_customizer_site( $wp_customize ) {
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
 *
 * @param  WP_Customize_Manager $wp_customize wp_customize.
 */
function ys_customizer_site_add_common( $wp_customize ) {
	$ys_customizer = new YS_Customizer( $wp_customize );
	/**
	 * Titleタグの区切り文字
	 */
	$ys_customizer->add_text(
		array(
			'id'          => 'ys_title_separate',
			'default'     => '-',
			'transport'   => 'postMessage',
			'label'       => 'titleタグの区切り文字',
			'description' => '※区切り文字の前後に半角空白が自動で挿入されます',
			'section'     => 'ys_customizer_section_site_common',
		)
	);
	/**
	 * 発行年数
	 */
	$ys_customizer->add_number(
		array(
			'id'          => 'ys_copyright_year',
			'default'     => (int) date_i18n( 'Y' ),
			'label'       => '発行年(Copyright)',
			'section'     => 'ys_customizer_section_site_common',
			'input_attrs' => array(
				'min' => 1900,
				'max' => 2100,
			),
		)
	);
	/**
	 * 抜粋文字数
	 */
	$ys_customizer->add_number(
		array(
			'id'      => 'ys_option_excerpt_length',
			'default' => 80,
			'label'   => '投稿抜粋の文字数',
			'section' => 'ys_customizer_section_site_common',
		)
	);
}
