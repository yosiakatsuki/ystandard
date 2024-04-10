<?php
/**
 * テンプレートタイプ
 *
 * @package ystandard
 * @author  yosiakatsuki
 * @license GPL-3.0+
 */

namespace ystandard;

use ystandard\utils\Post_Type;

class Post {

	/**
	 * Constructor.
	 */
	public function __construct() {
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

new Post();
