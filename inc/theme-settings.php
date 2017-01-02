<?php
//------------------------------------------------------------------------------
//
//	設定取得
//
//------------------------------------------------------------------------------
// 設定オブジェクト
$ys_settings = array();
// AMP判断用変数
$ys_amp = null;

/*
- 設定項目
	- 簡単設定
		- SNSまとめて設定
			- Twitter
				- アカウント名
			- Facebook
				- app_id
				- admins

	- 基本設定
		- アクセス解析設定
			- Google Analytics トラッキングID
		- シェアボタン設定
			- Twitterシェアにviaを付加する（要Twitterアカウント名設定）
			- Twitterアカウント名:@
		- OGP・Twitterカード設定
			- Facebook app_id
			- Facebook admins
			- Twitterアカウント名
			- OGPデフォルト画像
		- SEO対策設定
			- アーカイブページのnoindex設定

	- 高度な設定
		- AMPページを生成する（β）

	- AMP設定（β）
		- 通常ビューへのリンク表示
			- コンテンツ上部
			- シェアボタン下
		- AMP記事作成条件設定
			- scriptタグの削除
			- styleタグの削除
		- シェアボタン設定
			- Facebook app_id

*/

//-----------------------------------------------
//	OGP項目取得
//-----------------------------------------------
if (!function_exists( 'ys_option_get_ogp')) {
	function ys_option_get_ogp() {

		// OGP設定取得
		$ogpimage = esc_url( get_option('ys_ogp_default_image','') );
		if(is_single() || is_page()){
			// 存在しない場合OGPデフォルト画像を指定しておく
			$ogpimage = ys_utilities_get_post_thumbnail_url('full',$ogpimage);
		}

		return array(
			'app_id'=>esc_attr( get_option('ys_ogp_fb_app_id','') ),
			'admins'=>esc_attr( get_option('ys_ogp_fb_admins','') ),
			'image'=>$ogpimage
		);
	}
}

?>