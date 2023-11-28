<?php
/**
 * HEAD関連
 *
 * @package ystandard
 * @author  yosiakatsuki
 * @license GPL-2.0+
 */

defined( 'ABSPATH' ) || die();

/**
 * <head>タグにつける属性取得
 */
function ys_the_head_attr() {
	echo esc_attr( \ystandard\Head::get_head_attr() );
}
