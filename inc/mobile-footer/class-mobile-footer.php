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

		// 余白.
		// 上.
		$padding_top = Option::get_option( 'ys_mobile_footer__padding_top', '' );
		if ( '' !== $padding_top ) {
			$padding_top = Enqueue_Utility::get_css_var( 'mobile-footer--padding-top', $padding_top . 'px' );
			$css_vars    = array_merge( $css_vars, $padding_top );
		}
		// 下.
		$padding_bottom = Option::get_option( 'ys_mobile_footer__padding_bottom', '' );
		if ( '' !== $padding_bottom ) {
			$padding_bottom = Enqueue_Utility::get_css_var( 'mobile-footer--padding-bottom', $padding_bottom . 'px' );
			$css_vars       = array_merge( $css_vars, $padding_bottom );
		}
		// 左.
		$padding_left = Option::get_option( 'ys_mobile_footer__padding_left', '' );
		if ( '' !== $padding_left ) {
			$padding_left = Enqueue_Utility::get_css_var( 'mobile-footer--padding-left', $padding_left . 'px' );
			$css_vars     = array_merge( $css_vars, $padding_left );
		}
		// 右.
		$padding_right = Option::get_option( 'ys_mobile_footer__padding_right', '' );
		if ( '' !== $padding_right ) {
			$padding_right = Enqueue_Utility::get_css_var( 'mobile-footer--padding-right', $padding_right . 'px' );
			$css_vars      = array_merge( $css_vars, $padding_right );
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
				'title'       => '[ys]' . _x( 'モバイルフッターメニュー', 'customizer', 'ystandard' ),
				'description' => _x( 'モバイルフッターメニューの設定', 'customizer', 'ystandard' ),
				'priority'    => Customizer::get_priority( 'ys_mobile_footer' ),
			]
		);

		// モバイルフッター.
		$customizer->add_section_label(
			_x( '文字色・背景色設定', 'customizer', 'ystandard' ),
			[
				'description' => Admin::manual_link( 'manual/mobile-footer-menu' ),
			]
		);
		// モバイルフッター背景色.
		$customizer->add_color(
			[
				'id'      => 'ys_color_mobile_footer_bg',
				'default' => '',
				'label'   => _x( '背景色', 'customizer', 'ystandard' ),
			]
		);
		// モバイルフッター文字色.
		$customizer->add_color(
			[
				'id'      => 'ys_color_mobile_footer_text',
				'default' => '',
				'label'   => _x( '文字色', 'customizer', 'ystandard' ),
			]
		);

		// 余白の設定(Padding).
		$customizer->add_section_label(
			_x( '余白設定', 'customizer', 'ystandard' ),
			[
				'description' => Admin::manual_link( 'manual/mobile-footer-menu' ) . _x( '0~100の間で設定してください。それ以外の数値を入力すると設定が保存されません。', 'customizer', 'ystandard' ),
			]
		);
		// モバイルフッターPadding Top.
		$customizer->add_number(
			[
				'id'          => 'ys_mobile_footer__padding_top',
				'label'       => _x( '余白-上', 'customizer', 'ystandard' ),
				'input_attrs' => [
					'min' => 0,
					'max' => 100,
				],
			]
		);
		// モバイルフッターPadding Bottom.
		$customizer->add_number(
			[
				'id'          => 'ys_mobile_footer__padding_bottom',
				'label'       => _x( '余白-下', 'customizer', 'ystandard' ),
				'input_attrs' => [
					'min' => 0,
					'max' => 100,
				],
			]
		);
		// モバイルフッターPadding left.
		$customizer->add_number(
			[
				'id'          => 'ys_mobile_footer__padding_left',
				'label'       => _x( '余白-左', 'customizer', 'ystandard' ),
				'input_attrs' => [
					'min' => 0,
					'max' => 100,
				],
			]
		);
		// モバイルフッターPadding right.
		$customizer->add_number(
			[
				'id'          => 'ys_mobile_footer__padding_right',
				'label'       => _x( '余白-右', 'customizer', 'ystandard' ),
				'input_attrs' => [
					'min' => 0,
					'max' => 100,
				],
			]
		);
	}
}

new Mobile_Footer();
