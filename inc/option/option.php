<?php
/**
 * 設定取得
 *
 * @package ystandard
 * @author  yosiakatsuki
 * @license GPL-2.0+
 */

/**
 * クラスファイル
 */
require_once dirname( __FILE__ ) . '/class-option.php';

/**
 * 設定取得
 *
 * @param string $name    option key.
 * @param mixed  $default デフォルト値.
 * @param mixed  $type    取得する型.
 *
 * @return mixed
 */
function ys_get_option( $name, $default = false, $type = false ) {
	return \ystandard\Option::get_option( $name, $default, $type );
}

/**
 * 設定取得(bool)
 *
 * @param string $name    option key.
 * @param mixed  $default デフォルト値.
 *
 * @return mixed
 */
function ys_get_option_by_bool( $name, $default = false ) {
	return \ystandard\Option::get_option_by_bool( $name, $default );
}

/**
 * 設定取得(int)
 *
 * @param string $name    option key.
 * @param mixed  $default デフォルト値.
 *
 * @return mixed
 */
function ys_get_option_by_int( $name, $default = 0 ) {
	return \ystandard\Option::get_option_by_int( $name, $default );
}
