<?php
/**
 * スクリプト・CSSの読み込み関連の関数
 *
 * @package ystandard
 * @author  yosiakatsuki
 * @license GPL-2.0+
 */

/**
 * CSSのセット
 *
 * @param string $handle Handle.
 * @param string $src    CSSのURL.
 * @param bool   $inline インライン読み込みするか.
 * @param array  $deps   deps.
 * @param bool   $ver    クエリストリング.
 * @param string $media  media.
 * @param bool   $minify Minifyするか.
 *
 * @return void
 */
function ys_enqueue_css( $handle, $src, $inline = true, $deps = array(), $ver = false, $media = 'all', $minify = true ) {
	$scripts = ys_scripts();
	$scripts->set_enqueue_style( $handle, $src, $inline, $deps, $ver, $media, $minify );
}

/**
 * インラインCSSのセット
 *
 * @param string $style  CSS.
 * @param bool   $minify Minifyするか.
 *
 * @return void
 */
function ys_enqueue_inline_css( $style, $minify = true ) {
	$scripts = ys_scripts();
	$scripts->set_inline_style( $style, $minify );
}

/**
 * Onload-scriptのセット
 *
 * @param [type]  $id id.
 * @param [type]  $src src.
 * @param boolean $ver version.
 *
 * @return void
 */
function ys_enqueue_onload_script( $id, $src, $ver = false ) {
	$scripts = ys_scripts();
	$scripts->set_onload_script( $id, $src, $ver );
}

/**
 * Lazyload-scriptのセット
 *
 * @param string  $id  id.
 * @param string  $src src.
 * @param boolean $ver version.
 *
 * @return void
 */
function ys_enqueue_lazyload_script( $id, $src, $ver = false ) {
	$scripts = ys_scripts();
	$scripts->set_lazyload_script( $id, $src, $ver );
}