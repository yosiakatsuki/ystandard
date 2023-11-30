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
	 * @param bool $exclude 除外.
	 *
	 * @return array
	 */
	public static function get_taxonomies( $args = [], $exclude = true ) {
		$args       = array_merge(
			[ 'public' => true ],
			$args
		);
		$taxonomies = get_taxonomies( $args );

		if ( is_array( $exclude ) ) {
			$exclude[] = 'post_format';
			foreach ( $exclude as $item ) {
				unset( $taxonomies[ $item ] );
			}
		}

		if ( true === $exclude ) {
			unset( $taxonomies['post_format'] );
		}

		foreach ( $taxonomies as $key => $value ) {
			$taxonomy = get_taxonomy( $key );
			if ( $taxonomy ) {
				$taxonomies[ $key ] = $taxonomy->label;
			}
		}

		return $taxonomies;
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

		$taxonomy = array_key_first( $taxonomies );

		if ( 'post' === get_post_type( get_the_ID() ) ) {
			$taxonomy = 'category';
		}

		return $taxonomy;
	}
}
