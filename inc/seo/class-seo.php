<?php
/**
 * SEO
 *
 * @package ystandard
 * @author  yosiakatsuki
 * @license GPL-2.0+
 */

namespace ystandard;

/**
 * Class SEO
 *
 * @package ystandard
 */
class SEO {

	/**
	 * パネル名
	 */
	const PANEL_NAME = 'ys_seo';

	/**
	 * SEO constructor.
	 */
	public function __construct() {
		add_action( 'customize_register', [ $this, 'customize_register' ] );

	}

	/**
	 * カスタマイザー追加
	 *
	 * @param \WP_Customize_Manager $wp_customize カスタマイザー.
	 */
	public function customize_register( $wp_customize ) {
		$customizer = new Customize_Control( $wp_customize );
		$customizer->add_panel(
			[
				'title' => '[ys]SEO',
				'panel' => self::PANEL_NAME,
			]
		);
		/**
		 * SEO : meta descriptionを自動生成する
		 */
		$customizer->add_section(
			[
				'section'  => 'ys_meta_description',
				'title'    => 'meta description設定',
				'priority' => 1,
			]
		);
		// 自動生成する.
		$customizer->add_checkbox(
			[
				'id'        => 'ys_option_create_meta_description',
				'default'   => 1,
				'transport' => 'postMessage',
				'label'     => 'meta descriptionを自動生成する',
			]
		);
		// 抜粋文字数.
		$customizer->add_number(
			[
				'id'        => 'ys_option_meta_description_length',
				'default'   => 80,
				'transport' => 'postMessage',
				'label'     => 'meta descriptionに使用する文字数',
			]
		);
		/**
		 * アーカイブページのnoindex設定
		 */
		$customizer->add_section(
			[
				'section'  => 'ys_customizer_section_noindex',
				'title'    => 'アーカイブページのnoindex設定',
				'priority' => 1,
				'panel'    => 'ys_customizer_panel_seo',
			]
		);
		/**
		 * カテゴリー一覧をnoindexにする
		 */
		$customizer->add_checkbox(
			[
				'id'        => 'ys_archive_noindex_category',
				'default'   => 0,
				'transport' => 'postMessage',
				'label'     => 'カテゴリー一覧をnoindexにする',
			]
		);
		/**
		 * タグ一覧をnoindexにする
		 */
		$customizer->add_checkbox(
			[
				'id'        => 'ys_archive_noindex_tag',
				'default'   => 1,
				'transport' => 'postMessage',
				'label'     => 'タグ一覧をnoindexにする',
			]
		);
		/**
		 * 投稿者一覧をnoindexにする
		 */
		$customizer->add_checkbox(
			[
				'id'        => 'ys_archive_noindex_author',
				'default'   => 1,
				'transport' => 'postMessage',
				'label'     => '投稿者一覧をnoindexにする',
			]
		);
		/**
		 * 日別一覧をnoindexにする
		 */
		$customizer->add_checkbox(
			[
				'id'        => 'ys_archive_noindex_date',
				'default'   => 1,
				'transport' => 'postMessage',
				'label'     => '日別一覧をnoindexにする',
			]
		);
	}
}

new SEO;
