<?php

/**
 * OGP metaタグ作成
 */
if( ! function_exists( 'ys_get_the_ogp') ) {
	function ys_get_the_ogp(){
		if( ! ys_get_option( 'ys_ogp_enable' ) ) {
			return '';
		}
		$ogp = '';
		$param = ys_get_ogp_and_twitter_card_param();
		if( ! empty( $param['ogp_site_name'] ) ) {
			$ogp .= '<meta name="og:site_name" content="' . $param['ogp_site_name'] . '" />' . PHP_EOL;
		}
		if( ! empty( $param['ogp_locale'] ) ) {
			$ogp .= '<meta name="og:locale" content="' . $param['ogp_locale'] . '" />' . PHP_EOL;
		}
		if( ! empty( $param['ogp_app_id'] ) ) {
			$ogp .= '<meta name="og:app_id" content="' . $param['ogp_app_id'] . '" />' . PHP_EOL;
		}
		if( ! empty( $param['ogp_admins'] ) ) {
			$ogp .= '<meta name="og:admins" content="' . $param['ogp_admins'] . '" />' . PHP_EOL;
		}
		if( ! empty( $param['ogp_type'] ) ) {
			$ogp .= '<meta name="og:type" content="' . $param['ogp_type'] . '" />' . PHP_EOL;
		}
		if( ! empty( $param['title'] ) ) {
			$ogp .= '<meta name="og:title" content="' . $param['title'] . '" />' . PHP_EOL;
		}
		if( ! empty( $param['description'] ) ) {
			$ogp .= '<meta name="og:description" content="' . $param['description'] . '" />' . PHP_EOL;
		}
		if( ! empty( $param['image'] ) ) {
			$ogp .= '<meta name="og:image" content="' . $param['image'] . '" />' . PHP_EOL;
		}
		if( ! empty( $param['url'] ) ) {
			$ogp .= '<meta name="og:url" content="' . $param['url'] . '" />' . PHP_EOL;
		}
		return apply_filters( 'ys_get_the_ogp', $ogp );
	}
}

/**
 * Twitter Card metaタグ作成
 */
if( ! function_exists( 'ys_get_the_twitter_card') ) {
	function ys_get_the_twitter_card(){

		if( ! ys_get_option( 'ys_twittercard_enable' ) ) {
			return '';
		}
		$twitter_card = '';
		$param = ys_get_ogp_and_twitter_card_param();
		if( ! empty( $param['twitter_card_type'] ) ) {
			$twitter_card .= '<meta name="twitter:card" content="' . $param['twitter_card_type'] . '" />' . PHP_EOL;
		}
		if( ! empty( $param['twitter_account'] ) ) {
			$twitter_card .= '<meta name="twitter:site" content="' . $param['twitter_account'] . '" />' . PHP_EOL;
		}
		if( ! empty( $param['title'] ) ) {
			$twitter_card .= '<meta name="twitter:title" content="' . $param['title'] . '" />' . PHP_EOL;
		}
		if( ! empty( $param['description'] ) ) {
			$twitter_card .= '<meta name="twitter:description" content="' . $param['description'] . '" />' . PHP_EOL;
		}
		if( ! empty( $param['image'] ) ) {
			$twitter_card .= '<meta name="twitter:image" content="' . $param['image'] . '" />' . PHP_EOL;
		}
		return apply_filters( 'ys_get_the_twitter_card', $twitter_card );
	}
}


if( ! function_exists( 'ys_get_ogp_and_twitter_card_param') ) {
	function ys_get_ogp_and_twitter_card_param() {
		$param = array(
			'title' => get_bloginfo('name'),
			'description' => get_bloginfo('description'),
			'image' => ys_get_option( 'ys_ogp_default_image' ),
			'url' => get_bloginfo('url'),
			'ogp_site_name' => get_bloginfo('name'),
			'ogp_locale' => 'ja_JP',
			'ogp_type' => 'website',
			'ogp_app_id' => ys_get_option( 'ys_ogp_fb_app_id' ),
			'ogp_admins' => ys_get_option( 'ys_ogp_fb_admins' ),
			'twitter_account' => ys_get_option( 'ys_twittercard_user' ),
			'twitter_card_type' => ys_get_option( 'ys_twittercard_type' ),
		);
		/**
		 * 投稿・固定ページ系
		 */
		if( is_singular() && ! ys_is_toppage() ) {
			$param['title'] = get_the_title();
			$param['description'] = ys_get_the_custom_excerpt('');
			$image = ys_get_the_image_object();
			if( $image ) {
				$param['image'] = $image[0];
			}
			$param['url'] = get_the_permalink();
			$param['ogp_type'] = 'article';
		}
		/**
		 * アーカイブ系
		 */
		if( is_archive() && ! ys_is_toppage() ){
			$param['title'] = ys_get_the_archive_title( '' );
			$param['url'] = ys_get_the_archive_url();
		}
		/**
		 * フィルタ
		 */
		return apply_filters( 'ys_get_ogp_and_twitter_card_param', $param );
	}
}

/**
 * OGPデフォルト画像のimageオブジェクト取得
 */
if( ! function_exists( 'ys_get_ogp_default_image_object') ) {
	function ys_get_ogp_default_image_object() {
		$image = ys_get_option( 'ys_ogp_default_image' );
		if( $image ) {
			$image = wp_get_attachment_image_src( get_attachment_id_from_src( $image ), 'full' );
			if( $image ) {
				return $image;
			}
		}
		return false;
	}
}