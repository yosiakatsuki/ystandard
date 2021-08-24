<?php
/**
 * Text
 *
 * @package ystandard
 * @author  yosiakatsuki
 * @license GPL-2.0+
 */

namespace ystandard\helper;

defined( 'ABSPATH' ) || die();

/**
 * Class Text
 *
 * @package ystandard
 */
class Text {

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
		return str_replace(
			[
				"\t",
			],
			'',
			$text
		);
	}
}
