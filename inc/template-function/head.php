<?php
/**
 * Headタグ関連の関数
 *
 * @package ystandard
 * @author  yosiakatsuki
 * @license GPL-3.0+
 */
defined( 'ABSPATH' ) || die();

/**
 * <head>タグにつける属性取得
 */
function ys_the_head_attr() {
	echo \ystandard\Head::get_head_attr();
}
