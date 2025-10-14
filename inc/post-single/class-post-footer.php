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

/**
 * Class Post_Footer
 */
class Post_Footer {

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
