<?php
/**
 *
 *	SEO設定
 *
 */
function ys_customizer_sample( $wp_customize ){
	/**
	 * パネルの追加
	 */
	$wp_customize->add_panel(
										'ys_customizer_panel_seo',
										array(
											'priority'       => 1110,
											'title'          => '[ys]SEO設定'
										)
									);
	/**
	 * Google Analytics設定
	 */
	ys_customizer_seo_add_google_analytics( $wp_customize );
}

/**
 * Google Analytics設定
 */
function ys_customizer_seo_add_google_analytics( $wp_customize ) {
	/**
	 * セクション追加
	 */
	$wp_customize->add_section(
										'ys_customizer_section_google_analytics',
										array(
											'title'    => 'Google Analytics設定',
											'panel'    => 'ys_customizer_panel_seo',
											'priority' => 1,
										)
									);
	/**
	 * Google Analytics トラッキングID
	 */
	ys_customizer_add_setting_text(
		$wp_customize,
		array(
			'id'        => 'ys_ga_tracking_id',
			'default'   => '',
			'label'     => 'Google Analytics トラッキングID',
			'section'   => 'ys_customizer_section_google_analytics',
			'transport' => 'postMessage',
			'input_attrs' => array(
												'placeholder' => 'UA-00000000-0'
											)
		)
	);
	/**
	 * トラッキングコードタイプ
	 */
	ys_customizer_add_setting_radio(
		$wp_customize,
		array(
			'id'        => 'ys_ga_tracking_type',
			'default'   => 'gtag',
			'label'     => 'トラッキングコードタイプ',
			'description'     => 'Google Analytics トラッキングコードタイプを選択出来ます。※デフォルトはグローバル サイトタグ(gtag.js)です。',
			'section'   => 'ys_customizer_section_google_analytics',
			'transport' => 'postMessage',
			'choices' => array(
				'gtag' => 'グローバル サイトタグ(gtag.js)',
				'analytics' => 'ユニバーサルアナリティクス(analytics.js)'
			)
		)
	);
	/**
	 * ログイン中はアクセス数をカウントしない
	 */
	ys_customizer_add_setting_checkbox(
		$wp_customize,
		array(
			'id'        => 'ys_ga_exclude_logged_in_user',
			'label'       => '管理画面ログイン中はアクセス数をカウントしない（「購読者」ユーザーを除く）',
			'default'   => 0,
			'section'   => 'ys_customizer_section_google_analytics',
			'transport' => 'postMessage',
		)
	);
}