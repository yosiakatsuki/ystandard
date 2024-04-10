<?php
/**
 * サイドバー関連の読み込み
 *
 * @package ystandard
 * @author  yosiakatsuki
 * @license GPL-3.0+
 */
defined( 'ABSPATH' ) || die();

/**
 * サイドバーを表示するか
 */
function ys_is_active_sidebar() {
	return \ystandard\Widget::is_active_sidebar();
}
