<?php
/**
 * Meta Description.
 *
 * @package ystandard
 * @author  yosiakatsuki
 * @license GPL-2.0+
 */

namespace ystandard;

/**
 * Class No_Index
 *
 * @package ystandard
 */
class No_Index {

	/**
	 * No_Index constructor.
	 */
	public function __construct() {
		add_action( 'customize_register', [ $this, 'customize_register' ] );
		add_action( 'wp_head', [ $this, 'meta_noindex' ] );
	}

	/**
	 * Noindex処理
	 */
	public function meta_noindex() {
		$noindex = false;
		if ( is_404() ) {
			/**
			 * 404ページをnoindex
			 */
			$noindex = true;
		} elseif ( is_search() ) {
			/**
			 * 検索結果をnoindex
			 */
			$noindex = true;
		} elseif ( is_category() && Option::get_option_by_bool( 'ys_archive_noindex_category', false ) ) {
			/**
			 * カテゴリーページのnoindex設定がされていればnoindex
			 */
			$noindex = true;
		} elseif ( is_tag() && Option::get_option_by_bool( 'ys_archive_noindex_tag', true ) ) {
			/**
			 * カテゴリーページのnoindex設定がされていればnoindex
			 */
			$noindex = true;
		} elseif ( is_author() && Option::get_option_by_bool( 'ys_archive_noindex_author', true ) ) {
			/**
			 * カテゴリーページのnoindex設定がされていればnoindex
			 */
			$noindex = true;

		} elseif ( is_date() && Option::get_option_by_bool( 'ys_archive_noindex_date', true ) ) {
			/**
			 * カテゴリーページのnoindex設定がされていればnoindex
			 */
			$noindex = true;
		} elseif ( is_single() || is_page() ) {
			if ( '1' === Content::get_post_meta( 'ys_noindex' ) ) {
				/**
				 * 投稿・固定ページでnoindex設定されていればnoindex
				 */
				$noindex = true;
			}
		}
		$noindex = apply_filters( 'ys_the_noindex', $noindex );
		/**
		 * Noindex出力
		 */
		if ( $noindex ) {
			echo '<meta name="robots" content="noindex,follow">' . PHP_EOL;
		}
	}

	/**
	 * カスタマイザー追加
	 *
	 * @param \WP_Customize_Manager $wp_customize カスタマイザー.
	 */
	public function customize_register( $wp_customize ) {
		$customizer = new Customize_Control( $wp_customize );
		/**
		 * アーカイブページのnoindex設定
		 */
		$customizer->add_section(
			[
				'section'  => 'ys_noindex',
				'title'    => 'アーカイブページのnoindex',
				'priority' => 1,
				'panel'    => SEO::PANEL_NAME,
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
				'label'     => 'カテゴリーアーカイブをnoindexにする',
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
				'label'     => 'タグアーカイブをnoindexにする',
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
				'label'     => '投稿者アーカイブをnoindexにする',
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
				'label'     => '日別アーカイブをnoindexにする',
			]
		);
	}

}
