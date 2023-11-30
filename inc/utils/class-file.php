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
	 * @param string $file ファイルパス.
	 *
	 * @return string|false
	 */
	public static function file_get_contents( $file ) {

		try {
			if ( file_exists( $file ) ) {
				$content = file_get_contents( $file );
			} else {
				$content = false;
			}
		} catch ( \Exception $e ) {
			return false;
		}

		return $content;
	}

}
