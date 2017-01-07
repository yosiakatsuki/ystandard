<?php
//------------------------------------------------------------------------------
//
//	設定取得
//
//------------------------------------------------------------------------------
// 設定オブジェクト
$ys_settings = null;
// AMP判断用変数
$ys_amp = null;

//-----------------------------------------------
//	設定取得
//-----------------------------------------------
if (!function_exists( 'ys_settings')) {
	function ys_settings() {
		global $ys_settings;

		if($ys_settings !== null) {
			return $ys_settings;
		}

		// 基本設定
		$ys_copyright_year = esc_attr( get_option('ys_copyright_year',date_i18n('Y')) );
		$ys_ga_tracking_id = esc_attr( get_option('ys_ga_tracking_id','') );
		$ys_sns_share_tweet_via = get_option('ys_sns_share_tweet_via',0);
		$ys_sns_share_tweet_via_account = esc_attr( get_option('ys_sns_share_tweet_via_account','') );
		$ys_archive_noindex_category = get_option('ys_archive_noindex_category',0) ;
		$ys_archive_noindex_tag = get_option('ys_archive_noindex_tag',1) ;
		$ys_archive_noindex_author = get_option('ys_archive_noindex_author',1) ;
		$ys_archive_noindex_date = get_option('ys_archive_noindex_date',1) ;
		$ys_show_sidebar_mobile = get_option('ys_show_sidebar_mobile',1) ;
		$ys_ogp_fb_app_id = esc_attr( get_option('ys_ogp_fb_app_id','') );
		$ys_ogp_fb_admins = esc_attr( get_option('ys_ogp_fb_admins','') );
		$ys_twittercard_user = esc_attr( get_option('ys_twittercard_user','') );
		$ys_ogp_default_image = esc_url( get_option('ys_ogp_default_image','') );

		// 高度な設定
		$ys_amp_enable = get_option('ys_amp_enable',0) ;

		// AMP設定
		$ys_amp_normal_link = get_option('ys_amp_normal_link',0) ;
		$ys_amp_del_script = get_option('ys_amp_del_script',0) ;
		$ys_amp_del_style = get_option('ys_amp_del_style',0) ;

		// 配列作成
		$result = array(
										'ys_copyright_year' => $ys_copyright_year	//発行年
										,'ys_ga_tracking_id' => $ys_ga_tracking_id	//Google AanalyticsトラッキングID
										,'ys_sns_share_tweet_via' => $ys_sns_share_tweet_via	//Tweetポタンに via を出力するか
										,'ys_sns_share_tweet_via_account' => $ys_sns_share_tweet_via_account	//Twitter via アカウント
										,'ys_archive_noindex_category' => $ys_archive_noindex_category	//カテゴリー一覧をnoindexにする
										,'ys_archive_noindex_tag' => $ys_archive_noindex_tag	//タグ一覧をnoindexにする
										,'ys_archive_noindex_author' => $ys_archive_noindex_author	//投稿者一覧をnoindexにする
										,'ys_archive_noindex_date' => $ys_archive_noindex_date	//日別一覧をnoindexにする
										,'ys_show_sidebar_mobile' => $ys_show_sidebar_mobile	//モバイル表示でサイドバーを出力する
										,'ys_ogp_fb_app_id' => $ys_ogp_fb_app_id	//Facebook app id
										,'ys_ogp_fb_admins' => $ys_ogp_fb_admins	//facebook admins
										,'ys_twittercard_user' => $ys_twittercard_user	//Twitterカードのユーザー名
										,'ys_ogp_default_image' => $ys_ogp_default_image	//OGPデフォルト画像

										,'ys_amp_enable' => $ys_amp_enable	//AMPページを有効化するか

										,'ys_amp_normal_link' => $ys_amp_normal_link	//通常ビューへのリンクを表示する
										,'ys_amp_del_script' => $ys_amp_del_script	//scriptタグを削除してAMPページを作成
										,'ys_amp_del_style' => $ys_amp_del_style	//インラインで書かれたstyle属性を削除してAMPページを作成
									);

		return apply_filters('ys_settings',$result);
	}
}


//-----------------------------------------------
//	OGP項目取得
//-----------------------------------------------
if (!function_exists( 'ys_settings_get_ogp')) {
	function ys_settings_get_ogp() {

		// 設定
		$settings = ys_settings();

		// OGP設定取得
		$ogpimage = $settings['ys_ogp_default_image'];
		if(is_single() || is_page()){
			// アイキャッチが存在しない場合OGPデフォルト画像を指定しておく
			$ogpimage = ys_utilities_get_post_thumbnail_url('full',$ogpimage);
		}

		return array(
			'app_id'=>$settings['ys_ogp_fb_app_id'],
			'admins'=>$settings['ys_ogp_fb_admins'],
			'image'=>$ogpimage
		);
	}
}

?>