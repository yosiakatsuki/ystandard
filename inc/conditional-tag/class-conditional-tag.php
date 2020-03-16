<?php
/**
 * 条件判断用関数群
 *
 * @package ystandard
 * @author  yosiakatsuki
 * @license GPL-2.0+
 */

namespace ystandard;

/**
 * Class Conditional_Tag
 *
 * @package ystandard
 */
class Conditional_Tag {

	/**
	 * アイキャッチ画像を表示するか
	 *
	 * @param int $post_id 投稿ID.
	 *
	 * @return bool
	 */
	public static function is_active_post_thumbnail( $post_id = null ) {
		$result = true;
		if ( ! is_singular() ) {
			return false;
		}
		if ( ! has_post_thumbnail( $post_id ) ) {
			$result = false;
		}
		/**
		 * 投稿ページ
		 */
		if ( is_single() ) {
			if ( ! ys_get_option_by_bool( 'ys_show_post_thumbnail', true ) ) {
				$result = false;
			}
		}
		/**
		 * 固定ページ
		 */
		if ( is_page() ) {
			if ( ! ys_get_option_by_bool( 'ys_show_page_thumbnail', true ) ) {
				$result = false;
			}
		}

		return apply_filters( 'ys_is_active_post_thumbnail', $result );
	}

	/**
	 * Google Analyticsのタグを出力するか
	 *
	 * @return bool
	 */
	public static function is_enable_google_analytics() {
		if ( ys_is_amp() ) {
			return false;
		}
		/**
		 * 設定チェック
		 */
		if ( ! ys_get_google_anarytics_tracking_id() ) {
			return false;
		}
		/**
		 * ログイン中にGA出力しない場合
		 */
		if ( ys_get_option_by_bool( 'ys_ga_exclude_logged_in_user', false ) ) {
			if ( is_user_logged_in() ) {
				/**
				 * 編集権限を持っている場合のみ出力しない
				 */
				if ( current_user_can( 'edit_posts' ) ) {
					return false;
				}
			}
		}

		return true;
	}
}
