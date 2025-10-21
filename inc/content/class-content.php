<?php
/**
 * コンテンツ部分の処理
 *
 * @package ystandard
 * @author  yosiakatsuki
 * @license GPL-2.0+
 */

namespace ystandard;

use ystandard\utils\Convert;
use ystandard\utils\Post;
use ystandard\utils\Post_Type;

defined( 'ABSPATH' ) || die();

/**
 * Class Content
 *
 * @package ystandard
 */
class Content {

	/**
	 * アクション・フィルターの登録
	 */
	public function register() {

	}

	/**
	 * Post Type
	 *
	 * @return false|string
	 * @global \WP_Query
	 * @deprecated
	 */
	public static function get_post_type() {
		return Post_Type::get_post_type();
	}

	/**
	 * Fallback Post Type
	 *
	 * @param string $post_type Post type.
	 *
	 * @return string
	 * @deprecated
	 */
	public static function get_fallback_post_type( $post_type ) {

		return Post_Type::get_fallback_post_type( $post_type );
	}

	/**
	 * 関連記事の表示が有効か
	 *
	 * @return bool
	 */
	public static function is_active_related_posts() {
		if ( ! is_singular() ) {
			return false;
		}
		$post_type = Post_Type::get_post_type();
		$filter    = apply_filters( "ys_show_{$post_type}_related", null );
		if ( is_null( $filter ) ) {
			$fallback = Post_Type::get_fallback_post_type( $post_type );
			$show     = Option::get_option_by_bool( "ys_show_{$fallback}_related", true );
		} else {
			$show = $filter;
		}

		if ( ! $show ) {
			return false;
		}
		if ( Convert::to_bool( Post_Type::get_post_meta( 'ys_hide_related' ) ) ) {
			return false;
		}

		return true;
	}

	/**
	 * 関連記事表示
	 */
	public static function related_posts() {
		if ( ! self::is_active_related_posts() ) {
			return;
		}

		$tax_filter = '';
		$content    = '';
		$post_type  = Post_Type::get_post_type();

		if ( is_singular( 'post' ) ) {
			$tax_filter = 'category';
		} elseif ( is_singular() ) {
			$taxonomies = get_the_taxonomies();
			if ( $taxonomies ) {
				$tax_filter = array_key_first( $taxonomies );
			}
		}
		if ( ! empty( $tax_filter ) ) {
			$tax_filter = apply_filters(
				"ys_{$post_type}_related_posts_taxonomy",
				$tax_filter
			);
			$tax_filter = ! empty( $tax_filter ) ? "tax:{$tax_filter}," : '';

			$related        = new Recent_Posts();
			$shortcode_atts = apply_filters(
				'ys_related_posts_atts',
				[
					'post_type' => Post_Type::get_post_type(),
					'count'     => 6,
					'filter'    => "{$tax_filter}same-post",
					'list_type' => 'card',
					'orderby'   => 'rand',
					'cache'     => 'related_posts',
					'run_type'  => 'related_posts',
				],
				Post_Type::get_post_type(),
				get_the_ID()
			);
			$content        = $related->do_shortcode( $shortcode_atts );
		}
		$content = apply_filters(
			"ys_{$post_type}_related_posts",
			$content
		);
		if ( $content ) {
			$related_post_title = apply_filters(
				'ys_related_post_title',
				__( 'Related Posts', 'ystandard' )
			);
			$related_post       = sprintf(
				'<div class="post-related">%s%s</div>',
				"<p class=\"post-related__title\">{$related_post_title}</p>",
				$content
			);

			echo $related_post;
		}
	}
}

$class_content = new Content();
$class_content->register();
