<?php
/**
 * テンプレート関連の関数
 *
 * @package ystandard
 * @author  yosiakatsuki
 * @license GPL-3.0+
 */

defined( 'ABSPATH' ) || die();

/**
 * Front-pageでロードするテンプレート
 */
function ys_get_front_page_template() {
	return \ystandard\Front_Page::get_front_page_template();
}

/**
 * タイトル無しテンプレート判定
 */
function ys_is_no_title_template() {

	return \ystandard\Template_Type::is_no_title_template();
}

/**
 * モバイル判定
 */
function ys_is_mobile() {
	return \ystandard\utils\Conditional_Tags::is_mobile();
}
