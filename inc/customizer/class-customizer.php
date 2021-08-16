<?php
/**
 * テーマカスタマイザーコントロール追加
 *
 * @package ystandard
 * @author  yosiakatsuki
 * @license GPL-2.0+
 */

namespace ystandard;

use ystandard\helper\Style_Sheet;

defined( 'ABSPATH' ) || die();

/**
 * Class Customizer
 */
class Customizer {

	/**
	 * パネルリスト
	 */
	const PANEL_PRIORITY = [
		'ys_info_bar'           => 1000,
		'ys_design'             => 1010,
		'ys_sns'                => 1100,
		'ys_seo'                => 1110,
		'ys_wp_sitemap'         => 1120,
		'ys_performance_tuning' => 1130,
		'ys_advertisement'      => 1140,
		'ys_amp'                => 1300,
		'ys_extension'          => 2000,
	];

	/**
	 * YS_Customize_Register constructor.
	 */
	public function __construct() {
		add_action( 'customize_register', [ $this, 'customize_register' ] );
		add_action( 'customize_preview_init', [ $this, 'preview_init' ], 999 );
		add_action( Enqueue_Utility::FILTER_INLINE_CSS, [ $this, 'preview_inline_css' ], 999 );
		add_action( 'customize_controls_print_styles', [ $this, 'print_styles' ] );
		add_action( 'customize_controls_enqueue_scripts', [ $this, 'enqueue_scripts' ] );
	}

	/**
	 * パネル・セクションの優先度を取得
	 *
	 * @param string $key Panel or Section name.
	 *
	 * @return int
	 */
	public static function get_priority( $key ) {

		if ( array_key_exists( $key, self::PANEL_PRIORITY ) ) {
			return self::PANEL_PRIORITY[ $key ];
		}

		return 1000;
	}

	/**
	 * テーマカスタマイザープレビュー用JS
	 *
	 * @return void
	 */
	public function preview_init() {
		wp_enqueue_script(
			'ys-customize-preview-js',
			get_template_directory_uri() . '/js/admin/customizer-preview.js',
			[ 'jquery', 'customize-preview' ],
			date_i18n( 'YmdHis' ),
			true
		);
	}

	/**
	 * プレビュー用CSS
	 *
	 * @param string $css CSS.
	 *
	 * @return string
	 */
	public function preview_inline_css( $css ) {
		if ( ! is_customize_preview() ) {
			return $css;
		}
		// ヘッダー固定設定用.
		$css .= '
		.header-height-info {
			position: absolute;
			top:0;
			left:0;
			padding:.25em 1em;
			background-color:rgba(0,0,0,.7);
			font-size:.7rem;
			color:#fff;
			z-index:99;
		}';
		// サイドバー表示用.
		if ( Option::get_option_by_bool( 'ys_hide_sidebar_mobile', false ) ) {
			$css .= Style_Sheet::add_media_query(
				'.is-customize-preview .sidebar {display:none;}',
				'',
				'sm'
			);
		}

		return $css;
	}

	/**
	 * テーマカスタマイザー用JS
	 *
	 * @return void
	 */
	public function enqueue_scripts() {
		wp_enqueue_script(
			'ys-customize-controls-js',
			get_template_directory_uri() . '/js/admin/customizer-control.js',
			[ 'customize-controls', 'jquery' ],
			Utility::get_ystandard_version(),
			true
		);
	}


	/**
	 * 管理画面-テーマカスタマイザーページでのスタイルシートの読み込み
	 *
	 * @param string $hook_suffix suffix.
	 *
	 * @return void
	 */
	public function print_styles( $hook_suffix ) {
		wp_enqueue_style(
			'ys-customizer',
			get_template_directory_uri() . '/css/customizer.css',
			[],
			Utility::get_ystandard_version()
		);
	}


	/**
	 * カスタマイザー追加
	 *
	 * @param \WP_Customize_Manager $wp_customize カスタマイザー.
	 */
	public function customize_register( $wp_customize ) {

		$customizer = new Customize_Control( $wp_customize );
		/**
		 * WP標準の設定を削除
		 */
		$wp_customize->remove_setting( 'background_color' );
		$wp_customize->remove_section( 'colors' );
		$wp_customize->remove_control( 'display_header_text' );

		/**
		 * 拡張機能
		 */
		$wp_customize->add_panel(
			'ys_extension',
			[
				'title'       => '[ys]拡張機能',
				'description' => 'yStandard専用プラグイン等による拡張機能の設定',
				'priority'    => 9999,
			]
		);
	}

	/**
	 * カスタマイザー用画像アセットURL取得
	 *
	 * @param string $file File Path.
	 *
	 * @return string
	 */
	public static function get_assets_dir_uri( $file = '' ) {
		$result = get_template_directory_uri() . '/assets/images/customizer';

		if ( $file ) {
			$result .= $file;
		}

		return $result;
	}
}

new Customizer();
