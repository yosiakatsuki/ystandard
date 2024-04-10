<?php
/**
 * ヘッダーメディア関連の関数
 *
 * @package ystandard
 * @author  yosiakatsuki
 * @license GPL-3.0+
 */
defined( 'ABSPATH' ) || die();


/**
 * カスタムヘッダーが有効か
 *
 * @return bool
 */
function ys_is_active_header_media() {
	return \ystandard\Header_Media::is_active_header_media();
}

/**
 * カスタムヘッダータイプ
 *
 * @return string
 */
function ys_get_header_media_type() {
	return \ystandard\Header_Media::get_header_media_type();
}

/**
 * カスタムヘッダーの出力
 */
function ys_the_header_media_markup() {
	\ystandard\Header_Media::header_media_markup();
}
