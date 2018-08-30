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
		get_template_directory_uri() . '/js/admin.bundle.js',
		array( 'jquery', 'jquery-core' ),
		ys_get_theme_version( true ),
		true
	);
}

add_action( 'admin_enqueue_scripts', 'ys_enqueue_admin_scripts' );


/**
 * 管理画面-スタイルシートの読み込み
 *
 * @param string $hook_suffix suffix.
 *
 * @return void
 */
function ys_admin_enqueue_scripts( $hook_suffix ) {
	wp_enqueue_style(
		'ys_admin_style',
		get_template_directory_uri() . '/css/admin/admin.min.css',
		array(),
		ys_get_theme_version( true )
	);
	/**
	 * テーマ独自の設定ページ
	 */
	if ( 'toplevel_page_ys_settings_start' === $hook_suffix ) {
		wp_enqueue_style(
			'ys_settings_style',
			get_template_directory_uri() . '/css/admin/ystandard-settings.min.css'
		);
		wp_enqueue_style(
			'ys_settings_font',
			'https://fonts.googleapis.com/css?family=Orbitron'
		);
		wp_enqueue_style(
			'font-awesome',
			'https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css',
			array(),
			''
		);
	}
}

add_action( 'admin_enqueue_scripts', 'ys_admin_enqueue_scripts' );