<?php
/**
 * Helper : URL
 *
 * @package ystandard
 * @author  yosiakatsuki
 * @license GPL-2.0+
 */

namespace ystandard\helper;

defined( 'ABSPATH' ) || die();

/**
 * Class : Helper URL
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
}
