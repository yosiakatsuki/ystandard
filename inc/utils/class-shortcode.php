<?php
/**
 * ショートコード関連
 *
 * @package ystandard
 * @author  yosiakatsuki
 * @license GPL-3.0+
 */

namespace ystandard\utils;

defined( 'ABSPATH' ) || die();

/**
 * Class Short_Code
 *
 * @package ystandard
 */
class Short_Code {
	/**
	 * ショートコードの作成と実行
	 *
	 * @param string $name ショートコード名.
	 * @param array $args パラメーター.
	 * @param mixed $content コンテンツ.
	 * @param bool $echo 出力.
	 *
	 * @return string
	 */
	public static function do_shortcode( $name, $args = [], $content = null, $echo = true ) {
		$atts = [];
		/**
		 * パラメーター展開
		 */
		if ( ! empty( $args ) ) {
			foreach ( $args as $key => $value ) {
				if ( is_array( $value ) ) {
					$value = implode( ',', $value );
				}
				$atts[] = sprintf(
					'%s="%s"',
					$key,
					$value
				);
			}
		}
		$atts = empty( $atts ) ? '' : ' ' . implode( ' ', $atts );
		/**
		 * ショートコード作成
		 */
		if ( null === $content ) {
			// コンテンツなし.
			$shortcode = sprintf( '[%s%s]', $name, $atts );
		} else {
			// コンテンツあり.
			$shortcode = sprintf( '[%s%s]%s[/%s]', $name, $atts, $content, $name );
		}
		$result = do_shortcode( $shortcode );
		// 表示 or 取得
		if ( $echo ) {
			echo $result;

			return '';
		} else {
			return $result;
		}
	}
}
