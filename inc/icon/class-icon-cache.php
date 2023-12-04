<?php
/**
 * アイコン
 *
 * @package ystandard
 * @author  yosiakatsuki
 * @license GPL-3.0+
 */

namespace ystandard;

use ystandard\utils\File;
use ystandard\utils\Cache;
use ystandard\utils\Version;

class Icon_Cache {
	/**
	 * キャッシュキープレフィックス（実際のキーはバージョン情報が入る）
	 */
	const CACHE_KEY_PREFIX = 'ys_icons_cache_';
	/**
	 * オブジェクトキャッシュキー
	 */
	const OBJECT_CACHE_KEY = 'ys_icons';

	/**
	 * キャッシュの有効期限
	 */
	const EXPIRATION = 30;


	/**
	 * キャッシュからアイコン取得
	 *
	 * @param string $name Name.
	 *
	 * @return string|bool
	 */
	public static function get_icon_cache( $name ) {
		if ( apply_filters( 'ys_disable_icon_cache', false ) ) {
			return false;
		}
		$icons = self::get_cache_all_icons();
		if ( false === $icons ) {
			return false;
		}
		if ( ! isset( $icons['icon'] ) ) {
			return false;
		}
		if ( ! isset( $icons['icon'][ $name ] ) ) {
			return false;
		}

		return $icons['icon'][ $name ];
	}

	/**
	 * キャッシュからSNSアイコン取得
	 *
	 * @param string $name Name.
	 *
	 * @return string|bool
	 */
	public static function get_sns_icon_cache( $name ) {
		// キャッシュから取得.
		$icons = self::get_cache_all_icons();
		if ( isset( $icons['sns'][ $name ] ) ) {
			return $icons['sns'][ $name ];
		}
		// SNSアイコン定義ファイルから取得.
		$icons = self::get_all_sns_icons();
		if ( isset( $icons[ $name ] ) ) {
			return $icons[ $name ];
		}

		return false;
	}


	/**
	 * 全SNSアイコン取得
	 *
	 * @return array
	 */
	public static function get_all_sns_icons() {
		// キャッシュから取得.
		$cache = self::get_cache_all_icons();
		if ( isset( $cache['sns'] ) && ! empty( $cache['sns'] ) ) {
			return $cache['sns'];
		}
		// ファイルから取得.
		$icons = [];
		$path  = get_template_directory() . '/library/simple-icons/brand-icons.json';
		$data  = File::get_json_contents( $path );

		// 取得した内容を整理・キャッシュにセット.
		if ( ! empty( $data ) ) {
			foreach ( $data['data'] as $key => $value ) {
				$icons[ $key ] = $value;
			}
			if ( ! empty( $icons ) ) {
				self::add_sns_icon_cache( $icons );
			}
		}

		return $icons;
	}


	/**
	 * キャッシュから全アイコンを取得
	 *
	 * @return array|bool
	 */
	public static function get_cache_all_icons() {
		// オブジェクトキャッシュから取得.
		$icons = wp_cache_get( self::OBJECT_CACHE_KEY );
		if ( false !== $icons ) {
			return $icons;
		}
		// transientから取得.
		$icons = Cache::get_cache( self::get_cache_key() );

		// オブジェクトキャッシュに追加.
		if ( false !== $icons ) {
			wp_cache_add( self::OBJECT_CACHE_KEY, $icons );
		}

		return $icons;
	}

	/**
	 * キャッシュのキーを取得
	 *
	 * @return string
	 */
	public static function get_cache_key() {
		// キャッシュはテーマバージョンごとに作り直し
		return self::CACHE_KEY_PREFIX . Version::get_version();
	}


	/**
	 * アイコンのキャッシュセット
	 *
	 * @param string $name icon name.
	 * @param string $icon icon.
	 */
	public static function add_icon_cache( $name, $icon ) {
		if ( apply_filters( 'ys_disable_icon_cache', false ) ) {
			return;
		}
		// 登録済みキャッシュの取得.
		$cache = self::get_cache_all_icons();
		if ( self::is_empty( $cache ) ) {
			$cache = self::get_schema();
		}
		// アイコン用キャッシュのセット.
		if ( ! isset( $cache['icon'] ) ) {
			$cache['icon'] = [];
		}
		$cache['icon'][ $name ] = $icon;

		// キャッシュの再設定.
		Cache::delete_cache( self::get_cache_key() );
		Cache::set_cache( self::get_cache_key(), $cache, [], self::EXPIRATION, true );
	}

	/**
	 * SNSアイコンのキャシュセット
	 *
	 * @param array $icons icons.
	 */
	public static function add_sns_icon_cache( $icons ) {
		// 登録済みキャッシュの取得.
		$cache = self::get_cache_all_icons();
		if ( self::is_empty( $cache ) ) {
			$cache = self::get_schema();
		}
		// アイコン用キャッシュのセット.
		$cache['sns'] = $icons;

		// キャッシュの再設定.
		Cache::delete_cache( self::get_cache_key() );
		Cache::set_cache( self::get_cache_key(), $cache, [], self::get_cache_key(), true );
	}

	/**
	 * アイコンの存在確認
	 *
	 * @param array $icons Icons.
	 *
	 * @return bool
	 */
	public static function is_empty( $icons ) {
		if ( empty( $icons ) ) {
			return true;
		}

		if ( ! isset( $icons['icon'] ) && ! isset( $icons['sns'] ) ) {
			return true;
		}
		if ( ! isset( $icons['icon'] ) ) {
			$icons['icon'] = [];
		}
		if ( ! isset( $icons['sns'] ) ) {
			$icons['sns'] = [];
		}
		if ( empty( $icons['icon'] ) && empty( $icons['sns'] ) ) {
			return true;
		}

		return false;
	}

	/**
	 * アイコンキャッシュ用配列取得
	 *
	 * @return array
	 */
	public static function get_schema() {
		return [
			'icon' => [],
			'sns'  => [],
		];
	}
}
