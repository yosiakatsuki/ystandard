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
	}
}

new SEO;
