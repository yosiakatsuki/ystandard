<?php
/**
 * AMP設定
 *
 * @package ystandard
 * @author yosiakatsuki
 * @license GPL-2.0+
 */

/**
 * AMP設定
 *
 * @param  WP_Customize_Manager $wp_customize wp_customize.
 */
function ys_customizer_amp( $wp_customize ) {
	/**
	 * パネルの追加
	 */
	$wp_customize->add_panel(
		'ys_customizer_panel_amp',
		array(
			'title'    => '[ys]AMP設定',
			'priority' => 1300,
		)
	);
	/**
	 * AMP有効化設定
	 */
	ys_customizer_amp_add_enable_option( $wp_customize );
	/**
	 * AMP機能設定
	 */
	ys_customizer_amp_add_amp_options( $wp_customize );
	/**
	 * AMP広告設定
	 */
	ys_customizer_amp_add_amp_ads( $wp_customize );
	/**
	 * AMPテンプレート設定
	 */
	ys_customizer_amp_add_template( $wp_customize );
}

/**
 * AMP設定変更パネルを表示するかどうか
 */
function ys_customizer_active_callback_amp_options() {
	return ys_get_option( 'ys_amp_enable' );
}


/**
 * AMP有効化設定
 *
 * @param  WP_Customize_Manager $wp_customize wp_customize.
 */
function ys_customizer_amp_add_enable_option( $wp_customize ) {
	$ys_customizer = new YS_Customizer( $wp_customize );
	/**
	 * セクション追加
	 */
	$ys_customizer->add_section( array(
		'section'  => 'ys_customizer_section_amp_enable',
		'title'    => 'AMP有効化設定',
		'priority' => 1,
		'panel'    => 'ys_customizer_panel_amp',
	) );
	/**
	* AMP機能を有効化する
	*/
	$ys_customizer->add_label( array(
		'id'          => 'ys_amp_enable_label',
		'label'       => 'AMP機能を有効化',
		'description' => '※AMPページの生成を保証するものではありません。<br>※使用しているプラグインや投稿内のHTMLタグ、インラインCSS、JavaScriptコードによりAMPフォーマットとしてエラーとなる場合もあります。',
	) );
	$ys_customizer->add_checkbox(
		array(
			'id'          => 'ys_amp_enable',
			'default'     => 0,
			'label'       => 'AMP機能を有効化する',
			'description' => '※設定を有効化したら一度ページを再読込して下さい。「AMP設定」に詳細設定項目が表示されます。',
		)
	);
}


/**
 * AMP機能設定
 *
 * @param  WP_Customize_Manager $wp_customize wp_customize.
 */
function ys_customizer_amp_add_amp_options( $wp_customize ) {
	$ys_customizer = new YS_Customizer( $wp_customize );
	/**
	 * セクション追加
	 */
	$ys_customizer->add_section( array(
		'section'         => 'ys_customizer_section_amp_options',
		'title'           => 'AMP機能設定',
		'priority'        => 1,
		'panel'           => 'ys_customizer_panel_amp',
		'active_callback' => 'ys_customizer_active_callback_amp_options',
	) );
	/**
	 * AMP用 Google Analytics トラッキングID
	 */
	$ys_customizer->add_text(
		array(
			'id'          => 'ys_ga_tracking_id_amp',
			'default'     => '',
			'transport'   => 'postMessage',
			'label'       => 'Google Analytics トラッキングID(AMP)',
			'input_attrs' => array(
				'placeholder' => 'UA-00000000-0',
			),
		)
	);
}
/**
 * AMP広告設定
 *
 * @param  WP_Customize_Manager $wp_customize wp_customize.
 */
function ys_customizer_amp_add_amp_ads( $wp_customize ) {
	$ys_customizer = new YS_Customizer( $wp_customize );
	/**
	 * セクション追加
	 */
	$ys_customizer->add_section( array(
		'section'         => 'ys_customizer_section_amp_ads',
		'title'           => 'AMP広告設定',
		'priority'        => 1,
		'panel'           => 'ys_customizer_panel_amp',
		'active_callback' => 'ys_customizer_active_callback_amp_options',
	) );
	/**
	 * 記事本文上
	 * 旧ys_amp_advertisement_under_title
	 */
	$ys_customizer->add_textarea(
		array(
			'id'        => 'ys_amp_advertisement_before_content',
			'default'   => '',
			'transport' => 'postMessage',
			'label'     => '記事本文上',
		)
	);
	/**
	 * Moreタグ部分
	 */
	$ys_customizer->add_textarea(
		array(
			'id'        => 'ys_amp_advertisement_replace_more',
			'default'   => '',
			'transport' => 'postMessage',
			'label'     => 'moreタグ部分',
		)
	);
	/**
	 * 記事下
	 */
	$ys_customizer->add_textarea(
		array(
			'id'        => 'ys_amp_advertisement_under_content',
			'default'   => '',
			'transport' => 'postMessage',
			'label'     => '記事本文下',
		)
	);
}

/**
 * AMPテンプレート設定
 *
 * @param  WP_Customize_Manager $wp_customize wp_customize.
 */
function ys_customizer_amp_add_template( $wp_customize ) {
	$ys_customizer = new YS_Customizer( $wp_customize );
	/**
	 * セクション追加
	 */
	$ys_customizer->add_section( array(
		'section'         => 'ys_customizer_section_amp_template',
		'title'           => 'AMPテンプレート設定',
		'priority'        => 1,
		'description'     => 'AMPテンプレートの設定',
		'panel'           => 'ys_customizer_panel_amp',
		'active_callback' => 'ys_customizer_active_callback_amp_options',
	) );
	/**
	 * ヘッダータイプ
	 */
	$assets_url = ys_get_template_customizer_assets_img_dir_uri();
	$row1       = $assets_url . '/design/one-col-template/full.png';
	$center     = $assets_url . '/design/one-col-template/normal.png';
	$img        = '<img src="%s" alt="" width="100" height="100" />';
	$ys_customizer->add_image_label_radio(
		array(
			'id'          => 'ys_amp_thumbnail_type',
			'default'     => 'full',
			'transport'   => 'postMessage',
			'label'       => 'アイキャッチ画像表示タイプ',
			'description' => 'アイキャッチ画像の表示タイプ',
			'choices'     => array(
				'full'   => sprintf( $img, $row1 ),
				'normal' => sprintf( $img, $center ),
			),
		)
	);
}
