<?php
//------------------------------------------------------------------------------
//
//	設定項目取得
//
//------------------------------------------------------------------------------




//-----------------------------------------------
//	投稿作成メニューに項目追加
//-----------------------------------------------
if (!function_exists( 'ys_option_get_ogp')) {
	function ys_option_get_ogp() {

		// OGP設定取得
		$ogpimage = esc_url( get_option('ys_ogp_default_image','') );
		if(is_single() || is_page()){
			// 存在しない場合OGPデフォルト画像を指定しておく
			$ogpimage = ys_utilities_get_post_thumbnail_url(0,'full',$ogpimage);
		}

		return array(
			'app_id'=>esc_attr( get_option('ys_ogp_fb_app_id','') ),
			'admins'=>esc_attr( get_option('ys_ogp_fb_admins','') ),
			'image'=>$ogpimage
		);
	}
}



?>