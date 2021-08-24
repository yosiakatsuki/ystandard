<?php
/**
 * Style Sheet
 *
 * @package ystandard
 * @author  yosiakatsuki
 * @license GPL-2.0+
 */

namespace ystandard\helper;

defined( 'ABSPATH' ) || die();

/**
 * Class Style_Sheet
 *
 * @package ystandard
 */
class Style_Sheet {

	/**
	 * ブレークポイント
	 *
	 * @var array
	 */
	const BREAKPOINTS = [
		'sm' => 600,
		'md' => 769,
		'lg' => 1025,
	];

	/**
	 * CSSの圧縮
	 *
	 * @param string $style inline css styles.
	 *
	 * @return string
	 */
	public static function minify( $style ) {
		// コメント削除.
		$style = preg_replace( '#/\*[^!][^*]*\*+([^/][^*]*\*+)*/#', '', $style );
		// コロンの後の空白を削除する.
		$style = str_replace( ': ', ':', $style );
		// タブ、スペース、改行などを削除する.
		$style = str_replace( [ "\r\n", "\r", "\n", "\t", '  ', '    ' ], '', $style );

		return $style;
	}

	/**
	 * メディアクエリを追加
	 *
	 * @param string $css Styles.
	 * @param string $min Breakpoint.
	 * @param string $max Breakpoint.
	 *
	 * @return string
	 */
	public static function add_media_query( $css, $min = '', $max = '' ) {

		if ( ! array_key_exists( $min, self::BREAKPOINTS ) && ! array_key_exists( $max, self::BREAKPOINTS ) ) {
			return $css;
		}
		if ( array_key_exists( $min, self::BREAKPOINTS ) ) {
			$breakpoint = self::BREAKPOINTS[ $min ];
			$min        = "(min-width: ${breakpoint}px)";
		}
		if ( array_key_exists( $max, self::BREAKPOINTS ) ) {
			$breakpoint = self::BREAKPOINTS[ $max ] - 1;
			$max        = "(max-width: ${breakpoint}px)";
		}
		$breakpoint = $min . $max;
		if ( '' !== $min && '' !== $max ) {
			$breakpoint = $min . ' AND ' . $max;
		}

		if ( empty( $breakpoint ) ) {
			return $css;
		}

		return sprintf(
			'@media %s {%s}',
			$breakpoint,
			$css
		);
	}

	/**
	 * カラーコードをrgbに変換
	 *
	 * @param string $color カラーコード.
	 *
	 * @return array
	 */
	public static function hex_2_rgb( $color ) {
		return [
			hexdec( substr( $color, 1, 2 ) ),
			hexdec( substr( $color, 3, 2 ) ),
			hexdec( substr( $color, 5, 2 ) ),
		];
	}
}
