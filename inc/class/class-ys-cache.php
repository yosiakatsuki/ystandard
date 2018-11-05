<?php
/**
 * キャッシュ処理クラス
 *
 * @package ystandard
 * @author  yosiakatsuki
 * @license GPL-2.0+
 */

/**
 * キャッシュ処理クラス
 */
class YS_Cache {
	/**
	 * コンストラクタ
	 */
	public function __construct() {

	}

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
		$cache_key = $key . md5( serialize( $args ) );
		$cache_key = apply_filters( 'ys_cache_get_cache_key', $cache_key, $key, $args );

		/**
		 * Transientとして有効な文字数45文字にカットして返す
		 */
		return substr( $cache_key, 0, 45 );
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
		if ( is_numeric( $expiration ) ) {
			$cache_key = YS_Cache::get_cache_key( $key, $args );
			/**
			 * キャッシュの取得・判定、キャッシュがあれば返す
			 */
			$cache = YS_Cache::get_cache( $cache_key );
			if ( false !== $cache ) {
				return $cache;
			}
		}
		/**
		 * クエリの作成
		 */
		$query = new WP_Query( $args );
		/**
		 * キャッシュがなければ作成する
		 */
		YS_Cache::set_cache( $key, $query, $args, $expiration );

		return $query;
	}

	/**
	 * キャッシュを取得
	 *
	 * @param string $key キー.
	 *
	 * @return mixed
	 */
	public static function get_cache( $key ) {
		return get_transient( $key );
	}

	/**
	 * クエリ結果のキャッシュを作成
	 *
	 * @param string $key        キー.
	 * @param mixed  $obj        キャッシュするオブジェクト.
	 * @param array  $args       オプション.
	 * @param mixed  $expiration キャッシュ作成する日数.
	 *
	 * @return bool
	 */
	public static function set_cache( $key, $obj, $args, $expiration ) {
		if ( ! is_numeric( $expiration ) ) {
			return false;
		}
		/**
		 * キャッシュキー、有効期限を作成
		 */
		$expiration = YS_Cache::get_expiration( (int) $expiration );
		$cache_key  = YS_Cache::get_cache_key( $key, $args );

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

}
