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
 * フッターコンテンツが有効か
 *
 * @return bool
 */
function ys_is_active_footer_main_contents() {
	return \ystandard\Footer::is_active_main_contents();
}

/**
 * フッターナビゲーションが有効か
 *
 * @return bool
 */
function ys_is_active_footer_nav() {
	return \ystandard\Footer::is_active_footer_nav();
}

/**
 * フッターウィジェットが有効か
 *
 * @return bool
 */
function ys_is_active_footer_widgets() {
	return \ystandard\Footer::is_active_widget();
}

/**
 * サブフッターコンテンツ取得
 *
 * @return string
 */
function ys_get_sub_footer_contents() {
	return \ystandard\Footer::get_footer_sub_contents();
}

/**
 * フッターコピーライト表示
 *
 * @return void
 */
function ys_the_copyright() {
	echo wp_kses_post( \ystandard\Copyright::get_site_info() );
}
