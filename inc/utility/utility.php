<?php
/**
 *	テーマバージョン取得
 */
if ( ! function_exists( 'ys_get_theme_version') ) {
	function ys_get_theme_version( $template = false ) {
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
 * HTML・改行・ショートコードなしのテキストを取得
 */
function ys_get_plain_text( $data ) {
	/**
	* ショートコード削除
	*/
	$data = strip_shortcodes( $data );
	/**
	 * HTMLタグ削除
	 */
	$data = wp_strip_all_tags( $data, true );
	return $data;
}


/**
 * Twitter用javascript URL取得
 */
if( ! function_exists( 'ys_get_twitter_widgets_js' ) ) {
	function ys_get_twitter_widgets_js(){
		return apply_filters( 'ys_get_twitter_widgets_js', '//platform.twitter.com/widgets.js' );
	}
}
/**
 * Facebook用javascript URL取得
 */
if( ! function_exists( 'ys_get_facebook_sdk_js' ) ) {
	function ys_get_facebook_sdk_js(){
		return apply_filters( 'ys_get_facebook_sdk_js', '//connect.facebook.net/ja_JP/sdk.js#xfbml=1&version=v2.8' );
	}
}
/**
 * Feedly 購読URL作成
 */
if( ! function_exists( 'ys_get_feedly_subscribe_url' ) ) {
	function ys_get_feedly_subscribe_url( $type = '' ) {
		return 'https://feedly.com/i/subscription/feed/' . urlencode( get_feed_link( $type ) );
	}
}
/**
 * get_posts で使うクエリパラメータを作る : 基本部分
 */
if ( ! function_exists( 'ys_get_posts_args' ) ) {
	function ys_get_posts_args( $posts_per_page = 4, $args = array() ){
		$defaults = array(
									'post_type' => 'post',
									'posts_per_page' => $posts_per_page,
									'no_found_rows' => true,
									'ignore_sticky_posts'=> true
								);
		$args = wp_parse_args( $args, $defaults );
		return $args;
	}
}
/**
 * get_posts で使うクエリパラメータを作る : ランダムに取得
 */
if ( ! function_exists( 'ys_get_posts_args_rand' ) ) {
	function ys_get_posts_args_rand( $posts_per_page = 4, $args = array() ){
		$rand_args = array( 'orderby' => 'rand' );
		$rand_args = wp_parse_args( $rand_args, $args );
		/**
		 * ランダムなクエリを取得
		 */
		return ys_get_posts_args( $posts_per_page, $rand_args );
	}
}