<?php
/**
 * 管理画面通知
 *
 * @package ystandard
 * @author  yosiakatsuki
 * @license GPL-2.0+
 */

namespace ystandard;

defined( 'ABSPATH' ) || die();

/**
 * Class Notice
 *
 * @package ystandard
 */
class Notice {

	/**
	 * アクション名
	 */
	const ACTION = 'admin_notices';

	/**
	 * アクションのセット
	 *
	 * @param string|array $function      Function.
	 * @param int          $priority      Priority.
	 * @param int          $accepted_args Args.
	 */
	public static function set_notice( $function, $priority = 10, $accepted_args = 1 ) {
		add_action( self::ACTION, $function, $priority, $accepted_args );
	}

	/**
	 * 管理画面通知
	 *
	 * @param string $content notice content.
	 * @param string $type    type.
	 */
	public static function notice( $content, $type = 'error' ) {
		echo "<div class=\"notice notice-${type} is-dismissible\">${content}</div>";
	}

	/**
	 * 管理画面通知 - 完了
	 *
	 * @param string $content notice content.
	 */
	public static function success( $content ) {
		self::notice( $content, 'success' );
	}

	/**
	 * 管理画面通知 - お知らせ
	 *
	 * @param string $content notice content.
	 */
	public static function info( $content ) {
		self::notice( $content, 'info' );
	}

	/**
	 * 管理画面通知 - 警告
	 *
	 * @param string $content notice content.
	 */
	public static function warning( $content ) {
		self::notice( $content, 'warning' );
	}

	/**
	 * 管理画面通知 - エラー
	 *
	 * @param string $content notice content.
	 */
	public static function error( $content ) {
		self::notice( $content, 'error' );
	}

	/**
	 * 管理画面通知 - マニュアル
	 *
	 * @param string $content notice content.
	 */
	public static function manual( $content ) {
		echo "<div class=\"notice notice-manual\">${content}</div>";
	}

	/**
	 * 管理画面通知 - 装飾なし
	 *
	 * @param string $content notice content.
	 */
	public static function plain( $content ) {
		echo "<div class=\"notice\">${content}</div>";
	}

}
