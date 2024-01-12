<?php
/**
 * カスタマイザー
 *
 * @package ystandard
 * @author  yosiakatsuki
 * @license GPL-3.0+
 */

namespace ystandard\customizer;

defined( 'ABSPATH' ) || die();

/**
 * Class Customizer
 *
 * @package ystandard
 */
class Customizer {

	/**
	 * パネルの優先度.
	 */
	const PANEL_PRIORITY = [
		'ys_panel_logo'         => 1000,
		'ys_panel_header'       => 1010,
		'ys_info_bar'           => 1000,
		'ys_design'             => 1010,
		'ys_block_editor'       => 1020,
		'ys_sns'                => 1100,
		'ys_seo'                => 1110,
		'ys_feed'               => 1120,
		'ys_wp_sitemap'         => 1210,
		'ys_performance_tuning' => 1220,
		'ys_advertisement'      => 1230,
		'ys_extension'          => 10000,
	];

	/**
	 * パネルの優先度を取得する.
	 *
	 * @param string $key パネル名.
	 *
	 * @return int
	 */
	public static function get_priority( $key ) {

		if ( array_key_exists( $key, self::PANEL_PRIORITY ) ) {
			return self::PANEL_PRIORITY[ $key ];
		}

		return 9999;
	}

	/**
	 * カスタマイザー用画像アセットURL取得
	 *
	 * @param string $file File Path.
	 *
	 * @return string
	 */
	public static function get_assets_dir_uri( $file = '' ) {

		$file = ltrim( $file, '/' );

		return get_template_directory_uri() . "/assets/images/customizer/{$file}";
	}

	/**
	 * Customizer constructor.
	 */
	public function __construct() {
		add_action( 'customize_controls_enqueue_scripts', [ $this, 'enqueue_control_scripts' ] );
		add_action( 'customize_controls_enqueue_scripts', [ $this, 'enqueue_control_styles' ] );
		add_action( 'customize_preview_init', [ $this, 'enqueue_preview_script' ], 999 );
		add_action( 'customize_preview_init', [ $this, 'enqueue_preview_styles' ], 999 );

		add_action( 'customize_register', [ $this, 'add_control_type' ] );
		add_action( 'customize_register', [ $this, 'change_default_control' ] );
	}

	/**
	 * コントロール追加
	 *
	 * @param \WP_Customize_Manager $wp_customize カスタマイザー.
	 */
	public function add_control_type( $wp_customize ) {
		$wp_customize->register_control_type( __NAMESPACE__ . '\Color_Control' );
	}

	/**
	 * 標準設定の一部を変更
	 *
	 * @param \WP_Customize_Manager $wp_customize カスタマイザー.
	 */
	public function change_default_control( $wp_customize ) {
		$customizer = new Customizer_Controls( $wp_customize );
		$customizer->add_section_label(
			_x( 'ロゴ・サイトタイトル設定', 'customizer', 'ystandard' ),
			[
				'section'     => 'title_tagline',
				'description' => _x( 'yStandardの機能により、ロゴ・サイトタイトル設定は「[ys]ロゴ・タイトル」設定に移動しました。', 'customizer', 'ystandard' ),
			]
		);
		// 設定の場所変更等のため一旦削除.
		// セクション削除.
		$wp_customize->remove_section( 'colors' );
		$wp_customize->remove_section( 'background_image' );
		// transportの設定変更.
		$customizer->set_refresh( 'custom_logo' );
		$customizer->set_refresh( 'blogname' );
		$customizer->set_refresh( 'blogdescription' );
	}

	/**
	 * テーマカスタマイザーページのJS読み込み.
	 * @return void
	 */
	public function enqueue_control_scripts() {
		$path = '/assets/js/ystandard-customizer-control.js';
		wp_enqueue_script(
			'ys-customizer-control',
			get_template_directory_uri() . $path,
			[ 'jquery', 'customize-controls' ],
			filemtime( get_template_directory() . $path ),
			[
				'in_footer' => true,
				'strategy'  => 'defer',
			]
		);
	}

	/**
	 * テーマカスタマイザーページのCSS読み込み.
	 * @return void
	 */
	public function enqueue_control_styles() {
		$handle = 'ys-customizer-control';
		$path   = '/assets/css/ystandard-customizer-control.css';
		wp_enqueue_style(
			$handle,
			get_template_directory_uri() . $path,
			[],
			filemtime( get_template_directory() . $path ),
		);
		wp_style_add_data(
			$handle,
			'path',
			get_template_directory() . $path
		);
	}

	/**
	 * テーマカスタマイザープレビューのJS読み込み.
	 * @return void
	 */
	public function enqueue_preview_script() {
		$path = '/assets/js/ystandard-customizer-preview.js';
		wp_enqueue_script(
			'ys-customizer-preview',
			get_template_directory_uri() . $path,
			[ 'jquery', 'customize-preview' ],
			filemtime( get_template_directory() . $path ),
			[
				'in_footer' => true,
				'strategy'  => 'defer',
			]
		);
	}

	/**
	 * テーマカスタマイザープレビューのCSS読み込み.
	 * @return void
	 */
	public function enqueue_preview_styles() {
		$handle = 'ys-customizer-preview';
		$path   = '/assets/css/ystandard-customizer-preview.css';
		wp_enqueue_style(
			$handle,
			get_template_directory_uri() . $path,
			[],
			filemtime( get_template_directory() . $path ),
		);
		wp_style_add_data(
			$handle,
			'path',
			get_template_directory() . $path
		);
	}
}

new Customizer();
