<?php
/**
 * Embed関連の読み込み
 *
 * @package ystandard
 * @author  yosiakatsuki
 * @license GPL-3.0+
 */
defined( 'ABSPATH' ) || die();

/**
 * Embed用コンテンツ取得.
 */
function ys_embed_content() {
	$embed = new \ystandard\Embed();
	echo $embed->get_embed_content();
}
