<?php
/**
 * フォローボックス表示データの作成
 *
 * @package ystandard
 * @author  yosiakatsuki
 * @license GPL-2.0+
 */

/**
 * フォローボックス表示データの作成
 */
function ys_get_subscribe_buttons() {
	if ( ! ys_is_active_follow_box() ) {
		return;
	}
	$args = array();
	/**
	 * Twitter
	 */
	if ( ys_get_option( 'ys_subscribe_url_twitter', '' ) ) {
		$args['twitter'] = ys_get_option( 'ys_subscribe_url_twitter', '' );
	}
	/**
	 * Facebook
	 */
	if ( ys_get_option( 'ys_subscribe_url_facebook', '' ) ) {
		$args['facebook'] = ys_get_option( 'ys_subscribe_url_facebook', '' );
	}
	/**
	 * Feedly
	 */
	if ( ys_get_option( 'ys_subscribe_url_feedly', '' ) ) {
		$args['feedly'] = ys_get_option( 'ys_subscribe_url_feedly', '' );
	}
	/**
	 * クラス
	 */
	$args['class'] = 'singular-footer__block';

	ys_do_shortcode(
		'ys_follow_box',
		$args
	);
}
