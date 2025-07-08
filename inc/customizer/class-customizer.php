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
use ystandard\utils\CSS;
use ystandard\utils\Theme;

defined( 'ABSPATH' ) || die();

/**
 * Class Customizer
 */
class Customizer {

	/**
	 * パネルリスト
	 */
	const PANEL_PRIORITY = [
		'ys_info_bar'           => 1000, // お知らせバー.
		'ys_site_typography'    => 1100, // フォント・文字色.
		'ys_site_background'    => 1110, // サイト背景（未整理）.
		'ys_site_header'        => 1200, // サイトヘッダー（未整理）.
		'ys_global_nav'         => 1210, // グローバルナビゲーション.
		'ys_drawer_nav'         => 1210, // ドロワーメニュー（モバイルメニュー）.
		'ys_color_palette'      => 1220, // カラーパレット（未整理）.ブロックエディター設定からの移行.
		'ys_post_type_option'   => 1300, // 投稿タイプ別設定.投稿(1300), 固定ページ(1301),は固定。それ以降は1310で追加順となる.
		'ys_site_footer'        => 1400, // サイトフッター.
		'ys_mobile_footer'      => 1410, // モバイルフッター.
		'ys_site_copyright'     => 1420, // Copyright.
		'ys_breadcrumbs'        => 1500, // パンくずリスト.
		'ys_toc'                => 1510, // 目次(未整理).
		'ys_sns'                => 1520, // SNS.
		'ys_seo'                => 1530, // SEO.
		'ys_feed'               => 1540, // RSSフィード.
		'ys_wp_sitemap'         => 1550, // XMLサイトマップ.
		'ys_performance_tuning' => 1600, // パフォーマンスチューニング.
		'ys_advertisement'      => 1700, // 広告.
		'ys_extension'          => 2000, // 拡張機能.

		'ys_blog_card'          => 910, // ブログカード(廃止予定).
		'ys_design'             => 900, // デザイン(廃止予定).
	];

	/**
	 * YS_Customize_Register constructor.
	 */
	public function __construct() {
		add_action( 'customize_register', [ $this, 'customize_register' ] );
		add_action( 'customize_preview_init', [ $this, 'preview_init' ], 999 );
		add_action( 'ys_get_inline_css', [ $this, 'preview_inline_css' ], 999 );
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
			get_template_directory_uri() . '/js/customizer-preview.js',
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
			// モバイルで非表示.
			$css .= CSS::add_media_query_mobile( '.is-customize-preview .sidebar {display:none;}' );
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
			get_template_directory_uri() . '/js/customizer-control.js',
			[ 'customize-controls', 'jquery' ],
			filemtime( get_template_directory() . '/js/customizer-control.js' ),
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
			Theme::get_ystandard_version()
		);
	}


	/**
	 * カスタマイザー追加
	 *
	 * @param \WP_Customize_Manager $wp_customize カスタマイザー.
	 */
	public function customize_register( $wp_customize ) {

		/**
		 * WP標準の設定を削除
		 */
		$wp_customize->remove_setting( 'background_color' );
		$wp_customize->remove_section( 'colors' );
		$wp_customize->remove_control( 'display_header_text' );

		/**
		 * カスタムコントロールの追加
		 */
		$wp_customize->register_control_type( __NAMESPACE__ . '\Color_Control' );

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
