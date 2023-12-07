<?php
/**
 * 拡張機能
 *
 * @package ystandard
 * @author  yosiakatsuki
 * @license GPL-3.0+
 */

namespace ystandard;

use ystandard\customizer\Customizer;

defined( 'ABSPATH' ) || die();

/**
 * Class Extension
 *
 * @package ystandard
 */
class Extension {

	/**
	 * Constructor.
	 *
	 * @codeCoverageIgnore
	 */
	public function __construct() {
		add_action( 'customize_register', [ $this, 'add_panel' ] );
	}

	/**
	 * パネル追加.
	 *
	 * @param \WP_Customize_Manager $wp_customize カスタマイザー.
	 */
	public function add_panel( $wp_customize ) {

		/**
		 * 拡張機能
		 */
		$wp_customize->add_panel(
			'ys_extension',
			[
				'title'       => _x( '[ys]拡張機能', 'customizer', 'ystandard' ),
				'description' => _x( 'yStandard専用プラグイン等による拡張機能の設定', 'customizer', 'ystandard' ),
				'priority'    => Customizer::get_priority( 'ys_extension' ),
			]
		);
	}
}

new Extension();
