<?php
/**
 *
 *	AMP設定
 *
 */
function ys_customizer_amp( $wp_customize ){
	/**
	 * パネルの追加
	 */
	$wp_customize->add_panel(
										'ys_customizer_panel_amp',
										array(
											'priority'       => 1300,
											'title'          => '[ys]AMP設定'
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
}

/**
 * AMP設定変更パネルを表示するかどうか
 */
function ys_customizer_active_callback_amp_options() {
	return ys_get_option( 'ys_amp_enable' );
}


/**
 * AMP有効化設定
 */
function ys_customizer_amp_add_enable_option( $wp_customize ) {
	/**
	 * セクション追加
	 */
	$wp_customize->add_section(
										'ys_customizer_section_amp_enable',
										array(
											'title'    => 'AMP有効化設定',
											'panel'    => 'ys_customizer_panel_amp',
											'priority' => 1,
										)
									);
	ys_customizer_add_label(
		$wp_customize,
		array(
			'id'        => 'ys_amp_enable_label',
			'label'       => 'AMP機能を有効化',
			'section'     => 'ys_customizer_section_amp_enable',
			'description' => '※AMPページの生成を保証するものではありません。使用しているプラグインや投稿内のHTMLタグ、インラインCSS、javascriptコードによりAMPフォーマットとしてエラーとなる場合もあります。',
		)
	);
	/**
	 * AMP機能を有効化する
	 */
	ys_customizer_add_setting_checkbox(
		$wp_customize,
		array(
			'id'        => 'ys_amp_enable',
			'label'     => 'AMP機能を有効化する',
			'description' => '',
			'default'   => 0,
			'section'   => 'ys_customizer_section_amp_enable',
			'transport' => 'postMessage',
		)
	);
}


/**
 * AMP機能設定
 */
function ys_customizer_amp_add_amp_options( $wp_customize ) {
	/**
	 * セクション追加
	 */
	$wp_customize->add_section(
										'ys_customizer_section_amp_options',
										array(
											'title'    => 'AMP機能設定',
											'panel'    => 'ys_customizer_panel_amp',
											'priority' => 1,
											'active_callback' => 'ys_customizer_active_callback_amp_options'
										)
									);
	/**
	 * AMP用 Google Analytics トラッキングID
	 */
	ys_customizer_add_setting_text(
		$wp_customize,
		array(
			'id'        => 'ys_ga_tracking_id_amp',
			'default'   => '',
			'label'     => 'Google Analytics トラッキングID(AMP)',
			'section'   => 'ys_customizer_section_amp_options',
			'transport' => 'postMessage',
			'input_attrs' => array(
												'placeholder' => 'UA-00000000-0'
											)
		)
	);
}
/**
 * AMP広告設定
 */
function ys_customizer_amp_add_amp_ads( $wp_customize ) {
	/**
	 * セクション追加
	 */
	$wp_customize->add_section(
										'ys_customizer_section_amp_ads',
										array(
											'title'    => 'AMP広告設定',
											'panel'    => 'ys_customizer_panel_amp',
											'priority' => 1,
											'active_callback' => 'ys_customizer_active_callback_amp_options'
										)
									);
	/**
	 * 記事タイトル下
	 */
	ys_customizer_add_setting_textarea(
		$wp_customize,
		array(
			'id'        => 'ys_amp_advertisement_under_title',
			'default'   => '',
			'label'     => '記事タイトル下',
			'description' => '',
			'section'   => 'ys_customizer_section_amp_ads',
			'transport' => 'postMessage',
		)
	);
	/**
	 * moreタグ部分
	 */
	ys_customizer_add_setting_textarea(
		$wp_customize,
		array(
			'id'        => 'ys_amp_advertisement_replace_more',
			'default'   => '',
			'label'     => 'moreタグ部分',
			'description' => '',
			'section'   => 'ys_customizer_section_amp_ads',
			'transport' => 'postMessage',
		)
	);
	/**
	 * 記事下
	 */
	ys_customizer_add_setting_textarea(
		$wp_customize,
		array(
			'id'        => 'ys_amp_advertisement_under_content',
			'default'   => '',
			'label'     => '記事本文下',
			'description' => '',
			'section'   => 'ys_customizer_section_amp_ads',
			'transport' => 'postMessage',
		)
	);
}