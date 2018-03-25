<?php
/**
 * 投稿ページ設定
 *
 * @package ystandard
 * @author yosiakatsuki
 * @license GPL-2.0+
 */

/**
 * 投稿ページ設定
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
 * 投稿ページ設定
 *
 * @param  WP_Customize_Manager $wp_customize wp_customize.
 */
function ys_customizer_page_add_settings( $wp_customize ) {
	ys_customizer_add_label(
		$wp_customize,
		array(
			'id'      => 'ys_page_thumbnail_label',
			'label'   => 'アイキャッチ画像設定',
			'section' => 'ys_customizer_section_page',
		)
	);
	/**
	 * アイキャッチ画像を表示する
	 */
	ys_customizer_add_setting_checkbox(
		$wp_customize,
		array(
			'id'          => 'ys_show_page_thumbnail',
			'label'       => 'アイキャッチ画像を表示する',
			'description' => '※投稿内の先頭にアイキャッチ画像を配置している場合、こちらの設定を無効にすることにより画像が2枚連続で表示されないようにします。（他ブログサービスからの引っ越してきた場合に役立つかもしれません）',
			'default'     => 1,
			'section'     => 'ys_customizer_section_page',
		)
	);
	ys_customizer_add_label(
		$wp_customize,
		array(
			'id'          => 'ys_below_page_label',
			'label'       => '記事下表示設定',
			'section'     => 'ys_customizer_section_page',
			'description' => '※シェアボタンの表示・非表示は[SNS設定]→[SNSシェアボタン設定]から行って下さい',
		)
	);
	/**
	 * ブログフォローボックスを表示する
	 */
	ys_customizer_add_setting_checkbox(
		$wp_customize,
		array(
			'id'      => 'ys_show_page_follow_box',
			'label'   => 'ブログフォローボックスを表示する',
			'default' => 1,
			'section' => 'ys_customizer_section_page',
		)
	);
	/**
	 * 著者情報を表示する
	 */
	ys_customizer_add_setting_checkbox(
		$wp_customize,
		array(
			'id'      => 'ys_show_page_author',
			'label'   => '著者情報を表示する',
			'default' => 1,
			'section' => 'ys_customizer_section_page',
		)
	);
}