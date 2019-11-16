<?php
/**
 * スクリプトの読み込み関連
 *
 * @package ystandard
 * @author  yosiakatsuki
 * @license GPL-2.0+
 */

require_once dirname( __FILE__ ) . '/class-ys-scripts.php';
require_once dirname( __FILE__ ) . '/enqueue-admin.php';
require_once dirname( __FILE__ ) . '/enqueue-customizer.php';
require_once dirname( __FILE__ ) . '/enqueue-customizer-color.php';
require_once dirname( __FILE__ ) . '/enqueue-customizer-custom-header.php';
require_once dirname( __FILE__ ) . '/enqueue-theme-option-css.php';
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

/**
 * Onload-scriptのセット
 *
 * @param [type]  $id id.
 * @param [type]  $src src.
 * @param boolean $ver version.
 *
 * @return void
 */
function ys_enqueue_onload_script( $id, $src, $ver = false ) {
	$scripts = ys_scripts();
	$scripts->set_onload_script( $id, $src, $ver );
}

/**
 * Lazyload-scriptのセット
 *
 * @param string  $id  id.
 * @param string  $src src.
 * @param boolean $ver version.
 *
 * @return void
 */
function ys_enqueue_lazyload_script( $id, $src, $ver = false ) {
	$scripts = ys_scripts();
	$scripts->set_lazyload_script( $id, $src, $ver );
}