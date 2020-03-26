<?php
/**
 * サイトカラー
 *
 * @package ystandard
 * @author  yosiakatsuki
 * @license GPL-2.0+
 */

namespace ystandard;

/**
 * Class Site_Color
 *
 * @package ystandard
 */
class Site_Color {

	/**
	 * Site_Color constructor.
	 */
	public function __construct() {
		add_action( 'customize_register', [ $this, 'customize_register' ] );
		add_filter( 'ys_css_vars', [ $this, 'add_css_vars' ] );
	}

	/**
	 * フォントCSS
	 *
	 * @param array $css_vars CSS.
	 *
	 * @return array
	 */
	public function add_css_vars( $css_vars ) {

		return array_merge(
			$css_vars,
			Css_Vars::get_css_var( 'site-bg-color', self::get_site_bg() )
		);
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
				'section' => 'ys_color_site',
				'title'   => 'サイト背景色',
				'panel'   => Design::PANEL_NAME,
			]
		);
		// サイト背景色.
		$customizer->add_color(
			[
				'id'      => 'ys_color_site_bg',
				'default' => self::get_site_bg_default(),
				'label'   => 'サイト背景色',
			]
		);

	}

	/**
	 * サイト背景色デフォルト値取得
	 *
	 * @return string
	 */
	public static function get_site_bg_default() {
		return Option::get_default( 'ys_color_site_bg', '#ffffff' );
	}

	/**
	 * サイト背景色取得
	 *
	 * @return string
	 */
	public static function get_site_bg() {
		return ys_get_option( 'ys_color_site_bg', self::get_site_bg_default() );
	}

	/**
	 * カスタム背景色を使っているか
	 *
	 * @return bool
	 */
	public static function is_custom_bg_color() {
		return self::get_site_bg_default() !== self::get_site_bg();
	}

}

new Site_Color();
