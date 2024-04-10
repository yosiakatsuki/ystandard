<?php
/**
 * サニタイズ関連
 *
 * @package ystandard
 * @author  yosiakatsuki
 * @license GPL-3.0+
 */

namespace ystandard\utils;

defined( 'ABSPATH' ) || die();

/**
 * Class Sanitize
 *
 * @package ystandard
 */
class Sanitize {
	/**
	 * サニタイズで許可するHTML属性を取得
	 *
	 * @param array $tags HTML Tags.
	 * @param string $context Context.
	 *
	 * @return array
	 */
	public static function get_kses_allowed_html( $tags, $context = 'post' ) {
		$allowed_html = wp_kses_allowed_html( $context );
		$result       = [];
		foreach ( $tags as $tag ) {
			$result[ $tag ] = isset( $allowed_html[ $tag ] ) ? $allowed_html[ $tag ] : [];
		}

		return $result;
	}
}
