<?php
/**
 * SNS シェアボタン
 *
 * @package ystandard
 * @author  yosiakatsuki
 * @license GPL-2.0+
 */


function ys_the_sns_share_button( $headline = '' ) {
	/**
	 * 表示確認
	 */
	if ( is_singular() ) {
		if ( '1' === ys_get_post_meta( 'ys_hide_share' ) ) {
			return '';
		}
	}
	/**
	 * パラメータ作成
	 */
	$param   = array();
	$param[] = 'twitter="' . ys_get_option( 'ys_sns_share_button_twitter' ) . '"';
	$param[] = 'twitter_via_user="' . ys_get_option( 'ys_sns_share_tweet_via_account' ) . '"';
	$param[] = 'twitter_related_user="' . ys_get_option( 'ys_sns_share_tweet_related_account' ) . '"';
	$param[] = 'facebook="' . ys_get_option( 'ys_sns_share_button_facebook' ) . '"';
	$param[] = 'hatenabookmark="' . ys_get_option( 'ys_sns_share_button_hatenabookmark' ) . '"';
	$param[] = 'googleplus="' . ys_get_option( 'ys_sns_share_button_googleplus' ) . '"';
	$param[] = 'pocket="' . ys_get_option( 'ys_sns_share_button_pocket' ) . '"';
	$param[] = 'line="' . ys_get_option( 'ys_sns_share_button_line' ) . '"';
	$param[] = 'feedly="' . ys_get_option( 'ys_sns_share_button_feedly' ) . '"';
	$param[] = 'rss="' . ys_get_option( 'ys_sns_share_button_rss' ) . '"';
	$param[] = 'col_sp="' . ys_get_option( 'ys_sns_share_col_sp' ) . '"';
	$param[] = 'col_tablet="' . ys_get_option( 'ys_sns_share_col_tablet' ) . '"';
	$param[] = 'col_pc="' . ys_get_option( 'ys_sns_share_col_pc' ) . '"';
	$param[] = 'headline="' . $headline . '"';

	/**
	 * ショートコード実行
	 */
	echo do_shortcode( '[ys_share_button ' . implode( ' ', $param ) . ']' );
}