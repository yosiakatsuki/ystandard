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
				'icon'  => 'fab fa-twitter',
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
				'icon'  => 'fab fa-facebook-f',
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
				'icon'  => 'fab fa-google-plus-g',
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
				'icon'  => 'fas fa-rss',
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

if ( ! function_exists( 'ys_get_subscribe_image' ) ) {
	/**
	 * 購読リンク 背景画像取得
	 */
	function ys_get_subscribe_image() {
		$image = '';
		if ( has_post_thumbnail() ) {
			$image = get_the_post_thumbnail( get_the_ID(), 'post-thmbnail', array( 'class' => 'subscribe__image' ) );
		} else {
			/**
			 * OGP画像
			 */
			$image = ys_get_option( 'ys_ogp_default_image' );
			if ( ! $image ) {
				/**
				 * カスタムロゴ
				 */
				$image = ys_get_custom_logo_image_object();
				if ( $image ) {
					$image = $image[0];
				}
			}
			if ( $image ) {
				$image = sprintf(
					'<img class="subscribe__image" src="%s" />',
					$image
				);
			} else {
				$image = '<div class="flex flex--c-c color__font-sub"><i class="far fa-image"></i></div>';
			}
		}
		if ( ys_is_amp() ) {
			$image = ys_amp_convert_image( $image );
		}
		return apply_filters( 'ys_get_subscribe_image', $image );
	}
}
if ( ! function_exists( 'ys_the_subscribe_image' ) ) {
	/**
	 * 購読リンク 背景画像表示
	 */
	function ys_the_subscribe_image() {
		echo ys_get_subscribe_image();
	}
}