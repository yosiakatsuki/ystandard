<?php
/**
 * カスタムヘッダー
 *
 * @package ystandard
 * @author  yosiakatsuki
 * @license GPL-2.0+
 */

namespace ystandard;

/**
 * Class Custom_Header
 *
 * @package ystandard
 */
class Custom_Header {

	/**
	 * カスタムヘッダーが有効か
	 *
	 * @return bool
	 */
	public static function is_active_custom_header() {

		if ( Template::is_top_page() || ys_get_option_by_bool( 'ys_wp_header_media_all_page', false ) ) {
			if ( self::has_custom_image() ) {
				return true;
			}
			if ( ys_get_option( 'ys_wp_header_media_shortcode', '' ) ) {
				return true;
			}
		}

		return false;
	}

	/**
	 * ヘッダー画像が有効か
	 *
	 * @return bool
	 */
	public static function has_custom_image() {
		if ( get_custom_header_markup() ) {
			return true;
		}

		return false;
	}
}
