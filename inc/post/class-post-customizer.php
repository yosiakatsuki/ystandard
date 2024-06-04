<?php
/**
 * 投稿・固定ページ・投稿タイプ関連のカスタマイザー設定
 *
 * @package ystandard
 * @author  yosiakatsuki
 * @license GPL-2.0+
 */

namespace ystandard;

use ystandard\utils\Convert;

defined( 'ABSPATH' ) || die();

/**
 * Class Post_Customizer
 */
class Post_Customizer {

	/**
	 * Post_Types_Customizer constructor.
	 */
	public function __construct() {
		add_action( 'customize_register', [ $this, 'customize_register_post' ] );
		add_action( 'customize_register', [ $this, 'customize_register_page' ] );
	}

	/**
	 * 投稿設定
	 *
	 * @param \WP_Customize_Manager $wp_customize カスタマイザー.
	 */
	public function customize_register_post( $wp_customize ) {
		$customizer = new Customize_Control( $wp_customize );
		// 投稿タイプ
		$post_type = 'post';

		$customizer->add_section(
			[
				'section'     => 'ys_design_post',
				'title'       => '投稿ページ',
				'priority'    => 100,
				'description' => Admin::manual_link( 'manual/post-layout' ),
				'panel'       => Design::PANEL_NAME,
			]
		);
		$customizer->add_section_label( 'レイアウト' );
		// 表示カラム数.
		$col1 = Customizer::get_assets_dir_uri( '/design/column-type/col-1.png' );
		$col2 = Customizer::get_assets_dir_uri( '/design/column-type/col-2.png' );
		$img  = '<img src="%s" alt="" width="100" height="100" />';
		$customizer->add_image_label_radio(
			[
				'id'          => 'ys_post_layout',
				'default'     => '1col',
				'label'       => 'ページレイアウト',
				'description' => '投稿ページの表示レイアウト',
				'choices'     => [
					'1col' => sprintf( $img, $col1 ),
					'2col' => sprintf( $img, $col2 ),
				],
			]
		);
		// アイキャッチ.
		$default = Customizer::get_assets_dir_uri( '/design/eye-catch/default.png' );
		$full    = Customizer::get_assets_dir_uri( '/design/eye-catch/full.png' );
		$img     = '<img src="%s" alt="" width="100" height="100" />';
		$customizer->add_image_label_radio(
			[
				'id'      => 'ys_post_post_thumbnail_type',
				'default' => 'default',
				'label'   => 'アイキャッチ画像の表示タイプ',
				'choices' => [
					'default' => sprintf( $img, $default ),
					'full'    => sprintf( $img, $full ),
				],
			]
		);
		// コンテンツ域 背景色.
		$customizer->add_section_label( '本文エリア背景色' );
		$customizer->add_checkbox(
			[
				'id'          => "ys_{$post_type}_use_content_bg",
				'default'     => 0,
				'label'       => '本文エリア背景色を使用する',
				'description' => '投稿本文エリアの背景色設定を有効にします。',
			]
		);
		$customizer->add_color(
			[
				'id'              => "ys_{$post_type}_content_bg",
				'default'         => '',
				'label'           => '本文エリア背景色',
				'description'     => '投稿本文エリアの背景色設定。<br><span class="tw-text-ys-customizer-small">※投稿本文の背景色を設定した場合、全幅設定をしたブロックはデスクトップサイズの表示時に画面の横幅いっぱいに広がらず、コンテンツ本文領域が上限となります。</span>',
				'active_callback' => function () use ( $customizer, $post_type ) {
					return Convert::to_bool( get_option( "ys_{$post_type}_use_content_bg", 0 ) );
				},
			]
		);

		$customizer->add_section_label( '記事上部' );
		// アイキャッチの表示.
		$customizer->add_label(
			[
				'id'    => 'ys_show_post_header_thumbnail_label',
				'label' => 'アイキャッチ画像の表示設定',
			]
		);
		$customizer->add_checkbox(
			[
				'id'      => 'ys_show_post_header_thumbnail',
				'default' => 1,
				'label'   => 'アイキャッチ画像を表示する',
			]
		);
		// 投稿日時を表示する.
		$customizer->add_select(
			[
				'id'      => 'ys_show_post_publish_date',
				'default' => 'both',
				'label'   => '投稿日・更新日の表示タイプ',
				'choices' => [
					'both'    => '投稿日・更新日',
					'publish' => '投稿日のみ',
					'update'  => '更新日のみ',
					'none'    => '表示しない',
				],
			]
		);
		$customizer->add_label(
			[
				'id'    => 'ys_show_post_header_category_label',
				'label' => 'カテゴリー情報の表示設定',
			]
		);
		// カテゴリー表示.
		$customizer->add_checkbox(
			[
				'id'          => 'ys_show_post_header_category',
				'default'     => 1,
				'label'       => 'カテゴリー情報を表示する',
				'description' => '※ページ上部では1件のみ表示されます',
			]
		);
		$customizer->add_section_label( '記事下部' );
		$customizer->add_label(
			[
				'id'          => 'ys_post_after_contents_section_label',
				'label'       => '記事下コンテンツの表示・非表示設定',
				'description' => '※シェアボタンの表示は「[ys]SNS」→「SNSシェアボタン」から設定できます',
			]
		);
		// カテゴリー・タグ.
		$customizer->add_checkbox(
			[
				'id'      => 'ys_show_post_category',
				'default' => 1,
				'label'   => 'カテゴリー・タグ',
			]
		);
		// 著者情報を表示する.
		$customizer->add_checkbox(
			[
				'id'      => 'ys_show_post_author',
				'default' => 1,
				'label'   => '著者情報',
			]
		);
		// 関連記事.
		$customizer->add_checkbox(
			[
				'id'      => 'ys_show_post_related',
				'default' => 1,
				'label'   => '関連記事',
			]
		);
		// 次の記事・前の記事.
		$customizer->add_checkbox(
			[
				'id'      => 'ys_show_post_paging',
				'default' => 1,
				'label'   => '次の記事・前の記事',
			]
		);
	}

	/**
	 * 固定ページ設定
	 *
	 * @param \WP_Customize_Manager $wp_customize カスタマイザー.
	 */
	public function customize_register_page( $wp_customize ) {
		$customizer = new Customize_Control( $wp_customize );

		// 投稿タイプ
		$post_type = 'page';

		$customizer->add_section(
			[
				'section'     => "ys_design_{$post_type}",
				'title'       => '固定ページ',
				'priority'    => 110,
				'description' => Admin::manual_link( 'manual/page-layout' ),
				'panel'       => Design::PANEL_NAME,
			]
		);
		$customizer->add_section_label( 'レイアウト' );
		// 表示カラム数.
		$col1 = Customizer::get_assets_dir_uri( '/design/column-type/col-1.png' );
		$col2 = Customizer::get_assets_dir_uri( '/design/column-type/col-2.png' );
		$img  = '<img src="%s" alt="" width="100" height="100" />';
		$customizer->add_image_label_radio(
			[
				'id'          => "ys_{$post_type}_layout",
				'default'     => '1col',
				'label'       => 'ページレイアウト',
				'description' => '固定ページの表示レイアウト',
				'choices'     => [
					'1col' => sprintf( $img, $col1 ),
					'2col' => sprintf( $img, $col2 ),
				],
			]
		);
		// アイキャッチ.
		$default = Customizer::get_assets_dir_uri( '/design/eye-catch/default.png' );
		$full    = Customizer::get_assets_dir_uri( '/design/eye-catch/full.png' );
		$img     = '<img src="%s" alt="" width="100" height="100" />';
		$customizer->add_image_label_radio(
			[
				'id'      => "ys_{$post_type}_post_thumbnail_type",
				'default' => 'default',
				'label'   => 'アイキャッチ画像の表示タイプ',
				'choices' => [
					'default' => sprintf( $img, $default ),
					'full'    => sprintf( $img, $full ),
				],
			]
		);
		// コンテンツ域 背景色.
		$customizer->add_section_label( '本文エリア背景色' );
		$customizer->add_checkbox(
			[
				'id'          => "ys_{$post_type}_use_content_bg",
				'default'     => 0,
				'label'       => '本文エリア背景色を使用する',
				'description' => '投稿本文エリアの背景色設定を有効にします。',
			]
		);
		$customizer->add_color(
			[
				'id'              => "ys_{$post_type}_content_bg",
				'default'         => '',
				'label'           => '本文エリア背景色',
				'description'     => '投稿本文エリアの背景色設定。<br><span class="tw-text-ys-customizer-small">※投稿本文の背景色を設定した場合、全幅設定をしたブロックはデスクトップサイズの表示時に画面の横幅いっぱいに広がらず、コンテンツ本文領域が上限となります。</span>',
				'active_callback' => function () use ( $customizer, $post_type ) {
					return Convert::to_bool( get_option( "ys_{$post_type}_use_content_bg", 0 ) );
				},
			]
		);
		$customizer->add_section_label( '記事上部' );
		// アイキャッチの表示.
		$customizer->add_label(
			[
				'id'    => 'ys_show_page_header_thumbnail_label',
				'label' => 'アイキャッチ画像の表示設定',
			]
		);
		$customizer->add_checkbox(
			[
				'id'      => 'ys_show_page_header_thumbnail',
				'default' => 1,
				'label'   => 'アイキャッチ画像を表示する',
			]
		);
		// 投稿日時を表示する.
		$customizer->add_select(
			[
				'id'      => 'ys_show_page_publish_date',
				'default' => 'both',
				'label'   => '投稿日・更新日の表示タイプ',
				'choices' => [
					'both'    => '投稿日・更新日',
					'publish' => '投稿日のみ',
					'update'  => '更新日のみ',
					'none'    => '表示しない',
				],
			]
		);
		$customizer->add_section_label( '記事下部' );
		$customizer->add_label(
			[
				'id'          => 'ys_after_contents_section_label',
				'label'       => '記事下コンテンツの表示・非表示設定',
				'description' => '※シェアボタンの表示は「[ys]SNS」→「SNSシェアボタン」から設定できます',
			]
		);
		// 著者情報を表示する.
		$customizer->add_checkbox(
			[
				'id'      => 'ys_show_page_author',
				'default' => 1,
				'label'   => '著者情報',
			]
		);
	}
}

new Post_Customizer();
