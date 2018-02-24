<?php
/**
 * テーマ更新確認
 */
if( is_admin() ) {
	require_once get_template_directory() . '/library/theme-update-checker/theme-update-checker.php';
	/**
	 * アップデートのチェック
	 */
	function ys_update_check() {
		$theme_update_checker = new ThemeUpdateChecker(
																	'ystandard',
																	'https://wp-ystandard.com/download/ystandard/ystandard-info.json'
																);
	}
	add_action( 'after_setup_theme', 'ys_update_check' );
}