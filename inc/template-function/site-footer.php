<?php
/**
 * フッター関連の読み込み
 *
 * @package ystandard
 * @author  yosiakatsuki
 * @license GPL-3.0+
 */
defined( 'ABSPATH' ) || die();

/**
 * フッターウィジェットが有効か
 */
function ys_is_active_footer_widgets() {
	return \ystandard\Footer::is_active_widget();
}

/**
 * サブフッターコンテンツ取得
 */
function ys_get_sub_footer_contents() {
	return \ystandard\Footer::get_footer_sub_contents();
}

/**
 * フッターコピーライト表示
 *
 * @return void
 */
function ys_the_footer_site_info() {
	echo \ystandard\Copyright::get_site_info();
}
