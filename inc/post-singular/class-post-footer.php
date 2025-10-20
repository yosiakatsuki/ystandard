<?php
/**
 * 投稿フッター
 *
 * @package ystandard
 * @author  yosiakatsuki
 * @license GPL-3.0+
 */

namespace ystandard;

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
}
