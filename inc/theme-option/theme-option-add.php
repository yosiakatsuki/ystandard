<?php
/**
 * 管理画面メニュー追加
 *
 * @package ystandard
 * @author  yosiakatsuki
 * @license GPL-2.0+
 */

/**
 * メニュー追加
 */
function ys_add_admin_menu() {
	if ( apply_filters( 'disable_ystandard_admin_menu', false ) ) {
		return;
	}
	/**
	 * [yStandardメニューの追加]
	 */
	add_menu_page(
		'yStandard',
		'yStandard',
		'manage_options',
		'ys_settings_start',
		'ys_load_ys_settings_start',
		'',
		3
	);
	/**
	 * キャッシュメニューの追加
	 */
	add_submenu_page(
		'ys_settings_start',
		'キャッシュ管理',
		'キャッシュ管理',
		'manage_options',
		'ys_settings_cache',
		'ys_load_ys_settings_cache'
	);
}

add_action( 'admin_menu', 'ys_add_admin_menu' );
/**
 * スタートページ呼び出し
 */
function ys_load_ys_settings_start() {
	include get_template_directory() . '/inc/theme-option/ys-setting-start.php';
}

/**
 * キャッシュ管理ページ呼び出し
 */
function ys_load_ys_settings_cache() {
	include get_template_directory() . '/inc/theme-option/ys-setting-cache.php';
}
