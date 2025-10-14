<?php
/**
 * 投稿ヘッダー
 *
 * @package ystandard
 * @author  yosiakatsuki
 * @license GPL-3.0+
 */

namespace ystandard;

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
			[ __CLASS__, 'post_thumbnail_default' ]
		);
		self::set_singular_header(
			'title',
			[ __CLASS__, 'singular_title' ]
		);
		self::set_singular_header(
			'meta',
			[ __CLASS__, 'singular_meta' ]
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
}

Post_Header::get_instance();
