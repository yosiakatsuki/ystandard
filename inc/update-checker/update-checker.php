<?php
/**
 * テーマ更新確認
 *
 * @package ystandard
 * @author  yosiakatsuki
 * @license GPL-2.0+
 */

/**
 * テーマ更新確認
 */
require_once get_template_directory() . '/library/theme-update-checker/theme-update-checker.php';
/**
 * アップデートのチェック
 */
function ys_update_check() {
	$ver = 'v2';
	if ( ys_sanitize_checkbox( ys_get_option( 'ys_admin_upgrade_v3' ) ) ) {
		$ver = 'v3';
	}
	$theme_update_checker = new ThemeUpdateChecker(
		'ystandard',
		sprintf(
			'https://wp-ystandard.com/download/ystandard/%s/ystandard-info.json',
			$ver
		)
	);
}

add_action( 'after_setup_theme', 'ys_update_check' );