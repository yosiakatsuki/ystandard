<?php
//------------------------------------------------------------------------------
//
//	設定取得
//
//------------------------------------------------------------------------------

//-----------------------------------------------
//	設定呼び出し
//-----------------------------------------------
if (!function_exists( 'ys_settings')) {
	function ys_settings() {
		return apply_filters( 'ys_settings', ys_get_options() );
	}
}




//-----------------------------------------------
//	設定取得
//-----------------------------------------------
if (!function_exists( 'ys_get_setting')) {
	function ys_get_setting($name) {
		return apply_filters('ys_get_setting',ys_get_option($name),$name);
	}
}



//-----------------------------------------------
//	OGP項目取得
//-----------------------------------------------
if (!function_exists( 'ys_settings_get_ogp')) {
	function ys_settings_get_ogp() {

		// OGP設定取得
		$ogpimage = ys_get_setting('ys_ogp_default_image');
		if( is_single() || is_page() ){
			// アイキャッチが存在しない場合OGPデフォルト画像を指定しておく
			global $post;
			if( has_post_thumbnail( $post->ID ) ) {
				$ogpimage = get_the_post_thumbnail_url( $post->ID );
			}
		}

		return array(
			'app_id'=>ys_get_setting('ys_ogp_fb_app_id'),
			'admins'=>ys_get_setting('ys_ogp_fb_admins'),
			'image'=>$ogpimage
		);
	}
}