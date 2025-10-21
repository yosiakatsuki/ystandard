<?php
/**
 * 投稿ヘッダー
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
 * Class Post_Header
 */
class Post_Header {

	/**
	 * インスタンス
	 *
	 * @var Post_Header
	 */
	private static $instance;

	/**
	 * ヘッダーコンテンツの優先順位
	 */
	const HEADER_PRIORITY = [
		'post-thumbnail' => 10,
		'title'          => 20,
		'meta'           => 30,
		'sns-share'      => 40,
		'ad'             => 50,
		'widget'         => 60,
	];

	/**
	 * インスタンス取得
	 *
	 * @return Post_Header
	 */
	public static function get_instance(): Post_Header {
		if ( null === self::$instance ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	/**
	 * Post_Header constructor.
	 */
	private function __construct() {
		add_action( 'ys_set_singular_content', [ $this, 'set_singular_header_hook' ] );
	}

	/**
	 * 記事詳細ページのヘッダー表示の登録.
	 *
	 * @return void
	 */
	public function set_singular_header_hook(): void {
		self::set_singular_header(
			'post-thumbnail',
			[ '\ystandard\Post_Singular_Thumbnail', 'post_thumbnail_default' ]
		);
		self::set_singular_header(
			'title',
			[ '\ystandard\Post_Content', 'singular_title' ]
		);
		self::set_singular_header(
			'meta',
			[ '\ystandard\Post_Content', 'singular_meta' ]
		);
	}

	/**
	 * 記事ヘッダー設定
	 *
	 * @param string $key Key.
	 * @param callable $function_to_add The name of the function you wish to be called.
	 */
	public static function set_singular_header( $key, $function_to_add ) {
		$priority = self::get_header_priority( $key );
		if ( 'none' === $priority ) {
			return;
		}
		add_action(
			'ys_singular_header',
			$function_to_add,
			$priority
		);
	}

	/**
	 * コンテンツヘッダーの優先順位取得
	 *
	 * @param string $key Key.
	 *
	 * @return int
	 */
	public static function get_header_priority( $key ) {
		$post_type = Post_Type::get_post_type();
		$cache_key = "{$post_type}-header-priority";
		$priority  = wp_cache_get( $cache_key );
		if ( ! $priority ) {
			$priority = apply_filters(
				'ys_get_content_header_priority',
				self::HEADER_PRIORITY,
				$post_type
			);
			wp_cache_set( $cache_key, $priority );
		}

		if ( isset( $priority[ $key ] ) ) {
			return $priority[ $key ];
		}

		return 10;
	}


	/**
	 * 投稿ヘッダーを表示するか
	 *
	 * @return bool
	 */
	public static function is_active_post_header() {
		$result = true;
		// フロントページの場合は表示しない.
		if ( Front_Page::is_single_front_page() ) {
			$result = false;
		}
		// タイトルなしテンプレートの場合は表示しない.
		if ( Template_Type::is_no_title_template() ) {
			$result = false;
		}
		if ( is_singular() ) {
			// 投稿タイプ別フック.
			$post_type = Post_Type::get_post_type();
			$result    = apply_filters(
				"ys_is_active_post_header_{$post_type}",
				$result
			);
		}

		return apply_filters( 'ys_is_active_post_header', $result );
	}

	/**
	 * 投稿ヘッダーのカテゴリー情報を取得
	 */
	public static function get_post_header_category() {

		$post_type = Post_Type::get_post_type();
		$filter    = apply_filters( "ys_show_{$post_type}_header_taxonomy", null );
		if ( is_null( $filter ) ) {
			$fallback = Post_Type::get_fallback_post_type( $post_type );
			$show     = Option::get_option_by_bool( "ys_show_{$fallback}_header_category", true );
		} else {
			$show = $filter;
		}

		if ( ! Convert::to_bool( $show ) ) {
			return '';
		}

		$result     = [];
		$taxonomies = apply_filters(
			"ys_get_{$post_type}_header_taxonomy",
			self::get_post_header_taxonomies()
		);
		if ( empty( $taxonomies ) ) {
			return '';
		}
		$terms_length = apply_filters( "ys_get_{$post_type}_header_terms_length", 1 );
		foreach ( $taxonomies as $taxonomy ) {
			$terms = get_the_terms( false, $taxonomy );
			if ( is_wp_error( $terms ) || empty( $terms ) ) {
				return '';
			}
			$terms = array_slice( $terms, 0, $terms_length );
			foreach ( $terms as $term ) {
				$result[] = sprintf(
					'<div class="singular-header__terms">%s<a href="%s">%s</a></div>',
					Taxonomy::get_taxonomy_icon( $taxonomy ),
					get_term_link( $term ),
					$term->name
				);
			}
		}

		return apply_filters(
			"ys_get_{$post_type}_header_category",
			implode( '', $result ),
			$taxonomies
		);
	}

	/**
	 * 投稿詳細ヘッダー用表示タクソノミー取得
	 *
	 * @return array|bool
	 */
	public static function get_post_header_taxonomies() {
		$taxonomies = get_the_taxonomies();
		if ( ! $taxonomies ) {
			return false;
		}

		$taxonomy = array_keys( $taxonomies );

		if ( 'post' === get_post_type( get_the_ID() ) ) {
			$taxonomy = [ 'category' ];
		}

		return $taxonomy;
	}


}

Post_Header::get_instance();
