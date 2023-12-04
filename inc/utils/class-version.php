<?php
/**
 * バージョン情報
 *
 * @package ystandard
 * @author  yosiakatsuki
 * @license GPL-3.0+
 */

namespace ystandard\utils;

defined( 'ABSPATH' ) || die();

/**
 * Class Version
 *
 * @package ystandard\utils
 */
class Version {

	/**
	 * バージョン情報
	 *
	 * @var string
	 */
	private static $version = '';

	/**
	 * バージョン情報取得
	 *
	 * @return string
	 */
	public static function get_version() {
		if ( ! empty( self::$version ) ) {
			return self::$version;
		}
		$theme = wp_get_theme( get_template() );
		return self::$version = $theme->get( 'Version' );
	}
}
