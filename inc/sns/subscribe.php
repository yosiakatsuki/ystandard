<?php
/**
 * フォローボックス表示データの作成
 *
 * @package ystandard
 * @author yosiakatsuki
 * @license GPL-2.0+
 */

if ( ! function_exists( 'ys_get_subscribe_buttons' ) ) {
	/**
	 * フォローボックス表示データの作成
	 *
	 * @return array
	 */
	function ys_get_subscribe_buttons() {
		$subscribe = array();
		/**
		 * Twitter
		 */
		if ( ys_get_option( 'ys_subscribe_url_twitter' ) ) {
			$subscribe['Twitter'] = array(
				'class' => 'twitter',
				'icon'  => 'twitter',
				'text'  => 'Twitter',
				'url'   => ys_get_option( 'ys_subscribe_url_twitter' ),
			);
		}
		/**
		 * Facebook
		 */
		if ( ys_get_option( 'ys_subscribe_url_facebook' ) ) {
			$subscribe['Facebook'] = array(
				'class' => 'facebook',
				'icon'  => 'facebook',
				'text'  => 'Facebook',
				'url'   => ys_get_option( 'ys_subscribe_url_facebook' ),
			);
		}
		/**
		 * Google +
		 */
		if ( ys_get_option( 'ys_subscribe_url_googleplus' ) ) {
			$subscribe['Google+'] = array(
				'class' => 'google-plus',
				'icon'  => 'google-plus',
				'text'  => 'Google+',
				'url'   => ys_get_option( 'ys_subscribe_url_googleplus' ),
			);
		}
		/**
		 * Feedly
		 */
		if ( ys_get_option( 'ys_subscribe_url_feedly' ) ) {
			$subscribe['Feedly'] = array(
				'class' => 'feedly',
				'icon'  => 'feedly',
				'text'  => 'Feedly',
				'url'   => ys_get_option( 'ys_subscribe_url_feedly' ),
			);
		}
		if ( ! ys_is_active_follow_box() ) {
			$subscribe = array();
		}
		return apply_filters( 'ys_get_subscribe_buttons', $subscribe );
	}
}

if ( ! function_exists( 'ys_get_subscribe_background_image' ) ) {
	/**
	 * 購読リンク 背景画像取得
	 */
	function ys_get_subscribe_background_image() {
		$image = '';
		if ( has_post_thumbnail() ) {
			$image = get_the_post_thumbnail( get_the_ID(), 'post-thmbnail', array( 'class' => 'subscribe__image' ) );
		}
		if ( ys_is_amp() ) {
			$image = ys_amp_convert_image( $image );
		}
		return apply_filters( 'ys_get_subscribe_background_image', $image );
	}
}
if ( ! function_exists( 'ys_the_subscribe_background_image' ) ) {
	/**
	 * 購読リンク 背景画像表示
	 */
	function ys_the_subscribe_background_image() {
		echo ys_get_subscribe_background_image();
	}
}