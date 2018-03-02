<?php
/**
 * @package ystandard
 * @author yosiakatsuki
 * @license GPL-2.0+
 */
/**
 * フッターSNSフォローリンク用URL取得
 */
if( ! function_exists( 'ys_get_footer_sns_list' ) ) {
	function ys_get_footer_sns_list() {
		/**
		 * リンク作成用配列作成
		 */
		 $list = array();
		 $list[] = ys_create_footer_sns_fa_link( 'twitter', 'ys_follow_url_twitter', 'twitter' );
		 $list[] = ys_create_footer_sns_fa_link( 'facebook', 'ys_follow_url_facebook', 'facebook' );
		 $list[] = ys_create_footer_sns_fa_link( 'google-plus', 'ys_follow_url_googlepuls', 'google-plus' );
		 $list[] = ys_create_footer_sns_fa_link( 'instagram', 'ys_follow_url_instagram', 'instagram' );
		 $list[] = ys_create_footer_sns_fa_link( 'tumblr', 'ys_follow_url_tumblr', 'tumblr' );
		 $list[] = ys_create_footer_sns_fa_link( 'youtube', 'ys_follow_url_youtube', 'youtube' );
		 $list[] = ys_create_footer_sns_fa_link( 'github', 'ys_follow_url_github', 'github' );
		 $list[] = ys_create_footer_sns_fa_link( 'pinterest', 'ys_follow_url_pinterest', 'pinterest' );
		 $list[] = ys_create_footer_sns_fa_link( 'linkedin', 'ys_follow_url_linkedin', 'linkedin' );

		 return apply_filters( 'ys_get_footer_sns_list', $list );
	}
}

/**
 * フッターSNSフォローリンク作成用配列作成(汎用)
 */
if( ! function_exists( 'ys_create_footer_sns_link' ) ) {
	function ys_create_footer_sns_link( $class, $option_key, $icon_class = '' ){
		return array(
			'class'      => $class,
			'url'        => ys_get_option( $option_key ),
			'icon-class' => $icon_class
		);
	}
}

/**
 * フッターSNSフォローリンク作成用配列作成(font awesome)
 */
if( ! function_exists( 'ys_create_footer_sns_fa_link' ) ) {
	function ys_create_footer_sns_fa_link( $class, $option_key, $fa_class ){
		return ys_create_footer_sns_link( $class, $option_key, 'fa fa-' . $fa_class );
	}
}