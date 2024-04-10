<?php
/**
 * URL関連
 *
 * @package ystandard
 * @author  yosiakatsuki
 * @license GPL-3.0+
 */

namespace ystandard\utils;

defined( 'ABSPATH' ) || die();

/**
 * Class URL
 *
 * @package ystandard
 */
class URL {

	/**
	 * ページURL取得
	 *
	 * @return string
	 */
	public static function get_page_url() {
		$protocol = 'https://';
		if ( ! is_ssl() ) {
			$protocol = 'http://';
		}

		return $protocol . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
	}

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
		// 子テーマを使っていれば、stylesheet で変換.
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
