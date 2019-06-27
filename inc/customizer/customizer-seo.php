<?php
/**
 * SEO設定
 *
 * @package ystandard
 * @author  yosiakatsuki
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
			'title'    => '[ys]SEO設定',
			'priority' => 1110,
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
	$ys_customizer = new YS_Customizer( $wp_customize );
	/**
	 * セクション追加
	 */
	$ys_customizer->add_section(
		array(
			'section'  => 'ys_customizer_section_meta_description',
			'title'    => 'meta description設定',
			'priority' => 1,
			'panel'    => 'ys_customizer_panel_seo',
		)
	);
	/**
	 * SEO : meta descriptionを自動生成する
	 */
	$ys_customizer->add_checkbox(
		array(
			'id'        => 'ys_option_create_meta_description',
			'default'   => ys_get_option_default( 'ys_option_create_meta_description' ),
			'transport' => 'postMessage',
			'label'     => 'meta descriptionを自動生成する',
		)
	);
	/**
	 * 抜粋文字数
	 */
	$ys_customizer->add_number(
		array(
			'id'        => 'ys_option_meta_description_length',
			'default'   => 80,
			'transport' => 'postMessage',
			'label'     => 'meta descriptionに使用する文字数',
		)
	);
}

/**
 * アーカイブページのnoindex設定
 *
 * @param  WP_Customize_Manager $wp_customize wp_customize.
 */
function ys_customizer_seo_add_noindex( $wp_customize ) {
	$ys_customizer = new YS_Customizer( $wp_customize );
	/**
	 * セクション追加
	 */
	$ys_customizer->add_section(
		array(
			'section'  => 'ys_customizer_section_noindex',
			'title'    => 'アーカイブページのnoindex設定',
			'priority' => 1,
			'panel'    => 'ys_customizer_panel_seo',
		)
	);
	/**
	 * カテゴリー一覧をnoindexにする
	 */
	$ys_customizer->add_checkbox(
		array(
			'id'        => 'ys_archive_noindex_category',
			'default'   => 0,
			'transport' => 'postMessage',
			'label'     => 'カテゴリー一覧をnoindexにする',
		)
	);
	/**
	 * タグ一覧をnoindexにする
	 */
	$ys_customizer->add_checkbox(
		array(
			'id'        => 'ys_archive_noindex_tag',
			'default'   => 1,
			'transport' => 'postMessage',
			'label'     => 'タグ一覧をnoindexにする',
		)
	);
	/**
	 * 投稿者一覧をnoindexにする
	 */
	$ys_customizer->add_checkbox(
		array(
			'id'        => 'ys_archive_noindex_author',
			'default'   => 1,
			'transport' => 'postMessage',
			'label'     => '投稿者一覧をnoindexにする',
		)
	);
	/**
	 * 日別一覧をnoindexにする
	 */
	$ys_customizer->add_checkbox(
		array(
			'id'        => 'ys_archive_noindex_date',
			'default'   => 1,
			'transport' => 'postMessage',
			'label'     => '日別一覧をnoindexにする',
		)
	);
}

/**
 * Google Analytics設定
 *
 * @param  WP_Customize_Manager $wp_customize wp_customize.
 */
function ys_customizer_seo_add_google_analytics( $wp_customize ) {
	$ys_customizer = new YS_Customizer( $wp_customize );
	/**
	 * セクション追加
	 */
	$ys_customizer->add_section(
		array(
			'section'  => 'ys_customizer_section_google_analytics',
			'title'    => 'Google Analytics設定',
			'priority' => 1,
			'panel'    => 'ys_customizer_panel_seo',
		)
	);
	/**
	 * Google Analytics トラッキングID
	 */
	$ys_customizer->add_text(
		array(
			'id'          => 'ys_ga_tracking_id',
			'default'     => '',
			'transport'   => 'postMessage',
			'label'       => 'Google Analytics トラッキングID',
			'input_attrs' => array(
				'placeholder' => 'UA-00000000-0',
			),
		)
	);
	/**
	 * トラッキングコードタイプ
	 */
	$ys_customizer->add_radio(
		array(
			'id'          => 'ys_ga_tracking_type',
			'default'     => 'gtag',
			'transport'   => 'postMessage',
			'label'       => 'トラッキングコードタイプ',
			'description' => 'Google Analytics トラッキングコードタイプを選択出来ます。※デフォルトはグローバル サイトタグ(gtag.js)です。',
			'choices'     => array(
				'gtag'      => 'グローバル サイトタグ(gtag.js)',
				'analytics' => 'ユニバーサルアナリティクス(analytics.js)',
			),
		)
	);
	/**
	 * ログイン中はアクセス数をカウントしない
	 */
	$ys_customizer->add_checkbox(
		array(
			'id'          => 'ys_ga_exclude_logged_in_user',
			'default'     => 0,
			'transport'   => 'postMessage',
			'label'       => '管理画面ログイン中はアクセス数カウントを無効にする（「購読者」ユーザーを除く）',
			'description' => 'チェックを付けた場合、ログインユーザーのアクセスではGoogle Analyticsのトラッキングコードを出力しません',
		)
	);
}

/**
 * 構造化データ 設定
 *
 * @param  WP_Customize_Manager $wp_customize wp_customize.
 */
function ys_customizer_seo_add_structured_data( $wp_customize ) {
	$ys_customizer = new YS_Customizer( $wp_customize );
	/**
	 * セクション追加
	 */
	$ys_customizer->add_section(
		array(
			'section'  => 'ys_customizer_section_structured_data',
			'title'    => '構造化データ 設定',
			'priority' => 1,
			'panel'    => 'ys_customizer_panel_seo',
		)
	);
	/**
	 * Publisher画像
	 */
	$ys_customizer->add_image(
		array(
			'id'          => 'ys_option_structured_data_publisher_image',
			'default'     => '',
			'transport'   => 'postMessage',
			'label'       => 'Publisher Logo',
			'description' => '構造化データのPublisherに使用する画像です。サイトの顔になるような画像を設定すると良いかと思います。 推奨サイズ:横600px以下,縦60px以下',
		)
	);
	/**
	 * Publisher名
	 */
	$ys_customizer->add_text(
		array(
			'id'          => 'ys_option_structured_data_publisher_name',
			'default'     => '',
			'transport'   => 'postMessage',
			'label'       => 'Publisher Name',
			'description' => '構造化データのPublisherに使用する名前です。空白の場合はサイトタイトルを使用します',
		)
	);
}
