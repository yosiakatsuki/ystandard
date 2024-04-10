<?php
/**
 * ファイル操作系
 *
 * @package ystandard
 * @author  yosiakatsuki
 * @license GPL-3.0+
 */

namespace ystandard\utils;

defined( 'ABSPATH' ) || die();

/**
 * Class File
 *
 * @package ystandard
 */
class File {

	/**
	 * ファイル内容の取得
	 *
	 * @param string $path ファイルパス.
	 *
	 * @return string|false
	 */
	public static function file_get_contents( $path ) {

		try {
			if ( file_exists( $path ) ) {
				$content = file_get_contents( $path );
			} else {
				$content = false;
			}
		} catch ( \Exception $e ) {
			return false;
		}

		return $content;
	}

	/**
	 * JSONファイルの内容を取得
	 *
	 * @param string $path ファイルパス.
	 * @param bool $associative 配列で取得するか.
	 *
	 * @return false|array
	 */
	public static function get_json_contents( $path, $associative = true ) {
		if ( ! file_exists( $path ) ) {
			return false;
		}
		// JSONファイルの内容を取得（）
		$json = wp_json_file_decode( $path, [ 'associative' => $associative ] );
		if ( null === $json ) {
			return false;
		}

		return $json;
	}

}
