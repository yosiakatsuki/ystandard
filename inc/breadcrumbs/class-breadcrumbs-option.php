<?php
/**
 * パンくずリスト設定
 *
 * @package ystandard
 * @author  yosiakatsuki
 * @license GPL-2.0+
 */

namespace ystandard;

defined( 'ABSPATH' ) || die();

/**
 * Class Breadcrumbs_Option
 */
class Breadcrumbs_Option {

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
		$customizer->add_section(
			[
				'section'     => 'ys_breadcrumbs',
				'title'       => __( '[ys]パンくずリスト', 'ystandard' ),
				'priority'    => Customizer::get_priority( 'ys_breadcrumbs' ),
				'description' => Admin::manual_link( 'manual/breadcrumbs' ),
			]
		);
		$customizer->add_section_label( __( '表示設定', 'ystandard' ) );
		/**
		 * パンくずリスト表示位置
		 */
		$customizer->add_radio(
			[
				'id'          => 'ys_breadcrumbs_position',
				'default'     => 'footer',
				'label'       => __( 'パンくずリストの表示位置', 'ystandard' ),
				'description' => '',
				'choices'     => [
					'header' => __( 'ヘッダー', 'ystandard' ),
					'footer' => __( 'フッター', 'ystandard' ),
					'none'   => __( '表示しない', 'ystandard' ),
				],
			]
		);
		$customizer->add_section_label(
			__( '投稿ヘッダー無しテンプレート用設定', 'ystandard' ),
			[
				'active_callback' => [ $this, 'is_show_blank_option' ],
			]
		);
		$customizer->add_checkbox(
			[
				'id'              => 'ys_show_breadcrumb_blank_template',
				'default'         => 0,
				'label'           => __( '投稿ヘッダー無しテンプレートでもパンくずリストを表示する', 'ystandard' ),
				'active_callback' => [ $this, 'is_show_blank_option' ],
			]
		);

		$customizer->add_section_label( __( '色設定', 'ystandard' ) );
		// パンくずリスト文字色.
		$customizer->add_color(
			[
				'id'      => 'ys_color_breadcrumbs_text',
				'default' => '#656565',
				'label'   => __( 'パンくずリスト文字色', 'ystandard' ),
			]
		);
		/**
		 * パンくずリストに「投稿ページ」を表示する
		 */
		if ( 'page' === get_option( 'show_on_front' ) && get_option( 'page_for_posts' ) ) {

			$customizer->add_label(
				[
					'id'          => 'ys_show_page_for_posts_on_breadcrumbs_label',
					'label'       => __( 'パンくずリストの「投稿ページ」表示', 'ystandard' ),
					'description' => __( 'パンくずリストに「設定」→「表示設定」→「ホームページの表示」で「投稿ページ」で指定したページの表示設定', 'ystandard' ),
				]
			);
			$customizer->add_checkbox(
				[
					'id'      => 'ys_show_page_for_posts_on_breadcrumbs',
					'default' => 1,
					'label'   => __( 'パンくずリストに「投稿ページ」を表示する', 'ystandard' ),
				]
			);
		}
	}
	/**
	 * ヘッダー無しテンプレートオプションを表示するか.
	 *
	 * @return boolean
	 */
	public function is_show_blank_option() {
		return 'footer' === Option::get_option( 'ys_breadcrumbs_position', 'footer' );
	}
}

new Breadcrumbs_Option();
