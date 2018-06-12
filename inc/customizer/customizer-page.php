<?php
/**
 * 固定ページ設定
 *
 * @package ystandard
 * @author  yosiakatsuki
 * @license GPL-2.0+
 */

/**
 * 固定ページ設定
 *
 * @param  WP_Customize_Manager $wp_customize wp_customize.
 */
function ys_customizer_page( $wp_customize ) {
	/**
	 * セクション追加
	 * active_callbackが効かないのでデザイン設定の中に入れる
	 */
	$wp_customize->add_section(
		'ys_customizer_section_page',
		array(
			'title'           => '固定ページ設定',
			'panel'           => 'ys_customizer_panel_design',
			'priority'        => 1,
			'active_callback' => 'ys_customizer_active_callback_page',
		)
	);
	/**
	 * 固定ページ設定
	 */
	ys_customizer_page_add_settings( $wp_customize );
}

/**
 * 固定ページ設定の表示条件
 */
function ys_customizer_active_callback_page() {
	return true;

	// TODO:active_callbackが効かない.
	return is_page();
}

/**
 * 固定ページ設定
 *
 * @param  WP_Customize_Manager $wp_customize wp_customize.
 */
function ys_customizer_page_add_settings( $wp_customize ) {
	$ys_customizer = new YS_Customizer( $wp_customize );
	/**
	 * 表示カラム数
	 */
	$assets_url = ys_get_template_customizer_assets_img_dir_uri();
	$col1       = $assets_url . '/design/column-type/col-1.png';
	$col2       = $assets_url . '/design/column-type/col-2.png';
	$img        = '<img src="%s" alt="" width="100" height="100" />';
	$ys_customizer->add_image_label_radio(
		array(
			'id'          => 'ys_page_layout',
			'default'     => '2col',
			'label'       => 'レイアウト',
			'description' => '固定ページの表示レイアウト<br>※デフォルトテンプレートの表示レイアウト',
			'section'     => 'ys_customizer_section_page',
			'choices'     => array(
				'2col' => sprintf( $img, $col2 ),
				'1col' => sprintf( $img, $col1 ),
			),
		)
	);
	/**
	 * 記事上部表示設定
	 */
	$ys_customizer->add_label( array(
		'id'      => 'ys_above_page_label',
		'label'   => '記事上部設定',
		'section' => 'ys_customizer_section_page',
	) );
	/**
	 * アイキャッチ画像を表示する
	 */
	$ys_customizer->add_checkbox(
		array(
			'id'          => 'ys_show_page_thumbnail',
			'default'     => 1,
			'label'       => 'アイキャッチ画像を表示する',
			'description' => '※投稿内の先頭にアイキャッチ画像を配置している場合、こちらの設定を無効にすることにより画像が2枚連続で表示されないようにします。（他ブログサービスからの引っ越してきた場合に役立つかもしれません）',
			'section'     => 'ys_customizer_section_page',
		)
	);
	/**
	 * 投稿日時を表示する
	 */
	$ys_customizer->add_checkbox(
		array(
			'id'      => 'ys_show_page_publish_date',
			'default' => 1,
			'label'   => '投稿日・更新日を表示する',
			'section' => 'ys_customizer_section_page',
		)
	);
	/**
	 * 記事下表示設定
	 */
	$ys_customizer->add_label( array(
		'id'          => 'ys_below_page_label',
		'label'       => '記事下表示設定',
		'description' => '※シェアボタンの表示・非表示は[SNS設定]→[SNSシェアボタン設定]から行って下さい',
		'section'     => 'ys_customizer_section_page',
	) );
	/**
	 * ブログフォローボックスを表示する
	 */
	$ys_customizer->add_checkbox(
		array(
			'id'      => 'ys_show_page_follow_box',
			'default' => 1,
			'label'   => 'ブログフォローボックスを表示する',
			'section' => 'ys_customizer_section_page',
		)
	);
	/**
	 * 著者情報を表示する
	 */
	$ys_customizer->add_checkbox(
		array(
			'id'      => 'ys_show_page_author',
			'default' => 1,
			'label'   => '著者情報を表示する',
			'section' => 'ys_customizer_section_page',
		)
	);
}
