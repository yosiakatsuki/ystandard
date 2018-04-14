<?php
/**
 * SNS シェアボタン
 *
 * @package ystandard
 * @author yosiakatsuki
 * @license GPL-2.0+
 */

if ( ! function_exists( 'ys_get_share_button_data' ) ) {
	/**
	 * Share button
	 */
	function ys_get_share_button_data() {

		if ( '1' === ys_get_post_meta( 'ys_hide_share' ) ) {
			return array();
		}
		$data        = array();
		$share_url   = urlencode( get_permalink() );
		$share_title = urlencode( str_replace( ' &#8211; ', ' - ', wp_get_document_title() ) );
		/**
		 * Twitter
		 */
		if ( apply_filters( 'ys_show_share_button_twitter', ys_get_option( 'ys_sns_share_button_twitter' ) ) ) {
			$data[] = ys_get_share_button_data_twitter( $share_url, $share_title );
		}
		/**
		 * Facebook
		 */
		if ( apply_filters( 'ys_show_share_button_facebook', ys_get_option( 'ys_sns_share_button_facebook' ) ) ) {
			$data[] = ys_get_share_button_data_facebook( $share_url, $share_title );
		}
		/**
		 * はてなブックマーク
		 */
		if ( apply_filters( 'ys_show_share_button_hatenabookmark', ys_get_option( 'ys_sns_share_button_hatenabookmark' ) ) ) {
			$data[] = ys_get_share_button_data_hatenabookmark( $share_url, $share_title );
		}
		/**
		 * Google+
		 */
		if ( apply_filters( 'ys_show_share_button_googlepuls', ys_get_option( 'ys_sns_share_button_googlepuls' ) ) ) {
			$data[] = ys_get_share_button_data_google_plus( $share_url, $share_title );
		}
		/**
		 * Pocket
		 */
		if ( apply_filters( 'ys_show_share_button_pocket', ys_get_option( 'ys_sns_share_button_pocket' ) ) ) {
			$data[] = ys_get_share_button_data_pocket( $share_url, $share_title );
		}
		/**
		 * LINE
		 */
		if ( apply_filters( 'ys_show_share_button_line', ys_get_option( 'ys_sns_share_button_line' ) ) ) {
			$data[] = ys_get_share_button_data_line( $share_url, $share_title );
		}
		/**
		 * Feedly
		 */
		if ( apply_filters( 'ys_show_share_button_feedly', ys_get_option( 'ys_sns_share_button_feedly' ) ) ) {
			$data[] = ys_get_share_button_data_feedly( $share_url, $share_title );
		}
		/**
		 * RSS
		 */
		if ( apply_filters( 'ys_show_share_button_rss', ys_get_option( 'ys_sns_share_button_rss' ) ) ) {
			$data[] = ys_get_share_button_data_rss( $share_url, $share_title );
		}
		return apply_filters( 'ys_get_share_button_data', $data );
	}
}

if ( ! function_exists( 'ys_get_share_button_data_twitter' ) ) {
	/**
	 * シェアボタン用データ : Twitter
	 *
	 * @param string $share_url url.
	 * @param string $share_title title.
	 */
	function ys_get_share_button_data_twitter( $share_url, $share_title ) {
		$tweet_via         = '';
		$tweet_via_account = apply_filters( 'ys_share_tweet_via_account', ys_get_option( 'ys_sns_share_tweet_via_account' ) );
		if ( 1 == ys_get_option( 'ys_sns_share_tweet_via' ) && '' != $tweet_via_account ) {
			$tweet_via = '&via=' . $tweet_via_account;
		}
		$tweet_related         = '';
		$tweet_related_account = apply_filters( 'ys_share_tweet_related_account', ys_get_option( 'ys_sns_share_tweet_related_account' ) );
		if ( '' != $tweet_related_account ) {
			$tweet_related = '&related=' . $tweet_related_account;
		}
		$twitter_share_text  = apply_filters( 'ys_share_twitter_text', $share_title );
		$twitter_share_url   = apply_filters( 'ys_share_twitter_url', $share_url );
		$twitter_button_text = apply_filters( 'ys_twitter_button_text', 'Twitter' );

		$twitter_share_url = 'http://twitter.com/share?text=' . $twitter_share_text . '&url=' . $twitter_share_url . $tweet_via . $tweet_related;

		return array(
			'type'        => esc_attr( 'twitter' ),
			'icon'        => esc_attr( 'twitter' ),
			'url'         => esc_url_raw( $twitter_share_url ),
			'button-text' => esc_html( $twitter_button_text ),
		);
	}
}


if ( ! function_exists( 'ys_get_share_button_data_facebook' ) ) {
	/**
	 * シェアボタン用データ : Facebook
	 *
	 * @param string $share_url url.
	 * @param string $share_title title.
	 */
	function ys_get_share_button_data_facebook( $share_url, $share_title ) {
		$share_url   = 'https://www.facebook.com/sharer.php?src=bm&u=' . $share_url . '&t =' . $share_title;
		$button_text = apply_filters( 'ys_facebook_button_text', 'Facebook' );
		return array(
			'type'        => esc_attr( 'facebook' ),
			'icon'        => esc_attr( 'facebook' ),
			'url'         => esc_url_raw( $share_url ),
			'button-text' => esc_html( $button_text ),
		);
	}
}

if ( ! function_exists( 'ys_get_share_button_data_hatenabookmark' ) ) {
	/**
	 * シェアボタン用データ : はてなブックマーク
	 *
	 * @param string $share_url url.
	 * @param string $share_title title.
	 */
	function ys_get_share_button_data_hatenabookmark( $share_url, $share_title ) {
		$share_url   = 'https://b.hatena.ne.jp/add?mode=confirm&url=' . $share_url;
		$button_text = apply_filters( 'ys_hatenabookmark_button_text', 'はてブ' );
		return array(
			'type'        => esc_attr( 'hatenabookmark' ),
			'icon'        => esc_attr( 'hatenabookmark' ),
			'url'         => esc_url_raw( $share_url ),
			'button-text' => esc_html( $button_text ),
		);
	}
}

if ( ! function_exists( 'ys_get_share_button_data_google_plus' ) ) {
	/**
	 * シェアボタン用データ : Google+
	 *
	 * @param string $share_url url.
	 * @param string $share_title title.
	 */
	function ys_get_share_button_data_google_plus( $share_url, $share_title ) {
		$share_url   = 'https://plus.google.com/share?url=' . $share_url;
		$button_text = apply_filters( 'ys_googleplus_button_text', 'Google+' );
		return array(
			'type'        => esc_attr( 'google-plus' ),
			'icon'        => esc_attr( 'google-plus' ),
			'url'         => esc_url_raw( $share_url ),
			'button-text' => esc_html( $button_text ),
		);
	}
}

if ( ! function_exists( 'ys_get_share_button_data_pocket' ) ) {
	/**
	 * シェアボタン用データ : Pocket
	 *
	 * @param string $share_url url.
	 * @param string $share_title title.
	 */
	function ys_get_share_button_data_pocket( $share_url, $share_title ) {
		$share_url   = 'https://getpocket.com/edit?url=' . $share_url . '&title=' . $share_title;
		$button_text = apply_filters( 'ys_pocket_button_text', 'Pocket' );
		return array(
			'type'        => esc_attr( 'pocket' ),
			'icon'        => esc_attr( 'get-pocket' ),
			'url'         => esc_url_raw( $share_url ),
			'button-text' => esc_html( $button_text ),
		);
	}
}

if ( ! function_exists( 'ys_get_share_button_data_line' ) ) {
	/**
	 * シェアボタン用データ : LINE
	 *
	 * @param string $share_url url.
	 * @param string $share_title title.
	 */
	function ys_get_share_button_data_line( $share_url, $share_title ) {
		$share_url   = 'https://social-plugins.line.me/lineit/share?url=' . $share_url;
		$button_text = apply_filters( 'ys_line_button_text', 'LINE' );
		return array(
			'type'        => esc_attr( 'line' ),
			'icon'        => esc_attr( 'comment' ),
			'url'         => esc_url_raw( $share_url ),
			'button-text' => esc_html( $button_text ),
		);
	}
}

if ( ! function_exists( 'ys_get_share_button_data_feedly' ) ) {
	/**
	 * シェアボタン用データ : Feedly
	 *
	 * @param string $share_url url.
	 * @param string $share_title title.
	 */
	function ys_get_share_button_data_feedly( $share_url, $share_title ) {
		$share_url   = ys_get_feedly_subscribe_url();
		$button_text = apply_filters( 'ys_feedly_button_text', 'Feedly' );
		return array(
			'type'        => esc_attr( 'feedly' ),
			'icon'        => esc_attr( 'feedly' ),
			'url'         => esc_url_raw( $share_url ),
			'button-text' => esc_html( $button_text ),
		);
	}
}

if ( ! function_exists( 'ys_get_share_button_data_rss' ) ) {
	/**
	 * シェアボタン用データ : Feedly
	 *
	 * @param string $share_url url.
	 * @param string $share_title title.
	 */
	function ys_get_share_button_data_rss( $share_url, $share_title ) {
		$share_url   = get_feed_link();
		$button_text = apply_filters( 'ys_rss_button_text', 'フィード' );
		return array(
			'type'        => esc_attr( 'rss' ),
			'icon'        => esc_attr( 'rss' ),
			'url'         => esc_url_raw( $share_url ),
			'button-text' => esc_html( $button_text ),
		);
	}
}
