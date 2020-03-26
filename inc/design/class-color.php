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
