<?php
/**
 * SNS シェアボタン
 *
 * @package ystandard
 * @author  yosiakatsuki
 * @license GPL-2.0+
 */

/**
 * SNSシェアボタン表示
 *
 * @param string $title タイトル.
 *
 * @return string
 */
function ys_the_sns_share_button( $title = '' ) {
	/**
	 * 記事別の表示設定確認
	 */
	if ( is_singular() ) {
		if ( '1' === ys_get_post_meta( 'ys_hide_share' ) ) {
			return '';
		}
	}
	/**
	 * パラメータ作成
	 */
	$param = array(
		'twitter'              => ys_get_option_by_bool( 'ys_sns_share_button_twitter', true ),
		'twitter_via_user'     => ys_get_option( 'ys_sns_share_tweet_via_account', '' ),
		'twitter_related_user' => ys_get_option( 'ys_sns_share_tweet_related_account', '' ),
		'facebook'             => ys_get_option_by_bool( 'ys_sns_share_button_facebook', true ),
		'hatenabookmark'       => ys_get_option_by_bool( 'ys_sns_share_button_hatenabookmark', true ),
		'pocket'               => ys_get_option_by_bool( 'ys_sns_share_button_pocket', true ),
		'line'                 => ys_get_option_by_bool( 'ys_sns_share_button_line', true ),
		'feedly'               => ys_get_option_by_bool( 'ys_sns_share_button_feedly', true ),
		'rss'                  => ys_get_option_by_bool( 'ys_sns_share_button_rss', true ),
		'col_sp'               => ys_get_option_by_int( 'ys_sns_share_col_sp', 3 ),
		'col_tablet'           => ys_get_option_by_int( 'ys_sns_share_col_tablet', 3 ),
		'col_pc'               => ys_get_option_by_int( 'ys_sns_share_col_pc', 6 ),
		'title'                => $title,
	);
	/**
	 * ショートコード実行
	 */
	ys_do_shortcode( 'ys_share_button', $param );
}
