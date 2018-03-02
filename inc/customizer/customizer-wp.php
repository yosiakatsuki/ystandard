<?php
/**
 * @package ystandard
 * @author yosiakatsuki
 * @license GPL-2.0+
 */
/**
 * WordPress標準のカスタマイザー項目を変更
 */
function ys_customizer_wp( $wp_customize ) {
	ys_customizer_delete_site_icon( $wp_customize );
	ys_customizer_add_partial_bloginfo( $wp_customize );
	ys_customizer_add_apple_touch_icon( $wp_customize );
	ys_customizer_add_logo( $wp_customize );
	ys_customizer_add_description( $wp_customize );
}

/**
 * WP標準の背景色と色を削除
 */
function ys_customizer_delete_site_icon( $wp_customize ) {

	$wp_customize->remove_setting('background_color');
	$wp_customize->remove_section('colors');
}

/**
 * ブログ名などをカスタマイザーショートカット対応させる
 */
function ys_customizer_add_partial_bloginfo( $wp_customize ) {
	$wp_customize->get_setting( 'blogname' )->transport = 'postMessage';
	$wp_customize->get_setting( 'blogdescription' )->transport = 'postMessage';

	if ( isset( $wp_customize->selective_refresh ) ) {
		$wp_customize->selective_refresh->add_partial(
			'blogname',
			 array(
				'selector'            => '.site-title a',
				'container_inclusive' => false,
				'render_callback'     => function() {
																		bloginfo( 'name' );
																	},
			)
		);
		$wp_customize->selective_refresh->add_partial(
			'blogdescription',
			array(
				'selector'            => '.site-description',
				'container_inclusive' => false,
				'render_callback'     => function() {
																	bloginfo( 'description' );
																},
			)
		);
	}
}

/**
 * ロゴ設定追加
 */
function ys_customizer_add_logo( $wp_customize ) {
	ys_customizer_add_setting_checkbox(
		$wp_customize,
		array(
			'id'          => 'ys_logo_hidden',
			'section'     => 'title_tagline',
			'label'       => 'ロゴを出力しない',
			'description' => 'サイトヘッダーにロゴ画像を表示しない場合はチェックをつけてください（サイトタイトルをテキストのみで表示する場合でもロゴの指定がないと構造化データでエラーになるので、仮の画像でも良いので設定することを推奨します）',
			'priority'    => 9
		)
	);
}

/**
 * 概要表示・デスクリプション
 */
function ys_customizer_add_description( $wp_customize ) {
	ys_customizer_add_setting_checkbox(
		$wp_customize,
		array(
			'id'          => 'ys_wp_hidden_blogdescription',
			'section'     => 'title_tagline',
			'label'       => 'キャッチフレーズを出力しない',
			'description' => '',
			'priority'    => 20
		)
	);
	ys_customizer_add_setting_plain_textarea(
		$wp_customize,
		array(
			'id'          => 'ys_wp_site_description',
			'section'     => 'title_tagline',
			'transport'   => 'postMessage',
			'label'       => 'TOPページのmeta description',
			'description' => '※HTMLタグ・改行は削除されます',
			'priority'    => 21
		)
	);
}


/**
 * apple touch icon設定追加
 */
function ys_customizer_add_apple_touch_icon( $wp_customize ) {

	// サイトアイコンの説明を変更
	$wp_customize->get_control('site_icon')->description = sprintf(
		'ファビコン用の画像を設定して下さい。縦横%spx以上である必要があります。',
		'<strong>512</strong>'
	);

	$wp_customize->add_setting( 'ys_apple_touch_icon', array(
		'type'       => 'option',
		'capability' => 'manage_options',
		'transport'  => 'refresh',
	) );

	$wp_customize->add_control( new WP_Customize_Site_Icon_Control( $wp_customize, 'ys_apple_touch_icon', array(
		'label'       => 'apple touch icon',
		'description' => sprintf(
			'apple touch icon用の画像を設定して下さい。縦横%spx以上である必要があります。',
			'<strong>512</strong>'
		),
		'section'     => 'title_tagline',
		'priority'    => 61,
		'height'      => 512,
		'width'       => 512,
	) ) );
}