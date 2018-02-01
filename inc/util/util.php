<?php
/**
 *	テーマバージョン取得
 */
if ( ! function_exists( 'ys_util_get_theme_version') ) {
	function ys_util_get_theme_version( $template = false ) {
		/**
		 * 子テーマ情報
		 */
		$theme = wp_get_theme();
		if( $template && get_template() != get_stylesheet() ){
			/**
			 * 親テーマ情報
			 */
			$theme = wp_get_theme( get_template() );
		}
		return $theme->Version;
	}
}

/**
 * 投稿抜粋文を作成
 */
if( ! function_exists( 'ys_util_get_the_custom_excerpt' ) ) {
	function ys_util_get_the_custom_excerpt( $sep = ' …', $length = 0, $post_id = 0 ){
		if( ! is_singular() ) {
			return '';
		}
		if( 0 == $post_id ) {
			$post_id = get_the_ID();
		}
		if( 0 == $length ) {
			$length = 80;
		}
		$post = get_post( $post_id );
		$content = $post->post_content;
		/**
		 * moreタグ以降を削除
		 */
		$content = preg_replace( '/<!--more-->.+/is', '', $content );
		/**
		 * HTMLタグ削除
		 */
		$content = wp_strip_all_tags( $content, true );
		/**
		 * ショートコード削除
		 */
		$content = strip_shortcodes( $content );
		/**
		 * 長さ調節
		 */
		if( mb_strlen( $content ) > $length ) {
			$content =  mb_substr( $content, 0, $length - mb_strlen( $sep ) ) . $sep;
		}
		return apply_filters( 'ys_util_get_the_custom_excerpt', $content, $post_id );
	}
}
/**
 * 投稿抜粋文を出力
 */
if( ! function_exists( 'ys_util_the_custom_excerpt' ) ) {
	function ys_util_the_custom_excerpt( $sep = ' …', $length = 0, $post_id = 0 ){
		echo ys_util_get_the_custom_excerpt( $length, $sep, $post_id );
	}
}
/**
 * Twitter用javascript URL取得
 */
if( ! function_exists( 'ys_util_get_twitter_widgets_js' ) ) {
	function ys_util_get_twitter_widgets_js(){
		return apply_filters( 'ys_util_get_twitter_widgets_js', '//platform.twitter.com/widgets.js' );
	}
}
/**
 * Facebook用javascript URL取得
 */
if( ! function_exists( 'ys_util_get_facebook_sdk_js' ) ) {
	function ys_util_get_facebook_sdk_js(){
		return apply_filters( 'ys_util_get_facebook_sdk_js', '//connect.facebook.net/ja_JP/sdk.js#xfbml=1&version=v2.8' );
	}
}
/**
 * Feedly 購読URL作成
 */
if( ! function_exists( 'ys_util_get_feedly_subscribe_url' ) ) {
	function ys_util_get_feedly_subscribe_url( $type = '' ) {
		return 'https://feedly.com/i/subscription/feed/' . urlencode( get_feed_link( $type ) );
	}
}