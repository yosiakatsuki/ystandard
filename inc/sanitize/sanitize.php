<?php
/**
 * サニタイズ関連
 *
 * @package ystandard
 * @author yosiakatsuki
 * @license GPL-2.0+
 */

/**
 * チェックボックスのサニタイズ
 *
 * @param mixed $value 値.
 */
function ys_sanitize_checkbox( $value ) {
	if ( true == $value || 'true' === $value ) {
		return true;
	} else {
		return false;
	}
}