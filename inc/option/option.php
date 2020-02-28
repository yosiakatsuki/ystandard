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
require_once dirname( __FILE__ ) . '/class-ys-option.php';

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
	return YS_Option::get_option( $name, $default, $type );
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
	return YS_Option::get_option_by_bool( $name, $default );
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
	return YS_Option::get_option_by_int( $name, $default );
}

/**
 * 設定リストの作成・取得とキャッシュ作成
 *
 * @return array
 */
function ys_get_options_and_create_cache() {
	return YS_Option::create_cache();
}
