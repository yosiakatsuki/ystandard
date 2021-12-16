<?php
/**
 * モバイル設定
 *
 * @package ystandard
 * @author  yosiakatsuki
 * @license GPL-2.0+
 */

namespace ystandard;

defined( 'ABSPATH' ) || die();

/**
 * Class Mobile
 *
 * @package ystandard
 */
class Mobile {

	/**
	 * Mobile constructor.
	 */
	public function __construct() {
		add_action( 'customize_register', [ $this, 'customize_register' ] );
	}

	/**
	 * モバイル用デザイン設定追加
	 *
	 * @param \WP_Customize_Manager $wp_customize カスタマイザー.
	 */
	public function customize_register( $wp_customize ) {
		$customizer = new Customize_Control( $wp_customize );
		/**
		 * セクション追加
		 */
		$customizer->add_section(
			[
				'section'     => 'ys_mobile_design',
				'title'       => 'モバイル',
				'description' => 'モバイルページの設定' . Admin::manual_link( 'manual/mobile-page' ),
				'priority'    => 80,
				'panel'       => Design::PANEL_NAME,
			]
		);


		// サイドバー.
		$customizer->add_section_label( 'サイドバー表示' );
		// サイドバー出力.
		$customizer->add_checkbox(
			[
				'id'      => 'ys_hide_sidebar_mobile',
				'default' => 0,
				'label'   => 'モバイル表示でサイドバーを非表示にする',
			]
		);
	}
}

new Mobile();
