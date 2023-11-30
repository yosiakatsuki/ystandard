<?php
/**
 * テーマパス関連
 *
 * @package ystandard
 * @author  yosiakatsuki
 * @license GPL-3.0+
 */

namespace ystandard\utils;

defined( 'ABSPATH' ) || die();

/**
 * Class Path
 *
 * @package ystandard
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
	public static function to_template_uri( $path, $is_child = false ) {
		$replace = str_replace(
			get_template_directory(),
			get_template_directory_uri(),
			$path
		);
		if ( $is_child ) {
			$replace = self::to_stylesheet_uri( $path );
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
	public static function to_stylesheet_uri( $path ) {
		return str_replace(
			get_stylesheet_directory(),
			get_stylesheet_directory_uri(),
			$path
		);
	}
}
