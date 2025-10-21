<?php
/**
 * 投稿フッター
 *
 * @package ystandard
 * @author  yosiakatsuki
 * @license GPL-3.0+
 */

namespace ystandard;

use ystandard\utils\Convert;
use ystandard\utils\Post_Type;

defined( 'ABSPATH' ) || die();

/**
 * Class Post_Footer
 */
class Post_Footer {

	/**
	 * インスタンス
	 *
	 * @var Post_Footer
	 */
	private static $instance;

	/**
	 * フッターコンテンツの優先順位
	 */
	const FOOTER_PRIORITY = [
		'widget'    => 10,
		'ad'        => 20,
		'sns-share' => 30,
		'taxonomy'  => 40,
		'author'    => 50,
		'related'   => 60,
		'comment'   => 70,
		'paging'    => 80,
	];

	/**
	 * インスタンス取得
	 *
	 * @return Post_Footer
	 */
	public static function get_instance(): Post_Footer {
		if ( null === self::$instance ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	/**
	 * Post_Header constructor.
	 */
	private function __construct() {
		add_action( 'ys_set_singular_content', [ $this, 'set_singular_footer_hook' ] );
	}

	/**
	 * 記事上下表示のセット
	 */
	public function set_singular_footer_hook() {
		self::set_singular_footer(
			'related',
			[ __CLASS__, 'related_posts' ]
		);
	}

	/**
	 * 記事フッター設定
	 *
	 * @param string $key Key.
	 * @param callable $function_to_add The name of the function you wish to be called.
	 */
	public static function set_singular_footer( $key, $function_to_add ) {
		$priority = self::get_footer_priority( $key );
		if ( 'none' === $priority ) {
			return;
		}
		add_action(
			'ys_singular_footer',
			$function_to_add,
			$priority
		);
	}

	/**
	 * コンテンツフッターの優先順位取得
	 *
	 * @param string $key Key.
	 *
	 * @return int
	 */
	public static function get_footer_priority( $key ) {
		$post_type = Post_Type::get_post_type();
		$cache_key = "{$post_type}-footer-priority";
		$priority  = wp_cache_get( $cache_key );
		if ( ! $priority ) {
			$priority = apply_filters(
				'ys_get_content_footer_priority',
				self::FOOTER_PRIORITY
			);
			wp_cache_set( $cache_key, $priority );
		}

		if ( isset( $priority[ $key ] ) ) {
			return $priority[ $key ];
		}

		return 10;
	}

	/**
	 * 投稿フッターを表示するか
	 *
	 * @return bool
	 */
	public static function is_active_post_footer() {
		$result = true;
		if ( is_singular() ) {
			// 投稿タイプ別フック.
			$post_type = Post_Type::get_post_type();
			$result    = apply_filters(
				"ys_is_active_post_footer_{$post_type}",
				$result
			);
		}

		return apply_filters( 'ys_is_active_post_footer', $result );
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
