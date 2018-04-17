<?php
/**
 * OGP
 *
 * @package ystandard
 * @author yosiakatsuki
 * @license GPL-2.0+
 */

if ( ! function_exists( 'ys_get_the_ogp' ) ) {
	/**
	 * OGP metaタグ作成
	 */
	function ys_get_the_ogp() {
		if ( ! ys_get_option( 'ys_ogp_enable' ) ) {
			return '';
		}
		$ogp   = '';
		$param = ys_get_ogp_and_twitter_card_param();
		if ( ! empty( $param['ogp_site_name'] ) ) {
			$ogp .= '<meta property="og:site_name" content="' . $param['ogp_site_name'] . '" />' . PHP_EOL;
		}
		if ( ! empty( $param['ogp_locale'] ) ) {
			$ogp .= '<meta property="og:locale" content="' . $param['ogp_locale'] . '" />' . PHP_EOL;
		}
		if ( ! empty( $param['ogp_type'] ) ) {
			$ogp .= '<meta property="og:type" content="' . $param['ogp_type'] . '" />' . PHP_EOL;
		}
		if ( ! empty( $param['title'] ) ) {
			$ogp .= '<meta property="og:title" content="' . $param['title'] . '" />' . PHP_EOL;
		}
		if ( ! empty( $param['description'] ) ) {
			$ogp .= '<meta property="og:description" content="' . $param['description'] . '" />' . PHP_EOL;
		}
		if ( ! empty( $param['image'] ) ) {
			$ogp .= '<meta property="og:image" content="' . $param['image'] . '" />' . PHP_EOL;
		}
		if ( ! empty( $param['url'] ) ) {
			$ogp .= '<meta property="og:url" content="' . $param['url'] . '" />' . PHP_EOL;
		}
		if ( ! empty( $param['ogp_app_id'] ) ) {
			$ogp .= '<meta property="fb:app_id" content="' . $param['ogp_app_id'] . '" />' . PHP_EOL;
		}
		if ( ! empty( $param['ogp_admins'] ) ) {
			$ogp .= '<meta property="fb:admins" content="' . $param['ogp_admins'] . '" />' . PHP_EOL;
		}
		return apply_filters( 'ys_get_the_ogp', $ogp );
	}
}

if ( ! function_exists( 'ys_get_the_twitter_card' ) ) {
	/**
	 * Twitter Card metaタグ作成
	 */
	function ys_get_the_twitter_card() {

		if ( ! ys_get_option( 'ys_twittercard_enable' ) ) {
			return '';
		}
		$twitter_card = '';
		$param        = ys_get_ogp_and_twitter_card_param();
		if ( ! empty( $param['twitter_card_type'] ) ) {
			$twitter_card .= '<meta name="twitter:card" content="' . $param['twitter_card_type'] . '" />' . PHP_EOL;
		}
		if ( ! empty( $param['twitter_account'] ) ) {
			$twitter_card .= '<meta name="twitter:site" content="' . $param['twitter_account'] . '" />' . PHP_EOL;
		}
		if ( ! empty( $param['title'] ) ) {
			$twitter_card .= '<meta name="twitter:title" content="' . $param['title'] . '" />' . PHP_EOL;
		}
		if ( ! empty( $param['description'] ) ) {
			$twitter_card .= '<meta name="twitter:description" content="' . $param['description'] . '" />' . PHP_EOL;
		}
		if ( ! empty( $param['image'] ) ) {
			$twitter_card .= '<meta name="twitter:image" content="' . $param['image'] . '" />' . PHP_EOL;
		}
		return apply_filters( 'ys_get_the_twitter_card', $twitter_card );
	}
}

if ( ! function_exists( 'ys_get_ogp_and_twitter_card_param' ) ) {
	/**
	 * OGP/Twitter Cardsパラメータ
	 *
	 * @return array
	 */
	function ys_get_ogp_and_twitter_card_param() {
		$dscr  = ys_get_meta_description();
		$param = array(
			'title'             => get_bloginfo( 'name' ),
			'description'       => $dscr,
			'image'             => ys_get_option( 'ys_ogp_default_image' ),
			'url'               => home_url( '/' ),
			'ogp_site_name'     => get_bloginfo( 'name' ),
			'ogp_locale'        => 'ja_JP',
			'ogp_type'          => 'website',
			'ogp_app_id'        => ys_get_option( 'ys_ogp_fb_app_id' ),
			'ogp_admins'        => ys_get_option( 'ys_ogp_fb_admins' ),
			'twitter_account'   => ys_get_option( 'ys_twittercard_user' ),
			'twitter_card_type' => ys_get_option( 'ys_twittercard_type' ),
		);
		/**
		 * 投稿・固定ページ系
		 */
		if ( is_singular() && ! ys_is_top_page() ) {
			$param['title'] = apply_filters( 'ys_ogp_title_singular', get_the_title() );
			$dscr_singular  = ys_get_post_meta( 'ys_ogp_description' );
			if ( '' !== $dscr_singular ) {
				$dscr = $dscr_singular;
			}
			$param['description'] = apply_filters( 'ys_ogp_description_singular', $dscr );
			$image                = ys_get_the_image_object();
			if ( $image ) {
				$param['image'] = $image[0];
			}
			$param['url']      = get_the_permalink();
			$param['ogp_type'] = 'article';
		}
		/**
		 * アーカイブ系
		 */
		if ( is_archive() && ! ys_is_top_page() ) {
			$param['title']       = apply_filters( 'ys_ogp_title_archive', ys_get_the_archive_title( '' ) );
			$param['url']         = ys_get_the_archive_url();
			$param['description'] = apply_filters( 'ys_ogp_description_archive', $dscr );
		}
		/**
		 * フィルタ
		 */
		return apply_filters( 'ys_get_ogp_and_twitter_card_param', $param );
	}
}
