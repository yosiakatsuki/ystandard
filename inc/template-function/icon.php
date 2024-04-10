<?php
/**
 * アイコン関連の読み込み
 *
 * @package ystandard
 * @author  yosiakatsuki
 * @license GPL-3.0+
 */
defined( 'ABSPATH' ) || die();


/**
 * アイコン取得
 *
 * @param string $name name.
 * @param string $class class.
 *
 * @return string
 */
function ys_get_icon( $name, $class = '' ) {
	return \ystandard\Icon::get_icon( $name, $class );
}

/**
 * SNSアイコン取得
 *
 * @param string $name name.
 * @param string $title title.
 *
 * @return string
 */
function ys_get_sns_icon( $name, $title = '' ) {
	return \ystandard\Icon::get_sns_icon( $name, $title );
}
