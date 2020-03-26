<?php
/**
 * Design
 *
 * @package ystandard
 * @author  yosiakatsuki
 * @license GPL-2.0+
 */

namespace ystandard;

/**
 * Class Design
 *
 * @package ystandard
 */
class Design {

	CONST PANEL_NAME = 'ys_design';

	/**
	 * Design constructor.
	 */
	public function __construct() {
		add_action( 'customize_register', [ $this, 'customize_register' ], 9 );
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
				'panel'       => 'ys_customizer_panel_design',
				'title'       => '[ys]デザイン',
				'description' => 'サイト共通部分のデザイン設定',
			]
		);
	}
}

new Design();
