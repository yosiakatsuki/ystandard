<?php
/**
 * 管理画面関連の CSS/JS
 *
 * @package ystandard
 * @author  yosiakatsuki
 * @license GPL-2.0+
 */

namespace ystandard;

/**
 * Class Enqueue_Admin
 *
 * @package ystandard
 */
class Enqueue_Admin {

	/**
	 * ブロックエディター用インラインCSSフック名
	 */
	const BLOCK_EDITOR_ASSETS_HOOK = 'ys_block_editor_assets_inline_css';

	/**
	 * Enqueue_Admin constructor.
	 */
	public function __construct() {
		add_action( 'wp_enqueue_scripts', [ $this, 'enqueue_admin_bar_style' ] );
		if ( ! is_admin() ) {
			return;
		}
		add_action( 'admin_enqueue_scripts', [ $this, 'admin_enqueue_scripts' ] );
		add_action( 'admin_enqueue_scripts', [ $this, 'admin_enqueue_styles' ] );
		add_action( 'after_setup_theme', [ $this, 'enqueue_block_css' ] );
		add_action( 'enqueue_block_editor_assets', [ $this, 'enqueue_block_editor_assets' ] );
		add_action( 'admin_init', [ $this, 'enqueue_visual_editor_styles' ] );
		add_action( 'tiny_mce_before_init', [ $this, 'tiny_mce_before_init' ] );
	}

	/**
	 * アドミンバー調整用CSS
	 */
	public function enqueue_admin_bar_style() {
		wp_enqueue_style(
			'ys-admin-bar',
			get_template_directory_uri() . '/css/admin-bar.css',
			[ 'admin-bar' ],
			Utility::get_ystandard_version()
		);
	}

	/**
	 * 管理画面-JavaScriptの読み込み
	 *
	 * @param string $hook_suffix suffix.
	 *
	 * @return void
	 */
	public function admin_enqueue_scripts( $hook_suffix ) {
		/**
		 * メディアアップローダ
		 */
		wp_enqueue_media();
		wp_enqueue_script(
			'ys-admin-scripts',
			get_template_directory_uri() . '/js/admin/admin.js',
			[ 'jquery', 'jquery-core' ],
			Utility::get_ystandard_version(),
			true
		);
		wp_enqueue_script(
			'ys-custom-uploader',
			get_template_directory_uri() . '/js/admin/custom-uploader.js',
			[ 'jquery', 'jquery-core' ],
			Utility::get_ystandard_version(),
			true
		);
	}

	/**
	 * 管理画面-CSSの読み込み
	 *
	 * @param string $hook_suffix suffix.
	 *
	 * @return void
	 */
	public function admin_enqueue_styles( $hook_suffix ) {
		wp_enqueue_style(
			'ys-google-font',
			'https://fonts.googleapis.com/css?family=Orbitron'
		);
		wp_enqueue_style(
			'ys-admin',
			get_template_directory_uri() . '/css/admin.css',
			[],
			Utility::get_ystandard_version()
		);
		wp_enqueue_style(
			'font-awesome',
			Font_Awesome::get_font_awesome_css_url(),
			[],
			Font_Awesome::FONTAWESOME_VER
		);
	}

	/**
	 * Enqueue block editor style
	 */
	public function enqueue_block_css() {
		add_theme_support( 'editor-styles' );
		add_editor_style( 'css/block-editor.css' );
		add_editor_style( 'style.css' );
	}

	/**
	 * ブロックエディタのスタイル追加
	 */
	public function enqueue_block_editor_assets() {
		wp_enqueue_style(
			'ys-block-editor-assets',
			get_template_directory_uri() . '/css/block-editor-assets.css'
		);
		wp_add_inline_style(
			'ys-block-editor-assets',
			apply_filters( self::BLOCK_EDITOR_ASSETS_HOOK, '' )
		);
	}


	/**
	 * ビジュアルエディタ用CSS追加
	 */
	public function enqueue_visual_editor_styles() {
		/**
		 * ビジュアルエディターへのCSSセット
		 */
		add_editor_style( 'css/tiny-mce-style.css' );
		add_editor_style( 'style.css' );
	}

	/**
	 * TinyMCEに追加CSSを適用させる
	 *
	 * @param array $settings TinyMCE設定.
	 *
	 * @return array;
	 */
	public function tiny_mce_before_init( $settings ) {
		$settings['content_style'] = str_replace( '"', '\'', Enqueue_Styles::minify( wp_get_custom_css() ) );

		return $settings;

	}
}

new Enqueue_Admin();
