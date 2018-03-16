<?php
/**
 * アーカイブページ設定
 *
 * @package ystandard
 * @author yosiakatsuki
 * @license GPL-2.0+
 */

/**
 * アーカイブページ設定
 *
 * @param  WP_Customize_Manager $wp_customize wp_customize.
 */
function ys_customizer_archive( $wp_customize ) {
	/**
	 * セクション追加
	 * active_callbackが効かないのでデザイン設定の中に入れる
	 */
	$wp_customize->add_section(
		'ys_customizer_section_archive',
		array(
			'title'           => 'アーカイブページ設定',
			'panel'           => 'ys_customizer_panel_design',
			'priority'        => 1,
			'active_callback' => 'ys_customizer_active_callback_archive',
		)
	);
	/**
	 * 固定ページ設定
	 */
	ys_customizer_archive_add_settings( $wp_customize );
}
/**
 * アーカイブページ設定の表示条件
 */
function ys_customizer_active_callback_archive() {
	return true;
	// TODO:active_callbackが効かない.
	return is_archive();
}

/**
 * アーカイブページ設定
 *
 * @param  WP_Customize_Manager $wp_customize wp_customize.
 */
function ys_customizer_archive_add_settings( $wp_customize ) {

	/**
	 * 一覧タイプ
	 */
	$assets_url = ys_get_template_customizer_assets_img_dir_uri();
	$list       = $assets_url . '/design/archive/list.png';
	$card       = $assets_url . '/design/archive/card.png';
	$img        = '<img src="%s" alt="" width="100" height="100" />';
	ys_customizer_add_setting_image_label_radio(
		$wp_customize,
		array(
			'id'          => 'ys_archive_type',
			'default'     => 'list',
			'label'       => '一覧タイプ',
			'description' => '記事一覧の表示タイプ',
			'section'     => 'ys_customizer_section_archive',
			'choices'     => array(
				'list' => sprintf( $img, $list ),
				'card' => sprintf( $img, $card ),
			),
		)
	);
}