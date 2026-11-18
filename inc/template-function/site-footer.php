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
 * フッターナビゲーションが有効か
 *
 * @return bool
 */
function ys_is_active_footer_nav() {
	return \ystandard\Footer::is_active_footer_nav();
}

/**
 * フッターテンプレートパーツを表示するか
 *
 * @return bool
 */
function ys_is_active_footer_template_part() {
	return \ystandard\Footer::is_active_template_part();
}

/**
 * フッターコピーライト表示
 *
 * @return void
 */
function ys_the_copyright() {
	echo wp_kses_post( \ystandard\Copyright::get_site_info() );
}
