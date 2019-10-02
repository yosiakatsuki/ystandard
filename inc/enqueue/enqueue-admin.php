<?php
/**
 * 管理画面用スクリプト・CSSの読み込み
 *
 * @package ystandard
 * @author  yosiakatsuki
 * @license GPL-2.0+
 */

/**
 * 管理画面-JavaScriptの読み込み
 *
 * @param string $hook_suffix suffix.
 *
 * @return void
 */
function ys_enqueue_admin_scripts( $hook_suffix ) {
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

add_action( 'admin_enqueue_scripts', 'ys_enqueue_admin_scripts' );


/**
 * 管理画面-スタイルシートの読み込み
 *
 * @param string $hook suffix.
 *
 * @return void
 */
function ys_admin_enqueue_scripts( $hook ) {
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
		''
	);
	/**
	 * テーマ独自の設定ページ
	 */
	if ( false !== strpos( $hook, 'page_ys_settings' ) ) {
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
	if ( 'widgets.php' === $hook ) {
		wp_enqueue_style(
			'ys-admin-widget',
			get_template_directory_uri() . '/css/ystandard-admin-widget.css'
		);
	}
}

add_action( 'admin_enqueue_scripts', 'ys_admin_enqueue_scripts' );


/**
 * ビジュアルエディタ用CSS追加
 */
function ys_add_editor_styles() {
	/**
	 * ビジュアルエディターへのCSSセット
	 */
	if ( ys_get_option( 'ys_admin_enable_tiny_mce_style' ) ) {
		add_editor_style( get_template_directory_uri() . '/css/ystandard-tiny-mce-style.css' );
		add_editor_style( get_stylesheet_directory_uri() . '/style.css' );
		add_editor_style( ys_get_theme_file_uri( '/user-custom-editor-style.css' ) );
	}
}

add_action( 'admin_init', 'ys_add_editor_styles' );


/**
 * Enqueue block editor style
 */
function ys_enqueue_block_css() {
	if ( ys_get_option( 'ys_admin_enable_block_editor_style' ) ) {
		add_theme_support( 'editor-styles' );
		add_editor_style(
			get_template_directory_uri() . '/css/ystandard-admin-block.css'
		);
		add_editor_style(
			get_stylesheet_directory_uri() . '/style.css'
		);
		add_editor_style(
			ys_get_theme_file_uri( '/user-custom-editor-style.css' )
		);
	}
}

add_action( 'after_setup_theme', 'ys_enqueue_block_css' );

/**
 * ブロックエディタのスタイル追加
 */
function ys_enqueue_block_editor_assets() {
	if ( ys_get_option( 'ys_admin_enable_block_editor_style' ) ) {
		$scripts = ys_scripts();
		wp_enqueue_style(
			YS_Scripts::CSS_HANDLE_DUMMY,
			get_template_directory_uri() . '/css/ystandard.css'
		);
		wp_add_inline_style(
			YS_Scripts::CSS_HANDLE_DUMMY,
			$scripts->get_editor_font_size_css( '.editor-styles-wrapper' )
		);
		wp_add_inline_style(
			YS_Scripts::CSS_HANDLE_DUMMY,
			$scripts->get_editor_color_palette( '.editor-styles-wrapper' )
		);
	}
}

add_action( 'enqueue_block_editor_assets', 'ys_enqueue_block_editor_assets' );

/**
 * TinyMCEに追加CSSを適用させる
 *
 * @param array $settings TinyMCE設定.
 *
 * @return array;
 */
function ys_tiny_mce_inline_style( $settings ) {
	/**
	 * ビジュアルエディターへのCSSセット
	 */
	if ( ys_get_option( 'ys_admin_enable_tiny_mce_style' ) ) {
		$settings['content_style'] = wp_get_custom_css();
	}

	return $settings;
}

add_filter( 'tiny_mce_before_init', 'ys_tiny_mce_inline_style' );