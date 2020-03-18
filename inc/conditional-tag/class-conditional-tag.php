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
