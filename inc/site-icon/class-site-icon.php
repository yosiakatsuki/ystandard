<?php
/**
 * サイトアイコン
 *
 * @package ystandard
 * @author  yosiakatsuki
 * @license GPL-3.0+
 */

namespace ystandard;

use ystandard\customizer\Customizer_Controls;
use ystandard\utils\Manual;

defined( 'ABSPATH' ) || die();

/**
 * Class Site_Icon
 *
 * @package ystandard
 */
class Site_Icon {

	/**
	 * パネル名
	 */
	const SECTION_NAME = 'ys_panel_site-icon';

	public function __construct() {
		add_action( 'customize_register', [ $this, 'add_option_panel' ] );
		add_action( 'customize_register', [ $this, 'move_wp_setting' ] );
	}

	/**
	 * パネル追加
	 *
	 * @param \WP_Customize_Manager $wp_customize カスタマイザー.
	 */
	public function add_option_panel( $wp_customize ) {
		$customizer = new Customizer_Controls( $wp_customize );
		// サイトアイコン設定が移動したことの案内.
		$customizer->add_section_label(
			_x( 'サイトアイコン設定', 'customizer', 'ystandard' ),
			[
				'section'     => 'title_tagline',
				'description' => _x( 'yStandardの機能により、サイトアイコン設定は「[ys]サイトアイコン」設定に移動しました。', 'customizer', 'ystandard' ),
			]
		);

		$section    = self::SECTION_NAME;
		$customizer->add_section(
			[
				'section' => $section,
				'title'   => '[ys]' . _x( 'サイトアイコン', 'customizer', 'ystandard' ),
			]
		);
		$customizer->add_section_label(
			_x( 'サイトアイコン', 'customizer', 'ystandard' ),
			[
				'priority' => 9,
			]
		);
		// サイトアイコン.
		$customizer->set_section( 'site_icon', $section );
		$customizer->set_priority( 'site_icon', 10 );

	}

	/**
	 * サイトアイコン設定の移動
	 *
	 * @param \WP_Customize_Manager $wp_customize カスタマイザー.
	 */
	public function move_wp_setting( $wp_customize ) {
		$customizer = new Customizer_Controls( $wp_customize );
	}
}

new Site_Icon();
