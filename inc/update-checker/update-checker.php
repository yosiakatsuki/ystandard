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
	$check_url = apply_filters(
		'ys_update_check_url',
		'https://wp-ystandard.com/download/ystandard/v3/ystandard-info.json'
	);

	/**
	 * アップグレード
	 */
	if ( '1' === get_option( 'ys_v4_upgrade', '0' ) ) {
		$check_url = 'https://wp-ystandard.com/download/ystandard/v4/ystandard-info.json';
	}
	/**
	 * アップデート確認
	 */
	$theme_update_checker = new ThemeUpdateChecker(
		'ystandard',
		$check_url
	);
}

add_action( 'after_setup_theme', 'ys_update_check' );
