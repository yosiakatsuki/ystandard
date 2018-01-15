<?php
/**
 *
 *	共通デザイン設定
 *
 */
function ys_customizer_design( $wp_customize ){
	/**
	 * パネルの追加
	 */
	$wp_customize->add_panel(
										'ys_customizer_panel_design',
										array(
											'priority'        => 1010,
											'title'           => '[ys]デザイン設定',
											'description'     => 'サイト共通部分のデザイン設定',
											'active_callback' => array()
										)
									);
	/**
	 * ヘッダー設定
	 */
	ys_customizer_design_add_header( $wp_customize );
}

/**
 * ヘッダー設定
 */
function ys_customizer_design_add_header( $wp_customize ) {
	/**
	 * セクション追加
	 */
	$wp_customize->add_section(
										'ys_customizer_section_header_design',
										array(
											'title'           => 'ヘッダー設定',
											'panel'           => 'ys_customizer_panel_design',
											'priority'        => 1,
											'description'     => 'ヘッダー部分のデザイン設定',
											'active_callback' => array()
										)
									);
	/**
	 * ヘッダータイプ
	 */
	$assets_url = ys_get_template_customizer_assets_img_dir_uri();
	$row1 = $assets_url . '/design/header/1row.png';
	$center = $assets_url . '/design/header/center.png';
	$row2 = $assets_url . '/design/header/2row.png';
	ys_customizer_add_setting_image_label_radio(
		$wp_customize,
		array(
			'id'          => 'ys_design_header_type',
			'default'     => '1row',
			'label'       => 'ヘッダータイプ',
			'description' => 'ヘッダーの表示タイプ',
			'section'     => 'ys_customizer_section_header_design',
			'choices'     => array(
												'1row'   => $row1,
												'center' => $center,
												'2row'   => $row2
											)
		)
	);
}