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
 * Class Site_Background
 *
 * @package ystandard
 */
class Site_Background {

	/**
	 * Site_Background constructor.
	 */
	public function __construct() {
		add_action( 'customize_register', [ $this, 'customize_register' ] );
		add_action( 'customize_save', [ $this, 'customize_save_after' ] );
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
			Enqueue_Utility::get_css_var( 'site-bg', self::get_site_bg() )
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
				'section'     => 'ys_site_background',
				'title'       => 'サイト背景',
				'description' => Admin::manual_link( 'site-background' ),
				'priority'    => 20,
				'panel'       => Design::PANEL_NAME,
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
		// 背景画像.
		$customizer->set_section( 'background_image', 'ys_site_background' );
		$customizer->set_section( 'background_preset', 'ys_site_background' );
		$customizer->set_section( 'background_size', 'ys_site_background' );
		$customizer->set_section( 'background_repeat', 'ys_site_background' );
		$customizer->set_section( 'background_attachment', 'ys_site_background' );
		$customizer->set_section( 'background_position', 'ys_site_background' );
		$customizer->set_refresh( 'background_image' );
		$customizer->set_refresh( 'background_preset' );
		$customizer->set_refresh( 'background_size' );
		$customizer->set_refresh( 'background_repeat' );
		$customizer->set_refresh( 'background_attachment' );
		$customizer->set_refresh( 'background_position_x' );
		$customizer->set_refresh( 'background_position_y' );
	}

	/**
	 * Fires after Customize settings have been saved.
	 *
	 * @param \WP_Customize_Manager $manager WP_Customize_Manager instance.
	 */
	public function customize_save_after( $manager ) {
		if ( empty( get_theme_mod( 'background_image', '' ) ) || 'default' === get_theme_mod( 'background_preset', 'default' ) ) {
			remove_theme_mod( 'background_preset' );
			remove_theme_mod( 'background_size' );
			remove_theme_mod( 'background_repeat' );
			remove_theme_mod( 'background_attachment' );
			remove_theme_mod( 'background_position_x' );
			remove_theme_mod( 'background_position_y' );
		}
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
		return Option::get_option( 'ys_color_site_bg', self::get_site_bg_default() );
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

new Site_Background();
