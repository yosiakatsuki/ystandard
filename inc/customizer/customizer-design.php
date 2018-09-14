<?php
/**
 * デザイン設定
 *
 * @package ystandard
 * @author  yosiakatsuki
 * @license GPL-2.0+
 */

/**
 * 共通デザイン設定
 *
 * @param  WP_Customize_Manager $wp_customize wp_customize.
 */
function ys_customizer_design( $wp_customize ) {
	/**
	 * パネルの追加
	 */
	$wp_customize->add_panel(
		'ys_customizer_panel_design',
		array(
			'title'           => '[ys]デザイン設定',
			'priority'        => 1010,
			'description'     => 'サイト共通部分のデザイン設定',
			'active_callback' => array(),
		)
	);
	/**
	 * ヘッダー設定
	 */
	ys_customizer_design_add_header( $wp_customize );
	/**
	 * モバイルページ設定
	 */
	ys_customizer_design_add_mobile( $wp_customize );
	/**
	 * ワンカラムテンプレート設定
	 */
	ys_customizer_design_add_one_column_template( $wp_customize );
}

/**
 * ヘッダー設定
 *
 * @param  WP_Customize_Manager $wp_customize wp_customize.
 */
function ys_customizer_design_add_header( $wp_customize ) {
	$ys_customizer = new YS_Customizer( $wp_customize );
	/**
	 * セクション追加
	 */
	$ys_customizer->add_section(
		array(
			'section'     => 'ys_customizer_section_header_design',
			'title'       => 'ヘッダー設定',
			'priority'    => 1,
			'description' => 'ヘッダー部分のデザイン設定',
			'panel'       => 'ys_customizer_panel_design',
		)
	);
	/**
	 * ヘッダータイプ
	 */
	$assets_url = ys_get_template_customizer_assets_img_dir_uri();
	$row1       = $assets_url . '/design/header/1row.png';
	$center     = $assets_url . '/design/header/center.png';
	$row2       = $assets_url . '/design/header/2row.png';
	$img        = '<img src="%s" alt="" width="100" height="100" />';
	$ys_customizer->add_image_label_radio(
		array(
			'id'          => 'ys_design_header_type',
			'default'     => '1row',
			'label'       => 'ヘッダータイプ',
			'description' => 'ヘッダーの表示タイプ',
			'section'     => 'ys_customizer_section_header_design',
			'choices'     => array(
				'1row'   => sprintf( $img, $row1 ),
				'center' => sprintf( $img, $center ),
				'2row'   => sprintf( $img, $row2 ),
			),
		)
	);
}


/**
 * モバイルページ設定
 *
 * @param  WP_Customize_Manager $wp_customize wp_customize.
 */
function ys_customizer_design_add_mobile( $wp_customize ) {
	$ys_customizer = new YS_Customizer( $wp_customize );
	/**
	 * セクション追加
	 */
	$ys_customizer->add_section(
		array(
			'section'     => 'ys_customizer_section_mobile_design',
			'title'       => 'モバイルページ設定',
			'priority'    => 1,
			'description' => 'モバイルページのデザイン設定',
			'panel'       => 'ys_customizer_panel_design',
		)
	);
	/**
	 * サイドバー出力
	 */
	$ys_customizer->add_checkbox(
		array(
			'id'          => 'ys_show_sidebar_mobile',
			'default'     => 0,
			'label'       => 'モバイル表示でサイドバーを非表示にする',
			'description' => 'モバイルページでサイドバー部分を表示しない場合にチェックを付けて下さい',
		)
	);
	/**
	 * スライドメニューに検索フォームを出力する
	 */
	$ys_customizer->add_checkbox(
		array(
			'id'          => 'ys_show_search_form_on_slide_menu',
			'default'     => 0,
			'label'       => 'スライドメニューに検索フォームを出力する(モバイル)',
			'description' => 'モバイルページでスライドメニューに検索フォームを出力する場合にチェックを付けて下さい',
		)
	);
}

/**
 * ワンカラムテンプレート設定
 *
 * @param  WP_Customize_Manager $wp_customize wp_customize.
 */
function ys_customizer_design_add_one_column_template( $wp_customize ) {
	$ys_customizer = new YS_Customizer( $wp_customize );
	/**
	 * セクション追加
	 */
	$ys_customizer->add_section(
		array(
			'section'     => 'ys_customizer_section_one_column_template',
			'title'       => 'ワンカラムテンプレート設定',
			'priority'    => 10,
			'description' => 'ワンカラムテンプレートの設定',
			'panel'       => 'ys_customizer_panel_design',
		)
	);
	/**
	 * ヘッダータイプ
	 */
	$assets_url = ys_get_template_customizer_assets_img_dir_uri();
	$row1       = $assets_url . '/design/one-col-template/full.png';
	$center     = $assets_url . '/design/one-col-template/normal.png';
	$img        = '<img src="%s" alt="" width="100" height="100" />';
	$ys_customizer->add_image_label_radio(
		array(
			'id'          => 'ys_design_one_col_thumbnail_type',
			'default'     => 'full',
			'label'       => 'アイキャッチ画像表示タイプ',
			'description' => 'アイキャッチ画像の表示タイプ',
			'choices'     => array(
				'full'   => sprintf( $img, $row1 ),
				'normal' => sprintf( $img, $center ),
			),
		)
	);
}
