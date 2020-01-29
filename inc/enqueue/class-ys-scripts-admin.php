<?php
/**
 * CSS,JavaScript読み込み関連のためのクラス(管理画面)
 *
 * @package ystandard
 * @author  yosiakatsuki
 * @license GPL-2.0+
 */

/**
 * CSS,JavaScript読み込み関連のためのクラス(管理画面)
 */
class YS_Scripts_Admin {
	/**
	 * YS_Scripts_Admin constructor.
	 */
	public function __construct() {
		add_action( 'admin_enqueue_scripts', array( $this, 'admin_enqueue_scripts' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'admin_enqueue_styles' ) );
		/**
		 * ブロックエディタ関連
		 */
		if ( ys_get_option( 'ys_admin_enable_block_editor_style', true, 'bool' ) ) {
			add_action( 'after_setup_theme', array( $this, 'enqueue_block_css' ) );
			add_action( 'enqueue_block_editor_assets', array( $this, 'enqueue_block_editor_assets' ) );
		}
		/**
		 * ビジュアルエディタ関連
		 */
		if ( ys_get_option( 'ys_admin_enable_tiny_mce_style', true, 'bool' ) ) {
			add_action( 'admin_init', array( $this, 'enqueue_visual_editor_styles' ) );
			add_action( 'tiny_mce_before_init', array( $this, 'tiny_mce_before_init' ) );
		}
		/**
		 * テーマカスタマイザー関連
		 */
		add_action( 'customize_preview_init', array( $this, 'customize_preview_init' ) );
		add_action( 'customize_controls_print_styles', array( $this, 'customize_controls_print_styles' ) );
		add_action( 'customize_controls_enqueue_scripts', array( $this, 'customize_controls_enqueue_scripts' ) );
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
			array( 'jquery', 'jquery-core' ),
			ys_get_theme_version( true ),
			true
		);
		wp_enqueue_script(
			'ys-custom-uploader',
			get_template_directory_uri() . '/js/admin/custom-uploader.js',
			array( 'jquery', 'jquery-core' ),
			ys_get_theme_version( true ),
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
			'ys_admin_style',
			get_template_directory_uri() . '/css/ystandard-admin.css',
			array(),
			ys_get_theme_version( true )
		);
		wp_enqueue_style(
			'font-awesome',
			ys_get_font_awesome_css_url(),
			array(),
			'v5.11.2'
		);
		/**
		 * テーマ独自の設定ページ
		 */
		if ( false !== strpos( $hook_suffix, 'page_ys_settings' ) ) {
			wp_enqueue_style(
				'ys_settings_style',
				get_template_directory_uri() . '/css/ystandard-admin-settings.css'
			);
			wp_enqueue_style(
				'ys_settings_font',
				'https://fonts.googleapis.com/css?family=Orbitron'
			);
		}
		/**
		 * Widget
		 */
		if ( 'widgets.php' === $hook_suffix ) {
			wp_enqueue_style(
				'ys-admin-widget',
				get_template_directory_uri() . '/css/ystandard-admin-widget.css'
			);
		}
	}

	/**
	 * ビジュアルエディタ用CSS追加
	 */
	public function enqueue_visual_editor_styles() {
		/**
		 * ビジュアルエディターへのCSSセット
		 */
		add_editor_style( get_template_directory_uri() . '/css/ystandard-tiny-mce-style.css' );
		add_editor_style( get_stylesheet_directory_uri() . '/style.css' );
		add_editor_style( ys_get_theme_file_uri( '/user-custom-editor-style.css' ) );
	}

	/**
	 * Enqueue block editor style
	 */
	public function enqueue_block_css() {
		add_theme_support( 'editor-styles' );
		add_editor_style( 'css/ystandard-admin-block.css' );
		add_editor_style( 'style.css' );
		add_editor_style( 'user-custom-editor-style.css' );
	}

	/**
	 * ブロックエディタのスタイル追加
	 */
	public function enqueue_block_editor_assets() {
		$scripts = ys_scripts();
		wp_enqueue_style(
			YS_Scripts_Config::CSS_HANDLE_DUMMY,
			get_template_directory_uri() . '/css/ystandard.css'
		);
		wp_add_inline_style(
			YS_Scripts_Config::CSS_HANDLE_DUMMY,
			YS_Inline_Css::get_font_css(
				array(
					'body .editor-styles-wrapper',
					'.editor-styles-wrapper .editor-post-title .editor-post-title__block .editor-post-title__input'
				)
			)
		);
		/**
		 * フォントサイズ
		 */
		wp_add_inline_style(
			YS_Scripts_Config::CSS_HANDLE_DUMMY,
			YS_Inline_Css::get_editor_font_size_css( '.editor-styles-wrapper' )
		);
		/**
		 * カラーパレット
		 */
		wp_add_inline_style(
			YS_Scripts_Config::CSS_HANDLE_DUMMY,
			YS_Inline_Css::get_editor_color_palette( '.editor-styles-wrapper' )
		);
	}

	/**
	 * TinyMCEに追加CSSを適用させる
	 *
	 * @param array $settings TinyMCE設定.
	 *
	 * @return array;
	 */
	public function tiny_mce_before_init( $settings ) {
		$scripts                   = ys_scripts();
		$settings['content_style'] = str_replace( '"', '\'', $scripts->minify( wp_get_custom_css() ) );

		return $settings;

	}

	/**
	 * 管理画面-テーマカスタマイザーページでのスタイルシートの読み込み
	 *
	 * @param string $hook_suffix suffix.
	 *
	 * @return void
	 */
	public function customize_controls_print_styles( $hook_suffix ) {
		wp_enqueue_style(
			'ys_customizer_style',
			get_template_directory_uri() . '/css/ystandard-customizer.css',
			array(),
			ys_get_theme_version( true )
		);
	}

	/**
	 * テーマカスタマイザー用JS
	 *
	 * @return void
	 */
	public function customize_controls_enqueue_scripts() {
		wp_enqueue_script(
			'ys_customize_controls_js',
			get_template_directory_uri() . '/js/admin/customizer-control.js',
			array( 'customize-controls', 'jquery' ),
			ys_get_theme_version( true ),
			true
		);
	}

	/**
	 * テーマカスタマイザープレビュー用JS
	 *
	 * @return void
	 */
	public function customize_preview_init() {
		wp_enqueue_script(
			'ys_customize_preview_js',
			get_template_directory_uri() . '/js/admin/customizer-preview.js',
			array( 'customize-controls', 'jquery' ),
			date_i18n( 'YmdHis' ),
			true
		);
	}
}
