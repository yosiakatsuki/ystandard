<?php
/**
 * キャッシュ処理クラス
 *
 * @package ystandard
 * @author  yosiakatsuki
 * @license GPL-2.0+
 */

namespace ystandard;

defined( 'ABSPATH' ) || die();

/**
 * キャッシュ処理クラス
 */
class Cache {

	/**
	 * キャッシュプレフィックス
	 */
	const PREFIX = 'yscache_';

	/**
	 * キャッシュ作成に使用するキーを作成
	 *
	 * @param string $key  キー文字列.
	 * @param array  $args オプション.
	 *
	 * @return bool|string
	 */
	public static function get_cache_key( $key, $args ) {
		/**
		 * キー文字列の作成
		 */
		$cache_key = self::get_cache_key_prefix( $key, $args ) . md5( serialize( $args ) );
		$cache_key = apply_filters( 'ys_cache_get_cache_key', $cache_key, $key, $args );

		/**
		 * Transientとして有効な文字数45文字にカットして返す
		 */
		return substr( $cache_key, 0, 45 );
	}

	/**
	 * キャッシュキーのプレフィックス作成
	 *
	 * @param string $key  キー.
	 * @param array  $args オプション.
	 *
	 * @return string
	 */
	public static function get_cache_key_prefix( $key, $args ) {
		return apply_filters( 'ys_get_cache_key_prefix', self::PREFIX . $key, $key, $args );
	}

	/**
	 * キャッシュ機能を使ったクエリ取得
	 *
	 * @param string $key        キー.
	 * @param array  $args       オプション.
	 * @param mixed  $expiration 有効期限.
	 *
	 * @return mixed
	 */
	public static function get_query( $key, $args, $expiration ) {
		/**
		 * キャッシュ設定が有効な場合のみキャッシュの取得を試みる
		 */
		if ( is_numeric( $expiration ) && self::can_use_cache() ) {
			$cache_key = self::get_cache_key( $key, $args );
			/**
			 * キャッシュの取得・判定、キャッシュがあれば返す
			 */
			$cache = self::get_transient( $cache_key );
			if ( false !== $cache ) {
				return $cache;
			}
		}
		/**
		 * クエリの作成
		 */
		$query = new \WP_Query( $args );
		/**
		 * キャッシュがなければ作成する
		 */
		self::set_cache( $key, $query, $args, $expiration );

		return $query;
	}

	/**
	 * キャッシュを取得
	 *
	 * @param string $key  キー.
	 * @param array  $args オプション.
	 *
	 * @return mixed
	 */
	public static function get_cache( $key, $args ) {
		$cache_key = self::get_cache_key( $key, $args );

		return get_transient( $cache_key );
	}

	/**
	 * キャッシュ削除
	 *
	 * @param string $key  キー.
	 * @param array  $args オプション.
	 *
	 * @return bool
	 */
	public static function delete_cache( $key, $args ) {
		$cache_key = self::get_cache_key( $key, $args );

		return delete_transient( $cache_key );
	}

	/**
	 * キャッシュを取得
	 *
	 * @param string $key キー.
	 *
	 * @return mixed
	 */
	public static function get_transient( $key ) {
		return get_transient( $key );
	}

	/**
	 * クエリ結果のキャッシュを作成
	 *
	 * @param string $key        キー.
	 * @param mixed  $obj        キャッシュするオブジェクト.
	 * @param array  $args       オプション.
	 * @param mixed  $expiration キャッシュ作成する日数.
	 * @param bool   $force      強制的に作成するか.
	 *
	 * @return bool
	 */
	public static function set_cache( $key, $obj, $args, $expiration, $force = false ) {
		if ( ! is_numeric( $expiration ) ) {
			return false;
		}
		// キャッシュNGユーザーの場合、削除して抜ける.
		if ( ! self::can_use_cache() ) {
			self::delete_cache( $key, $args );

			// 強制的にキャッシュ作成する場合は抜けない.
			if ( false === $force ) {
				return false;
			}
		}
		/**
		 * キャッシュキー、有効期限を作成
		 */
		$expiration = self::get_expiration( (int) $expiration );
		$cache_key  = self::get_cache_key( $key, $args );

		return set_transient( $cache_key, $obj, $expiration );
	}

	/**
	 * キャッシュの有効日数を取得
	 *
	 * @param int $day 日数.
	 *
	 * @return float|int
	 */
	public static function get_expiration( $day = 1 ) {
		return $day * 60 * 60 * 24;
	}

	/**
	 * キャッシュ作成・取得できるユーザーかチェック
	 *
	 * @return bool
	 */
	public static function can_use_cache() {
		if ( ! is_user_logged_in() ) {
			return true;
		}
		if ( ! current_user_can( 'edit_posts' ) ) {
			return true;
		}

		return false;
	}

}
