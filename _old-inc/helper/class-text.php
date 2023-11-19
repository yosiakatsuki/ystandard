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
