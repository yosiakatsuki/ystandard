<?php
/**
 * 管理画面メニュー追加
 *
 * @package ystandard
 * @author yosiakatsuki
 * @license GPL-2.0+
 */

/**
 * メニュー追加
 */
function ys_add_admin_menu() {
	add_menu_page(
		'yStandard',
		'yStandard',
		'manage_options',
		'ys_settings_start',
		'load_ys_settings_start',
		'',
		3
	);
	// 便利ツール.
	add_submenu_page(
		'ys_settings_start',
		'便利ツール',
		'便利ツール',
		'manage_options',
		'ys_tools',
		'load_ys_tools'
	);
}
add_action( 'admin_menu', 'ys_add_admin_menu' );
/**
 * スタートページ呼び出し
 */
function load_ys_settings_start() {
	include get_template_directory() . '/inc/theme-option/ys-setting-start.php';
}
/**
 * 便利ツール呼び出し
 */
function load_ys_tools() {
	include get_template_directory() . '/inc/theme-option/ys-tools.php';
}