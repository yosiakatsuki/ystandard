<?php
/**
 * ヘッダー
 *
 * @package ystandard
 * @author  yosiakatsuki
 * @license GPL-3.0+
 */

defined( 'ABSPATH' ) || die();

/**
 * ヘッダーロゴ取得
 *
 * @return string;
 */
function ys_get_header_logo() {
	return \ystandard\Header::get_header_logo();
}
/**
 * ヘッダーロゴ出力
 *
 * @return string;
 */
function ys_the_header_logo() {
	echo ys_get_header_logo();
}

/**
 * サイトキャッチフレーズを取得
 */
function ys_the_blog_description() {
	echo \ystandard\Header::get_blog_description();
}
