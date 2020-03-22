<?php
/**
 * 色管理 クラス
 *
 * @package ystandard
 * @author  yosiakatsuki
 * @license GPL-2.0+
 */

namespace ystandard;

/**
 * Class YS_Color
 */
class Color {

	/**
	 * サイト背景色デフォルト値取得
	 *
	 * @return string
	 */
	public static function get_site_bg_default() {
		return Option::get_default( 'ys_color_site_bg', '#ffffff' );
	}

	/**
	 * サイト背景色取得
	 *
	 * @return string
	 */
	public static function get_site_bg() {
		return ys_get_option( 'ys_color_site_bg', self::get_site_bg_default() );
	}

	/**
	 * カスタム背景色を使っているか
	 *
	 * @return bool
	 */
	public static function is_custom_bg_color() {
		return Color::get_site_bg_default() !== Color::get_site_bg();
	}

	/**
	 * カスタムヘッダーのヘッダーを重ねるタイプ文字色取得
	 *
	 * @return string
	 */
	public static function get_custom_header_stack_text_color() {
		$text_color = '#fff';
		if ( 'light' === ys_get_option( 'ys_wp_header_media_full_type', 'dark' ) ) {
			$text_color = '#222';
		}

		return $text_color;
	}

	/**
	 * カスタムヘッダーのヘッダーを重ねるタイプ背景色取得
	 *
	 * @param string $opacity 不透明度.
	 *
	 * @return string
	 */
	public static function get_custom_header_stack_bg_color( $opacity ) {
		$bg_color = 'rgba(0,0,0,' . $opacity . ')';
		if ( 'light' === ys_get_option( 'ys_wp_header_media_full_type', 'dark' ) ) {
			$bg_color = 'rgba(255,255,255,' . $opacity . ')';
		}

		return $bg_color;
	}

}
