<?php
/**
 * タクソノミー関連
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
class Taxonomy {
	/**
	 * タクソノミー取得
	 *
	 * @param array $args args.
	 * @param bool  $exclude 除外.
	 *
	 * @return array
	 */
	public static function get_taxonomies( $args = [], $exclude = true ) {
		$args       = array_merge(
			[ 'public' => true ],
			$args
		);
		$taxonomies = self::remove_exclude_taxonomies( get_taxonomies( $args ), $exclude );

		foreach ( $taxonomies as $key => $value ) {
			$taxonomy = get_taxonomy( $key );
			if ( $taxonomy ) {
				$taxonomies[ $key ] = $taxonomy->label;
			}
		}

		return $taxonomies;
	}

	/**
	 * タクソノミー情報から除外するタクソノミーを削除
	 *
	 * @param array $taxonomies タクソノミー.
	 * @param bool  $exclude 除外.
	 *
	 * @return array
	 */
	private static function remove_exclude_taxonomies( $taxonomies, $exclude ) {

		if ( ! is_array( $taxonomies ) ) {
			return $taxonomies;
		}

		if ( is_array( $exclude ) ) {
			foreach ( $exclude as $item ) {
				unset( $taxonomies[ $item ] );
			}
		} elseif ( true === $exclude ) {
			unset( $taxonomies['post_format'] );
		}

		return $taxonomies;
	}

	/**
	 * 投稿タイプに関連付けられたタクソノミーを取得
	 *
	 * @param string $post_type 投稿タイプ.
	 *
	 * @return array | bool
	 */
	public static function get_post_type_taxonomies( $post_type, $exclude = true ) {
		$taxonomies = get_object_taxonomies( $post_type, 'objects' );
		if ( ! $taxonomies ) {
			return [];
		}

		$public_taxonomies = [];
		foreach ( $taxonomies as $taxonomy ) {
			if ( $taxonomy->public ) {
				$public_taxonomies[ $taxonomy->name ] = $taxonomy;
			}
		}

		return empty( $public_taxonomies ) ? false : self::remove_exclude_taxonomies( $public_taxonomies, $exclude );

	}

	/**
	 * メタ情報として表示するタクソノミー取得
	 *
	 * @return bool|string
	 */
	public static function get_meta_taxonomy() {
		$taxonomies = get_the_taxonomies();
		if ( ! $taxonomies ) {
			return false;
		}

		$taxonomy = Compatible::array_key_first( $taxonomies );

		if ( 'post' === get_post_type( get_the_ID() ) ) {
			$taxonomy = 'category';
		}

		return $taxonomy;
	}
}
