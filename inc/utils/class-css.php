<?php
/**
 * CSS関連
 *
 * @package ystandard
 * @author  yosiakatsuki
 * @license GPL-3.0+
 */

namespace ystandard\utils;

defined( 'ABSPATH' ) || die();

/**
 * Class CSS
 *
 * @package ystandard
 */
class CSS {

	/**
	 * ブレークポイント
	 *
	 * @var array
	 */
	const BREAKPOINTS = [
		'mobile'  => 640,
		'tablet'  => 768,
		'desktop' => 1024,
		'wide'    => 1200,
	];

	/**
	 * ブレークポイントの単位
	 *
	 * @var string
	 */
	const BREAKPOINT_UNIT = 'px';

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
	 * ブレークポイントの取得
	 *
	 * @return array
	 */
	public static function get_breakpoints() {

		return apply_filters( 'ys_get_breakpoints', self::BREAKPOINTS );
	}

	/**
	 * ブレークポイントの単位を取得
	 *
	 * @return string
	 */
	public static function get_breakpoint_unit() {

		return apply_filters( 'ys_get_breakpoint_unit', self::BREAKPOINT_UNIT );
	}

	/**
	 * ブレークポイントのem計算のベースサイズ.
	 *
	 * @param string $type mobile/tablet/desktop.
	 *
	 * @return int|float
	 */
	public static function get_breakpoints_max_width_size( $type ) {
		$breakpoints = self::get_breakpoints();

		// チェック.
		if ( ! is_array( $breakpoints ) || ! array_key_exists( $type, $breakpoints ) ) {
			return 0;
		}

		// 定義にはmin側のサイズが入るので、maxの計算は-0.02する.
		$result = (int) $breakpoints[ $type ] - 0.02;

		return apply_filters( 'ys_get_breakpoints_max_width_size', $result, $type );
	}


	/**
	 * モバイル用ブレークポイントの追加.
	 *
	 * @param string $css CSS.
	 *
	 * @return string
	 */
	public static function add_media_query_mobile( $css ) {
		$breakpoints = self::get_breakpoints();
		$unit        = self::get_breakpoint_unit();
		if ( ! is_array( $breakpoints ) || ! array_key_exists( 'mobile', $breakpoints ) ) {
			return $css;
		}
		// @media max-width:... 側を取得.
		$max = self::get_breakpoints_max_width_size( $breakpoints['mobile'] );

		return "@media (max-width:{$max}{$unit}) {{$css}}";
	}

	/**
	 * モバイル以上サイズ用ブレークポイントの追加.
	 *
	 * @param string $css CSS.
	 *
	 * @return string
	 */
	public static function add_media_query_over_mobile( $css ) {
		$breakpoints = self::get_breakpoints();
		$unit        = self::get_breakpoint_unit();
		if ( ! is_array( $breakpoints ) || ! array_key_exists( 'mobile', $breakpoints ) ) {
			return $css;
		}
		$min = $breakpoints['mobile'];

		return "@media (min-width:{$min}{$unit}) {{$css}}";
	}

	/**
	 * タブレット用ブレークポイントの追加.
	 *
	 * @param string $css CSS.
	 *
	 * @return string
	 */
	public static function add_media_query_only_tablet( $css ) {
		$breakpoints = self::get_breakpoints();
		$unit        = self::get_breakpoint_unit();

		if ( ! is_array( $breakpoints ) ) {
			return $css;
		}
		if ( ! array_key_exists( 'mobile', $breakpoints ) || ! array_key_exists( 'desktop', $breakpoints ) ) {
			return $css;
		}
		// min-width,max-widthを取得.
		$min = $breakpoints['desktop'];
		$max = self::get_breakpoints_max_width_size( $breakpoints['desktop'] );

		return "@media (min-width:{$min}{$unit}) AND (max-width:{$max}{$unit}) {{$css}}";
	}

	/**
	 * デスクトップ用ブレークポイントの追加.
	 *
	 * @param string $css CSS.
	 *
	 * @return string
	 */
	public static function add_media_query_desktop( $css ) {
		$breakpoints = self::get_breakpoints();
		$unit        = self::get_breakpoint_unit();

		if ( ! is_array( $breakpoints ) || ! array_key_exists( 'desktop', $breakpoints ) ) {
			return $css;
		}
		// min-widthを取得.
		$min = $breakpoints['desktop'];

		return "@media (min-width:{$min}{$unit}) {{$css}}";
	}

	/**
	 * ワイド幅以上用ブレークポイントの追加.
	 *
	 * @param string $css CSS.
	 *
	 * @return string
	 */
	public static function add_media_query_wide( $css ) {
		$breakpoints = self::get_breakpoints();
		$unit        = self::get_breakpoint_unit();

		if ( ! is_array( $breakpoints ) || ! array_key_exists( 'wide', $breakpoints ) ) {
			return $css;
		}
		// min-widthを取得.
		$min = $breakpoints['wide'];

		return "@media (min-width:{$min}{$unit}) {{$css}}";
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


	/**
	 * 値に単位があるかチェックして、単位がなければpxを追加する
	 *
	 * @param string|int|float $value 値.
	 *
	 * @return string
	 */
	public static function check_and_add_unit( $value ) {
		// 空の値や null の場合はそのまま返す.
		if ( empty( $value ) && 0 !== $value && '0' !== $value ) {
			return $value;
		}

		// 文字列に変換.
		$value = (string) $value;

		// 数値のみかチェック（整数または小数）.
		if ( is_numeric( $value ) ) {
			// 数値のみの場合はpxを追加.
			return $value . 'px';
		}

		// 上記に該当しない場合はそのまま返す（例：auto、inherit、none など）.
		return $value;
	}
}
