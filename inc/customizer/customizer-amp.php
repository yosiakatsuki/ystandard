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
											'priority'       => 1110,
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
										)
									);
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