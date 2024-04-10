<?php
/**
 * バージョン情報関連の読み込み
 *
 * @package ystandard
 * @author  yosiakatsuki
 * @license GPL-3.0+
 */
defined( 'ABSPATH' ) || die();


/**
 * テーマバージョン取得
 *
 * @param boolean $parent 親テーマ情報かどうか.
 *
 * @return string
 */
function ys_get_theme_version( $parent = false ) {
	return \ystandard\Utility::get_theme_version( $parent );
}

/**
 * 親テーマ(yStandard) バージョン取得
 *
 * @param boolean $parent 親テーマ情報かどうか.
 *
 * @return string
 */
function ys_get_ystandard_version( $parent = false ) {
	return \ystandard\Utility::get_ystandard_version();
}
