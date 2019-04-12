<?php
/**
 * フロントページ設定
 *
 * @package ystandard
 * @author  yosiakatsuki
 * @license GPL-2.0+
 */

/**
 * フロントページ設定
 *
 * @param  WP_Customize_Manager $wp_customize wp_customize.
 */
function ys_customizer_front_page( $wp_customize ) {
	$wp_customize->add_panel(
		'ys_customizer_panel_font_page',
		array(
			'title'           => '[ys]フロントページ設定',
			'priority'        => 1050,
			'description'     => 'フロントページ設定',
			'active_callback' => 'ys_customizer_active_callback_front_page_panel',
		)
	);
	/**
	 * デザイン設定
	 */
	ys_customizer_front_page_design( $wp_customize );
}

/**
 * フロントページ設定の表示条件
 */
function ys_customizer_active_callback_front_page_panel() {
	$show_on_front = get_option( 'show_on_front' );
	$page_on_front = get_option( 'page_on_front' );
	if ( 'page' === $show_on_front && $page_on_front ) {
		return true;
	}

	return false;
}

/**
 * デザイン設定
 *
 * @param  WP_Customize_Manager $wp_customize wp_customize.
 */
function ys_customizer_front_page_design( $wp_customize ) {
	$ys_customizer = new YS_Customizer( $wp_customize );
	/**
	 * セクション追加
	 */
	$ys_customizer->add_section(
		array(
			'section'     => 'ys_customizer_front_page_design',
			'title'       => 'デザイン設定',
			'priority'    => 1,
			'description' => 'フロントページのデザイン設定',
			'panel'       => 'ys_customizer_panel_font_page',
		)
	);
	/**
	 * 表示カラム数
	 */
	$assets_url = ys_get_template_customizer_assets_img_dir_uri();
	$col1       = $assets_url . '/design/column-type/col-1.png';
	$col2       = $assets_url . '/design/column-type/col-2.png';
	$img        = '<img src="%s" alt="" width="100" height="100" />';
	$ys_customizer->add_image_label_radio(
		array(
			'id'          => 'ys_front_page_layout',
			'default'     => '2col',
			'label'       => 'レイアウト',
			'description' => 'フロントページの表示レイアウト',
			'choices'     => array(
				'2col' => sprintf( $img, $col2 ),
				'1col' => sprintf( $img, $col1 ),
			),
		)
	);
}
