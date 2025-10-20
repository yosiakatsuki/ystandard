<?php
/**
 * 投稿・固定ページ・投稿タイプ サムネイル関連
 *
 * @package ystandard
 * @author  yosiakatsuki
 * @license GPL-2.0+
 */

namespace ystandard;

use ystandard\utils\Post_Type;

defined( 'ABSPATH' ) || die();

/**
 * Class Post_Singular_Thumbnail
 */
class Post_Singular_Thumbnail {
	/**
	 * インスタンス
	 *
	 * @var Post_Singular_Thumbnail
	 */
	private static $instance;

	/**
	 * インスタンス取得
	 *
	 * @return Post_Singular_Thumbnail
	 */
	public static function get_instance(): Post_Singular_Thumbnail {
		if ( null === self::$instance ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	/**
	 * Post_Singular_Thumbnail constructor.
	 */
	private function __construct() {

	}

	/**
	 * アイキャッチ画像を表示するか
	 *
	 * @param int|null $post_id 投稿ID.
	 *
	 * @return bool
	 */
	public static function is_active_post_thumbnail( int $post_id = null ): bool {
		$result = true;
		if ( ! is_singular() ) {
			return false;
		}
		if ( ! Post_Header::is_active_post_header() ) {
			return false;
		}
		if ( ! has_post_thumbnail( $post_id ) ) {
			$result = false;
		}
		$post_type = Post_Type::get_post_type();
		$filter    = apply_filters( "ys_show_{$post_type}_header_thumbnail", null );
		if ( is_null( $filter ) ) {
			$fallback = Post_Type::get_fallback_post_type( $post_type );
			$option   = Option::get_option_by_bool( "ys_show_{$fallback}_header_thumbnail", true );
		} else {
			$option = $filter;
		}
		if ( is_singular( $post_type ) ) {
			$result = ! $option ? false : $result;
		}

		return apply_filters( 'ys_is_active_post_thumbnail', $result );
	}

	/**
	 * フル幅サムネイル設定か
	 *
	 * @return bool
	 */
	public static function is_full_post_thumbnail() {
		$post_type = Post_Type::get_post_type();
		$filter    = apply_filters( "ys_{$post_type}_post_thumbnail_type", null );
		if ( is_null( $filter ) ) {
			$fallback = Post_Type::get_fallback_post_type( $post_type );
			$type     = Option::get_option( "ys_{$fallback}_post_thumbnail_type", 'default' );
		} else {
			$type = $filter;
		}

		if ( is_singular( $post_type ) && 'full' === $type ) {
			return true;
		}

		return false;
	}
}

Post_Singular_Thumbnail::get_instance();
