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
	return \ystandard\Template::get_front_page_template();
}

/**
 * タイトル無しテンプレート判定
 */
function ys_is_no_title_template() {

	return \ystandard\Template::is_no_title_template();
}

/**
 * テンプレート読み込み拡張
 *
 * @param string $slug The slug name for the generic template.
 * @param string $name The name of the specialised template.
 * @param array $args テンプレートに渡す変数.
 */
function ys_get_template_part( $slug, $name = null, $args = [] ) {
	\ystandard\Template::get_template_part( $slug, $name, $args );
}

/**
 * モバイル判定
 */
function ys_is_mobile() {
	return \ystandard\Template::is_mobile();
}
