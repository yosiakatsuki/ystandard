<?php
/**
 * @package ystandard
 * @author yosiakatsuki
 * @license GPL-2.0+
 */
/**
 * サニタイズ関連
 */
function ys_sanitize_checkbox( $value ) {
	if ( $value == true || $value === 'true' ) {
		return true;
	} else {
		return false;
	}
}