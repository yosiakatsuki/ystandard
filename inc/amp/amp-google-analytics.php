<?php
/**
 * AMP用Google Analytics関連処理
 *
 * @package ystandard
 * @author yosiakatsuki
 * @license GPL-2.0+
 */

if ( ! function_exists( 'ys_get_amp_google_anarytics_tracking_id' ) ) {
	/**
	 * Google Analytics idの取得
	 */
	function ys_get_amp_google_anarytics_tracking_id() {
		$ga_id = trim( ys_get_option( 'ys_ga_tracking_id_amp' ) );
		if ( '' === $ga_id ) {
			$ga_id = ys_get_google_anarytics_tracking_id();
		}
		return apply_filters( 'ys_get_amp_google_anarytics_tracking_id', $ga_id );
	}
}

if ( ! function_exists( 'ys_the_amp_google_anarytics' ) ) {
	/**
	 * Google Analytics出力
	 */
	function ys_the_amp_google_anarytics() {
		/**
		 * 管理画面ログイン中はGAタグを出力しない
		 */
		if ( ! ys_is_enable_google_analytics() ) {
			return;
		}
		if ( ! ys_is_amp() ) {
			return;
		}
		get_template_part( 'template-parts/amp/amp-google-analytics' );
	}
}
add_action( 'ys_body_prepend', 'ys_the_amp_google_anarytics', 1 );