<?php
/**
 * 投稿タイプ
 *
 * @package ystandard
 * @author  yosiakatsuki
 * @license GPL-3.0+
 */

namespace ystandard\utils;

defined( 'ABSPATH' ) || die();

/**
 * Class Post_Type
 *
 * @package ystandard\utils
 */
class Post_Type {

	/**
	 * 投稿タイプを取得.
	 *
	 * @return false|string
	 * @global \WP_Query
	 */
	public static function get_post_type() {
		global $wp_query;
		$post_type = get_post_type();
		// get_post_type が取得できない場合はクエリから取得.
		if ( ! $post_type ) {
			if ( isset( $wp_query->query['post_type'] ) ) {
				$post_type = $wp_query->query['post_type'];
			}
		}

		return $post_type;
	}

	/**
	 * 投稿タイプ取得
	 *
	 * @param array $args args.
	 * @param bool $exclude 除外.
	 *
	 * @return array
	 */
	public static function get_post_types( $args = [], $exclude = true ) {
		$args = array_merge(
			[ 'public' => true ],
			$args
		);

		$types = get_post_types( $args );

		if ( is_array( $exclude ) ) {
			$exclude[] = 'attachment';
			foreach ( $exclude as $item ) {
				unset( $types[ $item ] );
			}
		}

		if ( true === $exclude ) {
			unset( $types['attachment'] );
		}

		foreach ( $types as $key => $value ) {
			$post_type = get_post_type_object( $key );
			if ( $post_type ) {
				$types[ $key ] = $post_type->labels->singular_name;
			}
		}

		return $types;
	}

	/**
	 * 投稿タイプ別の設定が見つからなかったときに仮でどのタイプのページとして振る舞うかを取得.
	 *
	 * @param string $post_type Post type.
	 *
	 * @return string
	 */
	public static function get_fallback_post_type( $post_type ) {

		if ( in_array( $post_type, [ 'post', 'page', 'attachment' ], true ) ) {
			return $post_type;
		}

		return is_post_type_hierarchical( $post_type ) ? 'page' : 'post';
	}
}
