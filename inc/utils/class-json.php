<?php
/**
 * JSON関連
 *
 * @package ystandard
 * @author  yosiakatsuki
 * @license GPL-3.0+
 */

namespace ystandard\utils;

defined( 'ABSPATH' ) || die();

/**
 * Class JSON
 *
 * @package ystandard
 */
class JSON {
	/**
	 * JSON-LD出力
	 *
	 * @param array $data Data.
	 */
	public static function the_json_ld( $data = [] ) {
		if ( ! is_array( $data ) || empty( $data ) ) {
			return;
		}
		$encoded = wp_json_encode( $data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT );
		echo "<script type=\"application/ld+json\">{$encoded}</script>" . PHP_EOL;
	}
}
