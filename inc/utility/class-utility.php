<?php
/**
 * Utility
 *
 * @package ystandard
 * @author  yosiakatsuki
 * @license GPL-2.0+
 */

namespace ystandard;

/**
 * ユーティリティークラス
 */
class Utility {

	/**
	 * Twitter用JavaScript URL取得
	 *
	 * @return string
	 */
	public static function get_twitter_widgets_js() {
		return '//platform.twitter.com/widgets.js';
	}

	/**
	 * Twitter用JavaScript URL取得
	 *
	 * @return string
	 */
	public static function get_facebook_sdk_js() {
		return '//connect.facebook.net/ja_JP/sdk.js#xfbml=1&version=v5.0';
	}

	/**
	 * ファイル内容の取得
	 *
	 * @param string $file ファイルパス.
	 *
	 * @return string
	 */
	public static function file_get_contents( $file ) {
		$content = false;
		if ( ys_init_filesystem() ) {
			/**
			 * @global \WP_Filesystem_Direct $wp_filesystem ;
			 */
			global $wp_filesystem;
			$content = $wp_filesystem->get_contents( $file );
		}

		return $content;
	}

	/**
	 * Font Awesomeのバージョン取得
	 *
	 * @return string
	 */
	public static function get_font_awesome_version() {
		return 'v5.12.0';
	}

	/**
	 * ショートコードの作成と実行
	 *
	 * @param string $name    ショートコード名.
	 * @param array  $args    パラメーター.
	 * @param mixed  $content コンテンツ.
	 * @param bool   $echo    出力.
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
		/**
		 * 表示 or 取得
		 */
		if ( $echo ) {
			echo $result;

			return '';
		} else {
			return $result;
		}
	}

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

		return $content;
	}

	/**
	 * Boolに変換
	 *
	 * @param mixed $value 変換する値.
	 *
	 * @return bool
	 */
	public static function to_bool( $value ) {
		if ( true === $value || 'true' === $value || 1 === $value || '1' === $value ) {
			return true;
		} else {
			return false;
		}
	}

	/**
	 * [false]として判定できるか
	 *
	 * @param mixed $value 変換する値.
	 *
	 * @return bool
	 */
	public static function is_false( $value ) {
		if ( 'false' === $value || false === $value || 0 === $value || '0' === $value ) {
			return true;
		}

		return false;
	}

}
