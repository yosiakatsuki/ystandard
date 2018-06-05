<?php
/**
 * 広告設定
 *
 * @package ystandard
 * @author yosiakatsuki
 * @license GPL-2.0+
 */

/**
 * 広告設定
 *
 * @param  WP_Customize_Manager $wp_customize wp_customize.
 */
function ys_customizer_advertisement( $wp_customize ) {
	/**
	 * パネルの追加
	 */
	$wp_customize->add_panel(
		'ys_customizer_panel_advertisement',
		array(
			'title'    => '[ys]広告設定',
			'priority' => 1130,
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
	/**
	 * インフィード広告
	 */
	ys_customizer_advertisement_add_infeed( $wp_customize );
}

/**
 * PC用広告
 *
 * @param  WP_Customize_Manager $wp_customize wp_customize.
 */
function ys_customizer_advertisement_add_ads_pc( $wp_customize ) {
	$ys_customizer = new YS_Customizer( $wp_customize );
	/**
	 * セクション追加
	 */
	$ys_customizer->add_section( array(
		'section' => 'ys_customizer_section_ads_pc',
		'title'   => 'PC広告設定',
		'panel'   => 'ys_customizer_panel_advertisement',
	) );
	/**
	 * 記事タイトル上
	 */
	$ys_customizer->add_textarea(
		array(
			'id'      => 'ys_advertisement_before_title',
			'default' => '',
			'label'   => '記事タイトル上',
		)
	);
	/**
	 * 記事タイトル下
	 */
	$ys_customizer->add_textarea(
		array(
			'id'      => 'ys_advertisement_after_title',
			'default' => '',
			'label'   => '記事タイトル下',
		)
	);
	/**
	 * 記事本文上
	 * 旧ys_advertisement_under_title
	 */
	$ys_customizer->add_textarea(
		array(
			'id'      => 'ys_advertisement_before_content',
			'default' => '',
			'label'   => '記事本文上(PC)',
		)
	);
	/**
	 * Moreタグ部分
	 */
	$ys_customizer->add_textarea(
		array(
			'id'      => 'ys_advertisement_replace_more',
			'default' => '',
			'label'   => 'moreタグ部分(PC)',
		)
	);
	/**
	 * 記事本文下（左）
	 */
	$ys_customizer->add_textarea(
		array(
			'id'      => 'ys_advertisement_under_content_left',
			'default' => '',
			'label'   => '記事本文下（左）',
		)
	);
	/**
	 * 記事本文下（右）
	 */
	$ys_customizer->add_textarea(
		array(
			'id'      => 'ys_advertisement_under_content_right',
			'default' => '',
			'label'   => '記事本文下（右）',
		)
	);
}

/**
 * モバイル用広告
 *
 * @param  WP_Customize_Manager $wp_customize wp_customize.
 */
function ys_customizer_advertisement_add_ads_sp( $wp_customize ) {
	$ys_customizer = new YS_Customizer( $wp_customize );
	/**
	 * セクション追加
	 */
	$ys_customizer->add_section( array(
		'section' => 'ys_customizer_section_ads_sp',
		'title'   => 'モバイル広告設定',
		'panel'   => 'ys_customizer_panel_advertisement',
	) );
	/**
	 * 記事タイトル上
	 */
	$ys_customizer->add_textarea(
		array(
			'id'      => 'ys_advertisement_before_title_sp',
			'default' => '',
			'label'   => '記事タイトル上',
		)
	);
	/**
	 * 記事タイトル下
	 */
	$ys_customizer->add_textarea(
		array(
			'id'      => 'ys_advertisement_after_title_sp',
			'default' => '',
			'label'   => '記事タイトル下',
		)
	);
	/**
	 * 記事本文上
	 * 旧ys_advertisement_under_title_sp
	 */
	$ys_customizer->add_textarea(
		array(
			'id'      => 'ys_advertisement_before_content_sp',
			'default' => '',
			'label'   => '記事本文上(SP)',
		)
	);
	/**
	 * Moreタグ部分
	 */
	$ys_customizer->add_textarea(
		array(
			'id'      => 'ys_advertisement_replace_more_sp',
			'default' => '',
			'label'   => 'moreタグ部分(SP)',
		)
	);
	/**
	 * 記事本文下（SP）
	 */
	$ys_customizer->add_textarea(
		array(
			'id'      => 'ys_advertisement_under_content_sp',
			'default' => '',
			'label'   => '記事本文下（SP）',
		)
	);
}
/**
 * インフィード広告
 *
 * @param  WP_Customize_Manager $wp_customize wp_customize.
 */
function ys_customizer_advertisement_add_infeed( $wp_customize ) {
	$ys_customizer = new YS_Customizer( $wp_customize );
	/**
	 * セクション追加
	 */
	$ys_customizer->add_section( array(
		'section' => 'ys_customizer_section_infeed',
		'title'   => 'インフィード広告設定',
		'panel'   => 'ys_customizer_panel_advertisement',
	) );
	/**
	 * PC用広告
	 */
	$ys_customizer->add_textarea(
		array(
			'id'      => 'ys_advertisement_infeed_pc',
			'default' => '',
			'label'   => 'PC用広告',
		)
	);
	/**
	 * 広告を表示する間隔
	 */
	$ys_customizer->add_number(
		array(
			'id'          => 'ys_advertisement_infeed_pc_step',
			'default'     => 3,
			'label'       => '広告を表示する間隔(PC)',
			'input_attrs' => array(
				'min' => 1,
				'max' => 100,
			),
		)
	);
	/**
	 * 表示する広告の最大数
	 */
	$ys_customizer->add_number(
		array(
			'id'          => 'ys_advertisement_infeed_pc_limit',
			'default'     => 3,
			'label'       => '表示する広告の最大数(PC)',
			'input_attrs' => array(
				'min' => 1,
				'max' => 100,
			),
		)
	);
	/**
	 * SP用広告
	 */
	$ys_customizer->add_textarea(
		array(
			'id'      => 'ys_advertisement_infeed_sp',
			'default' => '',
			'label'   => 'SP用広告',
		)
	);
	/**
	 * 広告を表示する間隔
	 */
	$ys_customizer->add_number(
		array(
			'id'          => 'ys_advertisement_infeed_sp_step',
			'default'     => 3,
			'label'       => '広告を表示する間隔(SP)',
			'input_attrs' => array(
				'min' => 1,
				'max' => 100,
			),
		)
	);
	/**
	 * 表示する広告の最大数
	 */
	$ys_customizer->add_number(
		array(
			'id'          => 'ys_advertisement_infeed_sp_limit',
			'default'     => 3,
			'label'       => '表示する広告の最大数(SP)',
			'input_attrs' => array(
				'min' => 1,
				'max' => 100,
			),
		)
	);

}
