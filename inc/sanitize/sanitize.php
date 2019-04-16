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
 * @return bool
 */
function ys_sanitize_checkbox( $value ) {
	if ( true === $value || 'true' === $value || 1 === $value || '1' === $value ) {
		return true;
	} else {
		return false;
	}
}