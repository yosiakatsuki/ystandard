<?php
/**
 * 文字列関連
 *
 * @package ystandard
 * @author  yosiakatsuki
 * @license GPL-3.0+
 */

namespace ystandard\utils;

defined( 'ABSPATH' ) || die();

/**
 * Class Text
 *
 * @package ystandard
 */
class Text {

	/**
	 * HTML・改行・ショートコードなしのテキストを取得
	 *
	 * @param string $content content.
	 *
	 * @return string
	 */
	public static function get_plain_text( $content ) {
		// ショートコード削除.
		$content = strip_shortcodes( $content );
		// HTMLタグ削除.
		$content = wp_strip_all_tags( $content, true );
		// HTMLタグらしき文字の変換.
		$content = htmlspecialchars( $content, ENT_QUOTES );

		return $content;
	}

	/**
	 * 改行削除
	 *
	 * @param string $text Text.
	 *
	 * @return string
	 */
	public static function remove_nl( $text ) {
		return str_replace(
			[
				"\r\n",
				"\r",
				"\n",
			],
			'',
			$text
		);
	}

	/**
	 * タブ削除
	 *
	 * @param string $text Text.
	 *
	 * @return string
	 */
	public static function remove_tab( $text ) {
		return str_replace( [ "\t" ], '', $text );
	}

	/**
	 * スペース削除
	 *
	 * @param string $text Text.
	 *
	 * @return string
	 */
	public static function remove_space( $text ) {
		return str_replace(
			[
				' ',
				'　',
			],
			'',
			$text
		);
	}

	/**
	 * まとめて削除
	 *
	 * @param string $text Text.
	 *
	 * @return string
	 */
	public static function remove_nl_tab_space( $text ) {
		return self::remove_nl( self::remove_tab( self::remove_space( $text ) ) );
	}
}
