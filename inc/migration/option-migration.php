<?php
/**
 * 変更対応
 *
 * @package ystandard
 * @author  yosiakatsuki
 * @license GPL-2.0+
 */

/**
 * 設定値の移行
 */
function ys_migration_options() {

	/**
	 * デザイン - ヘッダー設定
	 */
	$option = ys_get_option( 'ys_design_header_type' );
	if ( '1row' == $option ) {
		update_option( 'ys_design_header_type', 'row1' );
	}
	if ( '2row' == $option ) {
		update_option( 'ys_design_header_type', 'row2' );
	}
}
add_action( 'after_setup_theme', 'ys_migration_options' );
