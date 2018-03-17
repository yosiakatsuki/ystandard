<?php
/**
 * SEO設定
 *
 * @package ystandard
 * @author yosiakatsuki
 * @license GPL-2.0+
 */

/**
 * SEO設定
 *
 * @param  WP_Customize_Manager $wp_customize wp_customize.
 */
function ys_customizer_seo( $wp_customize ) {
	/**
	 * パネルの追加
	 */
	$wp_customize->add_panel(
		'ys_customizer_panel_seo',
		array(
			'priority' => 1110,
			'title'    => '[ys]SEO設定',
		)
	);
	/**
	 * メタデスクリプションの作成
	 */
	ys_customizer_seo_add_meta_description( $wp_customize );
	/**
	 * アーカイブページのnoindex設定
	 */
	ys_customizer_seo_add_noindex( $wp_customize );
	/**
	 * Google Analytics設定
	 */
	ys_customizer_seo_add_google_analytics( $wp_customize );
	/**
	 * 構造化データ設定
	 */
	ys_customizer_seo_add_structured_data( $wp_customize );
}

/**
 * メタデスクリプションの作成
 *
 * @param  WP_Customize_Manager $wp_customize wp_customize.
 */
function ys_customizer_seo_add_meta_description( $wp_customize ) {
	/**
	 * セクション追加
	 */
	$wp_customize->add_section(
		'ys_customizer_section_meta_description',
		array(
			'title'    => 'meta description設定',
			'panel'    => 'ys_customizer_panel_seo',
			'priority' => 1,
		)
	);
	/**
	 * SEO : meta descriptionを自動生成する
	 */
	ys_customizer_add_setting_checkbox(
		$wp_customize,
		array(
			'id'        => 'ys_option_create_meta_description',
			'label'     => 'meta descriptionを自動生成する',
			'default'   => 1,
			'section'   => 'ys_customizer_section_meta_description',
			'transport' => 'postMessage',
		)
	);
	/**
	 * 抜粋文字数
	 */
	ys_customizer_add_setting_number(
		$wp_customize,
		array(
			'id'        => 'ys_option_meta_description_length',
			'default'   => 80,
			'label'     => 'meta descriptionに使用する文字数',
			'section'   => 'ys_customizer_section_meta_description',
			'transport' => 'postMessage',
		)
	);
}
/**
 * アーカイブページのnoindex設定
 *
 * @param  WP_Customize_Manager $wp_customize wp_customize.
 */
function ys_customizer_seo_add_noindex( $wp_customize ) {
	/**
	 * セクション追加
	 */
	$wp_customize->add_section(
		'ys_customizer_section_noindex',
		array(
			'title'    => 'アーカイブページのnoindex設定',
			'panel'    => 'ys_customizer_panel_seo',
			'priority' => 1,
		)
	);
	/**
	 * カテゴリー一覧をnoindexにする
	 */
	ys_customizer_add_setting_checkbox(
		$wp_customize,
		array(
			'id'        => 'ys_archive_noindex_category',
			'label'     => 'カテゴリー一覧をnoindexにする',
			'default'   => 0,
			'section'   => 'ys_customizer_section_noindex',
			'transport' => 'postMessage',
		)
	);
	/**
	 * タグ一覧をnoindexにする
	 */
	ys_customizer_add_setting_checkbox(
		$wp_customize,
		array(
			'id'        => 'ys_archive_noindex_tag',
			'label'     => 'タグ一覧をnoindexにする',
			'default'   => 1,
			'section'   => 'ys_customizer_section_noindex',
			'transport' => 'postMessage',
		)
	);
	/**
	 * 投稿者一覧をnoindexにする
	 */
	ys_customizer_add_setting_checkbox(
		$wp_customize,
		array(
			'id'        => 'ys_archive_noindex_author',
			'label'     => '投稿者一覧をnoindexにする',
			'default'   => 1,
			'section'   => 'ys_customizer_section_noindex',
			'transport' => 'postMessage',
		)
	);
	/**
	 * 日別一覧をnoindexにする
	 */
	ys_customizer_add_setting_checkbox(
		$wp_customize,
		array(
			'id'        => 'ys_archive_noindex_date',
			'label'     => '日別一覧をnoindexにする',
			'default'   => 1,
			'section'   => 'ys_customizer_section_noindex',
			'transport' => 'postMessage',
		)
	);
}
/**
 * Google Analytics設定
 *
 * @param  WP_Customize_Manager $wp_customize wp_customize.
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
			'id'          => 'ys_ga_tracking_id',
			'default'     => '',
			'label'       => 'Google Analytics トラッキングID',
			'section'     => 'ys_customizer_section_google_analytics',
			'transport'   => 'postMessage',
			'input_attrs' => array(
				'placeholder' => 'UA-00000000-0',
			),
		)
	);
	/**
	 * トラッキングコードタイプ
	 */
	ys_customizer_add_setting_radio(
		$wp_customize,
		array(
			'id'          => 'ys_ga_tracking_type',
			'default'     => 'gtag',
			'label'       => 'トラッキングコードタイプ',
			'description' => 'Google Analytics トラッキングコードタイプを選択出来ます。※デフォルトはグローバル サイトタグ(gtag.js)です。',
			'section'     => 'ys_customizer_section_google_analytics',
			'transport'   => 'postMessage',
			'choices'     => array(
				'gtag'      => 'グローバル サイトタグ(gtag.js)',
				'analytics' => 'ユニバーサルアナリティクス(analytics.js)',
			),
		)
	);
	/**
	 * ログイン中はアクセス数をカウントしない
	 */
	ys_customizer_add_setting_checkbox(
		$wp_customize,
		array(
			'id'          => 'ys_ga_exclude_logged_in_user',
			'label'       => '管理画面ログイン中はアクセス数カウントを無効にする（「購読者」ユーザーを除く）',
			'description' => 'チェックを付けた場合、ログインユーザーのアクセスではGoogle Analyticsのトラッキングコードを出力しません',
			'default'     => 0,
			'section'     => 'ys_customizer_section_google_analytics',
			'transport'   => 'postMessage',
		)
	);
}

/**
 * 構造化データ 設定
 *
 * @param  WP_Customize_Manager $wp_customize wp_customize.
 */
function ys_customizer_seo_add_structured_data( $wp_customize ) {
	/**
	 * セクション追加
	 */
	$wp_customize->add_section(
		'ys_customizer_section_structured_data',
		array(
			'title'    => '構造化データ 設定',
			'panel'    => 'ys_customizer_panel_seo',
			'priority' => 1,
		)
	);
	/**
	 * Publisher画像
	 */
	ys_customizer_add_setting_image(
		$wp_customize,
		array(
			'id'          => 'ys_option_structured_data_publisher_image',
			'default'     => '',
			'label'       => 'Publisher Logo',
			'description' => '構造化データのPublisherに使用する画像です。サイトの顔になるような画像を設定すると良いかと思います。 推奨サイズ:縦60px以下,横600px以下 ',
			'section'     => 'ys_customizer_section_structured_data',
			'transport'   => 'postMessage',
		)
	);
	/**
	 * Publisher名
	 */
	ys_customizer_add_setting_text(
		$wp_customize,
		array(
			'id'          => 'ys_option_structured_data_publisher_name',
			'default'     => '',
			'label'       => 'Publisher Name',
			'description' => '構造化データのPublisherに使用する名前です。空白の場合はサイトタイトルを使用します',
			'section'     => 'ys_customizer_section_structured_data',
			'transport'   => 'postMessage',
		)
	);
}