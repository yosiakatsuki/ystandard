<?php
/**
 * Helper : Path
 *
 * @package ystandard
 * @author  yosiakatsuki
 * @license GPL-2.0+
 */

namespace ystandard\helper;

defined( 'ABSPATH' ) || die();

/**
 * Path
 */
class Path {

	/**
	 * テーマパスをテーマURLに変換
	 *
	 * @param string  $path     Path.
	 * @param boolean $is_child 子テーマか.
	 *
	 * @return string
	 */
	public static function replace_template_path_to_uri( $path, $is_child = false ) {
		$replace = str_replace(
			get_template_directory(),
			get_template_directory_uri(),
			$path
		);
		if ( $is_child ) {
			$replace = self::replace_stylesheet_path_to_uri( $path );
		}

		return $replace;
	}

	/**
	 * テーマパスをテーマURLに変換
	 *
	 * @param string $path Path.
	 *
	 * @return string
	 */
	public static function replace_stylesheet_path_to_uri( $path ) {
		return str_replace(
			get_stylesheet_directory(),
			get_stylesheet_directory_uri(),
			$path
		);
	}
}
