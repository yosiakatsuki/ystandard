<?php
/**
 * 設定関連の読み込み
 *
 * @package ystandard
 * @author  yosiakatsuki
 * @license GPL-3.0+
 */
defined( 'ABSPATH' ) || die();

/**
 * 設定取得
 *
 * @param string $name option key.
 * @param mixed $default デフォルト値.
 * @param mixed $type 取得する型.
 *
 * @return mixed
 */
function ys_get_option( $name, $default = false, $type = false ) {
	return \ystandard\Option::get_option( $name, $default, $type );
}

/**
 * 設定取得(bool)
 *
 * @param string $name option key.
 * @param mixed $default デフォルト値.
 *
 * @return mixed
 */
function ys_get_option_by_bool( $name, $default = false ) {
	return \ystandard\Option::get_option_by_bool( $name, $default );
}

/**
 * 設定取得(int)
 *
 * @param string $name option key.
 * @param mixed $default デフォルト値.
 *
 * @return mixed
 */
function ys_get_option_by_int( $name, $default = 0 ) {
	return \ystandard\Option::get_option_by_int( $name, $default );
}
