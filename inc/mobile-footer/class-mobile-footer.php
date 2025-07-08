<?php
/**
 * モバイルフッター
 *
 * @package ystandard
 * @author  yosiakatsuki
 * @license GPL-2.0+
 */

namespace ystandard;

use ystandard\utils\CSS;

defined( 'ABSPATH' ) || die();

/**
 * Class Mobile_Footer
 */
class Mobile_Footer {

	/**
	 * Constructs the Mobile_Footer class.
	 */
	public function __construct() {
		// カスタマイザー.
		add_action( 'customize_register', [ $this, 'customize_register' ] );
		add_filter( 'ys_get_css_custom_properties_args', [ $this, 'add_css_var' ] );
	}

	/**
	 * CSS変数を追加.
	 *
	 * @param array $css_vars CSS.
	 *
	 * @return array
	 */
	public function add_css_var( $css_vars ) {

		// モバイルフッター背景色.
		$bg_color = Option::get_option( 'ys_color_mobile_footer_bg', '' );
		if ( $bg_color ) {
			$bg_color = CSS::hex_2_rgb( $bg_color );
			$bg       = Enqueue_Utility::get_css_var(
				'mobile-footer--background',
				sprintf(
					'rgb(%s,%s,%s,0.95)',
					$bg_color[0],
					$bg_color[1],
					$bg_color[2]
				)
			);
			$css_vars = array_merge( $css_vars, $bg );
		}

		// 文字色.
		$text_color = Option::get_option( 'ys_color_mobile_footer_text', '' );
		if ( $text_color ) {
			$color    = Enqueue_Utility::get_css_var( 'mobile-footer--text-color', $text_color );
			$css_vars = array_merge( $css_vars, $color );
		}

		return $css_vars;
	}

	/**
	 * カスタマイザーの設定を追加
	 *
	 * @param \WP_Customize_Manager $wp_customize カスタマイザー.
	 */
	public function customize_register( $wp_customize ) {
		$customizer = new Customize_Control( $wp_customize );
		$customizer->add_section(
			[
				'section'     => 'ys_mobile_footer',
				'title'       => '[ys]' . _x( 'モバイルフッター', 'customizer', 'ystandard' ),
				'description' => _x( 'モバイルフッターの設定', 'customizer', 'ystandard' ),
				'priority'    => Customizer::get_priority( 'ys_mobile_footer' ),
			]
		);

		// モバイルフッター.
		$customizer->add_section_label(
			_x( '色設定', 'customizer', 'ystandard' ),
			[
				'description' => Admin::manual_link( 'manual/mobile-footer-menu' ),
			]
		);
		// モバイルフッター背景色.
		$customizer->add_color(
			[
				'id'      => 'ys_color_mobile_footer_bg',
				'default' => '',
				'label'   => _x( 'モバイルフッター背景色', 'customizer', 'ystandard' ),
			]
		);
		// モバイルフッター文字色.
		$customizer->add_color(
			[
				'id'      => 'ys_color_mobile_footer_text',
				'default' => '',
				'label'   => _x( 'モバイルフッター文字色', 'customizer', 'ystandard' ),
			]
		);
	}
}
new Mobile_Footer();
