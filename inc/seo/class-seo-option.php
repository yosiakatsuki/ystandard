<?php
/**
 * SEO設定
 *
 * @package ystandard
 * @author  yosiakatsuki
 * @license GPL-3.0+
 */

namespace ystandard;

use ystandard\customizer\Customizer_Controls;

defined( 'ABSPATH' ) || die();


/**
 * Class SEO_Option
 *
 * @package ystandard
 */
class SEO_Option {

	/**
	 * パネル名
	 */
	const PANEL_NAME = 'ys_seo';

	/**
	 * constructor.
	 */
	public function __construct() {
		add_action( 'customize_register', [ $this, 'add_seo_option_panel' ] );
	}

	/**
	 * パネル追加
	 *
	 * @param \WP_Customize_Manager $wp_customize カスタマイザー.
	 */
	public function add_seo_option_panel( $wp_customize ) {
		$customizer = new Customizer_Controls( $wp_customize );
		$customizer->add_panel(
			[
				'[ys]' . _x( 'SEO', 'customizer', 'ystandard' ),
				'panel' => self::PANEL_NAME,
			]
		);
	}
}

new SEO_Option();
