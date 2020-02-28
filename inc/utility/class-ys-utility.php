<?php
/**
 * Utility
 *
 * @package ystandard
 * @author  yosiakatsuki
 * @license GPL-2.0+
 */

/**
 * ユーティリティークラス
 */
class YS_Utility {

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
	 * 非推奨メッセージを表示する
	 *
	 * @param string $func    関数.
	 * @param string $since   いつから.
	 * @param string $comment コメント.
	 */
	public static function deprecated_comment( $func, $since, $comment = '' ) {
		if ( ! current_user_can( 'edit_posts' ) ) {
			return;
		}
		if ( ! defined( WP_DEBUG ) ) {
			return;
		}
		if ( false === WP_DEBUG ) {
			return;
		}
		$message = sprintf(
			'<span style="color:red"><code>%s</code>は%sで非推奨になった関数です。</span><br>' . PHP_EOL,
			$func,
			$since
		);
		if ( $comment ) {
			$message .= '<br><span style="color:#999">' . $comment . '</span><br>' . PHP_EOL;
		}
		echo $message;
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
			global $wp_filesystem;
			$content = $wp_filesystem->get_contents( $file );
		}

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
	 * Font Awesomeのバージョン取得
	 *
	 * @return string
	 */
	public static function get_font_awesome_version() {
		return 'v5.12.0';
	}
}
