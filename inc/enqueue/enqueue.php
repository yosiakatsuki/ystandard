<?php
/**
 * スクリプトの読み込み関連
 *
 * @package ystandard
 * @author  yosiakatsuki
 * @license GPL-2.0+
 */

require_once dirname( __FILE__ ) . '/class-ys-scripts.php';

/**
 * スクリプト関連のクラス準備
 *
 * @return YS_Scripts
 */
function ys_scripts() {
	global $ys_scripts;
	if ( ! ( $ys_scripts instanceof YS_Scripts ) ) {
		$ys_scripts = new YS_Scripts();
	}

	return $ys_scripts;
}

/**
 * スクリプト登録処理
 */
$ys_scripts = ys_scripts();
$ys_scripts->init();