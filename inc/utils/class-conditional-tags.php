<?php
/**
 * 条件分岐系
 *
 * @package ystandard
 * @author  yosiakatsuki
 * @license GPL-3.0+
 */

namespace ystandard\utils;

defined( 'ABSPATH' ) || die();

class Conditional_Tags {

	/**
	 * モバイル判定
	 *
	 * @return bool
	 */
	public static function is_mobile() {

		$ua = [
			'^(?!.*iPad).*iPhone',
			'iPod',
			'Android.*Mobile',
			'Mobile.*Firefox',
			'Windows.*Phone',
			'blackberry',
			'dream',
			'CUPCAKE',
			'webOS',
			'incognito',
			'webmate',
		];

		$ua = apply_filters( 'ys_is_mobile_ua_list', $ua );

		return self::check_user_agent( $ua );
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


	/**
	 * ユーザーエージェントのチェック
	 *
	 * @param array $ua 対象ユーザーエージェントのリスト.
	 *
	 * @return boolean
	 */
	public static function check_user_agent( $ua ) {
		if ( ! isset( $_SERVER['HTTP_USER_AGENT'] ) ) {
			return false;
		}
		$pattern = '/' . implode( '|', $ua ) . '/i';

		return preg_match( $pattern, $_SERVER['HTTP_USER_AGENT'] );
	}

	/**
	 * ブロックエディターで動作しているか
	 *
	 * @return bool
	 */
	public static function is_block_editor() {
		if ( ! defined( 'REST_REQUEST' ) ) {
			return false;
		}

		return \REST_REQUEST;
	}
}
