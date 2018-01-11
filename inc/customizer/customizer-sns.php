<?php
/**
 *
 *	sns設定
 *
 */
function ys_customizer_sns( $wp_customize ){
	/**
	 * パネルの追加
	 */
	$wp_customize->add_panel(
										'ys_customizer_panel_sns',
										array(
											'priority'       => 1100,
											'title'          => '[ys]SNS設定'
										)
									);
	/**
	 * OGP設定
	 */
	ys_customizer_sns_add_ogp( $wp_customize );
	/**
	 * Twitter Cards
	 */
	ys_customizer_sns_add_twitter_cards( $wp_customize );
}

/**
 * OGP設定
 */
function ys_customizer_sns_add_ogp( $wp_customize ) {
	/**
	 * セクション追加
	 */
	$wp_customize->add_section(
										'ys_customizer_section_ogp',
										array(
											'title'    => 'OGP設定',
											'panel'    => 'ys_customizer_panel_sns',
											'priority' => 1,
										)
									);
	/**
	 * OGP metaタグを出力する
	 */
	ys_customizer_add_label(
		$wp_customize,
		array(
			'id'          => 'ys_ogp_enable_label',
			'label'       => 'OGP metaタグ',
			'section'     => 'ys_customizer_section_ogp',
			'description' => '',
		)
	);
	ys_customizer_add_setting_checkbox(
		$wp_customize,
		array(
			'id'        => 'ys_ogp_enable',
			'label'     => 'OGPのmetaタグを出力する',
			'section'   => 'ys_customizer_section_ogp',
			'transport' => 'postMessage',
		)
	);
	/**
	 * Facebook app_id
	 */
	ys_customizer_add_setting_text(
		$wp_customize,
		array(
			'id'        => 'ys_ogp_fb_app_id',
			'default'   => '',
			'label'     => 'Facebook app_id',
			'section'   => 'ys_customizer_section_ogp',
			'transport' => 'postMessage',
			'input_attrs' => array(
												'placeholder' => '000000000000000',
												'maxlength' => 15
											)
		)
	);
	/**
	 * Facebook app_id
	 */
	ys_customizer_add_setting_text(
		$wp_customize,
		array(
			'id'        => 'ys_ogp_fb_admins',
			'default'   => '',
			'label'     => 'Facebook admins',
			'section'   => 'ys_customizer_section_ogp',
			'transport' => 'postMessage',
			'input_attrs' => array(
												'placeholder' => '000000000000000',
												'maxlength' => 15
											)
		)
	);
	/**
	 * OGPデフォルト画像
	 */
	ys_customizer_add_setting_image(
		$wp_customize,
		array(
			'id'        => 'ys_ogp_default_image',
			'default'   => '',
			'label'     => 'OGPデフォルト画像',
			'description'  => 'トップページ・アーカイブページ・投稿にアイキャッチ画像が無かった場合のデフォルト画像を指定して下さい。おすすめサイズ：横1200px - 縦630px ',
			'section'   => 'ys_customizer_section_ogp',
			'transport' => 'postMessage',
		)
	);
}

/**
 * Twitter Cards
 */
function ys_customizer_sns_add_twitter_cards( $wp_customize ) {
	/**
	 * セクション追加
	 */
	$wp_customize->add_section(
										'ys_customizer_section_twitter_cards',
										array(
											'title'    => 'Twitterカード設定',
											'panel'    => 'ys_customizer_panel_sns',
											'priority' => 1,
										)
									);
	/**
	 * Twitterカードのmetaタグを出力する
	 */
	ys_customizer_add_label(
		$wp_customize,
		array(
			'id'          => 'ys_twittercard_enable_label',
			'label'       => 'Twitterカードmetaタグ',
			'section'     => 'ys_customizer_section_twitter_cards',
			'description' => '',
		)
	);
	ys_customizer_add_setting_checkbox(
		$wp_customize,
		array(
			'id'        => 'ys_twittercard_enable',
			'label'     => 'Twitterカードのmetaタグを出力する',
			'default'   => 1,
			'section'   => 'ys_customizer_section_twitter_cards',
			'transport' => 'postMessage',
		)
	);
	/**
	 * ユーザー名
	 */
	ys_customizer_add_setting_text(
		$wp_customize,
		array(
			'id'        => 'ys_twittercard_user',
			'default'   => '',
			'label'     => 'Twitterカードのユーザー名',
			'section'   => 'ys_customizer_section_twitter_cards',
			'description'  => '「@」なしのTwitterユーザー名を入力して下さい。例：Twitterユーザー名…「@yosiakatsuki」→入力…「yosiakatsuki」',
			'transport' => 'postMessage',
			'input_attrs' => array(
												'placeholder' => 'username',
											)
		)
	);
	/**
	 * カードタイプ
	 */
	ys_customizer_add_setting_radio(
		$wp_customize,
		array(
			'id'        => 'ys_twittercard_type',
			'default'   => 'summary_large_image',
			'label'     => 'カードタイプ',
			'section'   => 'ys_customizer_section_twitter_cards',
			'transport' => 'postMessage',
			'choices' => array(
				'summary_large_image' => 'Summary Card with Large Image',
				'summary' => 'Summary Card'
			)
		)
	);
}