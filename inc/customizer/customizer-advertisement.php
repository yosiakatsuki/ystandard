<?php
/**
 * @package ystandard
 * @author yosiakatsuki
 * @license GPL-2.0+
 */
/**
 *	広告設定
 */
function ys_customizer_advertisement( $wp_customize ){
	/**
	 * パネルの追加
	 */
	$wp_customize->add_panel(
										'ys_customizer_panel_advertisement',
										array(
											'priority'       => 1130,
											'title'          => '[ys]広告設定'
										)
									);
	/**
	 * PC用広告
	 */
	ys_customizer_advertisement_add_ads_pc( $wp_customize );
	/**
	 * モバイル用広告
	 */
	ys_customizer_advertisement_add_ads_sp( $wp_customize );
}

/**
 * PC用広告
 */
function ys_customizer_advertisement_add_ads_pc( $wp_customize ) {
		/**
		 * セクション追加
		 */
		$wp_customize->add_section(
											'ys_customizer_section_ads_pc',
											array(
												'title'    => 'PC広告設定',
												'panel'    => 'ys_customizer_panel_advertisement',
											)
										);
	/**
	 * 記事タイトル下
	 */
	ys_customizer_add_setting_textarea(
		$wp_customize,
		array(
			'id'        => 'ys_advertisement_under_title',
			'default'   => '',
			'label'     => '記事タイトル下(PC)',
			'description' => '',
			'section'   => 'ys_customizer_section_ads_pc',
		)
	);
	/**
	 * moreタグ部分
	 */
	ys_customizer_add_setting_textarea(
		$wp_customize,
		array(
			'id'        => 'ys_advertisement_replace_more',
			'default'   => '',
			'label'     => 'moreタグ部分(PC)',
			'description' => '',
			'section'   => 'ys_customizer_section_ads_pc',
		)
	);
	/**
	 * 記事本文下（左）
	 */
	ys_customizer_add_setting_textarea(
		$wp_customize,
		array(
			'id'        => 'ys_advertisement_under_content_left',
			'default'   => '',
			'label'     => '記事本文下（左）',
			'description' => '',
			'section'   => 'ys_customizer_section_ads_pc',
		)
	);
	/**
	 * 記事本文下（右）
	 */
	ys_customizer_add_setting_textarea(
		$wp_customize,
		array(
			'id'        => 'ys_advertisement_under_content_right',
			'default'   => '',
			'label'     => '記事本文下（右）',
			'description' => '',
			'section'   => 'ys_customizer_section_ads_pc',
		)
	);
}

/**
 * モバイル用広告
 */
function ys_customizer_advertisement_add_ads_sp( $wp_customize ) {
		/**
		 * セクション追加
		 */
		$wp_customize->add_section(
											'ys_customizer_section_ads_sp',
											array(
												'title'    => 'モバイル広告設定',
												'panel'    => 'ys_customizer_panel_advertisement',
											)
										);
	/**
	 * 記事タイトル下
	 */
	ys_customizer_add_setting_textarea(
		$wp_customize,
		array(
			'id'        => 'ys_advertisement_under_title_sp',
			'default'   => '',
			'label'     => '記事タイトル下(SP)',
			'description' => '',
			'section'   => 'ys_customizer_section_ads_sp',
		)
	);
	/**
	 * moreタグ部分
	 */
	ys_customizer_add_setting_textarea(
		$wp_customize,
		array(
			'id'        => 'ys_advertisement_replace_more_sp',
			'default'   => '',
			'label'     => 'moreタグ部分(SP)',
			'description' => '',
			'section'   => 'ys_customizer_section_ads_sp',
		)
	);
	/**
	 * 記事本文下（SP）
	 */
	ys_customizer_add_setting_textarea(
		$wp_customize,
		array(
			'id'        => 'ys_advertisement_under_content_sp',
			'default'   => '',
			'label'     => '記事本文下（SP）',
			'description' => '',
			'section'   => 'ys_customizer_section_ads_sp',
		)
	);
}