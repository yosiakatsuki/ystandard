<?php
/**
 * 広告関連の読み込み
 *
 * @package ystandard
 * @author  yosiakatsuki
 * @license GPL-3.0+
 */
defined( 'ABSPATH' ) || die();

/**
 * インフィード広告の表示
 */
function ys_the_ad_infeed() {
	echo \ystandard\Advertisement::get_infeed();
}
