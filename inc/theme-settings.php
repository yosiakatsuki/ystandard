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
//	設定呼び出し
//-----------------------------------------------
if (!function_exists( 'ys_settings')) {
	function ys_settings() {
		global $ys_settings;

		if( null !== $ys_settings ) {
			return apply_filters( 'ys_settings', $ys_settings );
		}

		// 基本設定
	// 基本設定 > Copyright
		$ys_title_separate = esc_attr( get_option('ys_title_separate','') );
		$ys_copyright_year = esc_attr( get_option('ys_copyright_year',date_i18n('Y')) );
		$ys_copyright_year = ($ys_copyright_year == '') ? date_i18n('Y') : $ys_copyright_year;
	// 基本設定 > アクセス解析設定
		$ys_ga_tracking_id = esc_attr( get_option('ys_ga_tracking_id','') );

	// 基本設定 > SNSシェアボタン設定
		$ys_sns_share_on_entry_header = get_option('ys_sns_share_on_entry_header',0);
		$ys_sns_share_on_below_entry = get_option('ys_sns_share_on_below_entry',0);

	// 基本設定 > Twitterシェアボタン設定
		$ys_sns_share_tweet_via = get_option('ys_sns_share_tweet_via',0);
		$ys_sns_share_tweet_via_account = esc_attr( get_option('ys_sns_share_tweet_via_account','') );
		$ys_sns_share_tweet_related_account = esc_attr( get_option('ys_sns_share_tweet_related_account','') );
	// 基本設定 > OGP設定
		$ys_ogp_fb_app_id = esc_attr( get_option('ys_ogp_fb_app_id','') );
		$ys_ogp_fb_admins = esc_attr( get_option('ys_ogp_fb_admins','') );
		$ys_twittercard_user = esc_attr( get_option('ys_twittercard_user','') );
		$ys_ogp_default_image = esc_url( get_option('ys_ogp_default_image','') );

	// 基本設定 > サイト表示設定
		$ys_show_sidebar_mobile = get_option('ys_show_sidebar_mobile',1) ;
		$ys_show_emoji = get_option('ys_show_emoji',0) ;
		$ys_show_oembed = get_option('ys_show_oembed',0) ;

	// 基本設定 > SNSフォロー
		$ys_follow_url_twitter = esc_url( get_option('ys_follow_url_twitter','') );
		$ys_follow_url_facebook = esc_url( get_option('ys_follow_url_facebook','') );
		$ys_follow_url_googlepuls = esc_url( get_option('ys_follow_url_googlepuls','') );
		$ys_follow_url_instagram = esc_url( get_option('ys_follow_url_instagram','') );
		$ys_follow_url_tumblr = esc_url( get_option('ys_follow_url_tumblr','') );
		$ys_follow_url_youtube = esc_url( get_option('ys_follow_url_youtube','') );
		$ys_follow_url_github = esc_url( get_option('ys_follow_url_github','') );
		$ys_follow_url_pinterest = esc_url( get_option('ys_follow_url_pinterest','') );
		$ys_follow_url_linkedin = esc_url( get_option('ys_follow_url_linkedin','') );

	// 基本設定 > 購読リンク
		$ys_subscribe_url_twitter = esc_url( get_option('ys_subscribe_url_twitter','') );
		$ys_subscribe_url_facebook = esc_url( get_option('ys_subscribe_url_facebook','') );
		$ys_subscribe_url_googleplus = esc_url( get_option('ys_subscribe_url_googleplus','') );
		$ys_subscribe_url_feedly = esc_url( get_option('ys_subscribe_url_feedly','') );

	// 基本設定 > 投稿設定
		$ys_show_post_related = get_option('ys_show_post_related',1) ;
		$ys_hide_post_paging = get_option('ys_hide_post_paging',0) ;

	// 基本設定 > SEO設定
		$ys_archive_noindex_category = get_option('ys_archive_noindex_category',0) ;
		$ys_archive_noindex_tag = get_option('ys_archive_noindex_tag',1) ;
		$ys_archive_noindex_author = get_option('ys_archive_noindex_author',1) ;
		$ys_archive_noindex_date = get_option('ys_archive_noindex_date',1) ;
	// 基本設定 > 広告設定
		$ys_advertisement_under_title = get_option('ys_advertisement_under_title','') ;
		$ys_advertisement_replace_more = get_option('ys_advertisement_replace_more','') ;
		$ys_advertisement_under_content_left = get_option('ys_advertisement_under_content_left','') ;
		$ys_advertisement_under_content_right = get_option('ys_advertisement_under_content_right','') ;
		$ys_advertisement_under_title_sp = get_option('ys_advertisement_under_title_sp','') ;
		$ys_advertisement_replace_more_sp = get_option('ys_advertisement_replace_more_sp','') ;
		$ys_advertisement_under_content_sp = get_option('ys_advertisement_under_content_sp','') ;

		// 高度な設定 - 投稿設定
		$ys_hide_post_thumbnail = get_option('ys_hide_post_thumbnail',0) ;
		// 高度な設定 - css,javascript設定
		$ys_desabled_color_customizeser = get_option('ys_desabled_color_customizeser',0) ;
		$ys_load_script_twitter = get_option('ys_load_script_twitter',0) ;
		$ys_load_script_facebook = get_option('ys_load_script_facebook',0) ;
		$ys_load_cdn_jquery_url = esc_url( get_option('ys_load_cdn_jquery_url','') );
		$ys_not_load_jquery = get_option('ys_not_load_jquery',0) ;
		// 高度な設定 - AMP設定
		$ys_amp_enable = get_option('ys_amp_enable',0) ;

		// AMP設定
		$ys_ga_tracking_id_amp = esc_attr( get_option('ys_ga_tracking_id_amp','') );
		$ys_amp_share_fb_app_id = esc_attr( get_option('ys_amp_share_fb_app_id','') );
		$ys_amp_normal_link = get_option('ys_amp_normal_link',1) ;
		$ys_amp_normal_link_share_btn = get_option('ys_amp_normal_link_share_btn',1) ;
		$ys_amp_del_script = get_option('ys_amp_del_script',0) ;
		$ys_amp_del_style = get_option('ys_amp_del_style',0) ;
		$ys_show_entry_footer_widget_amp = get_option('ys_show_entry_footer_widget_amp',0) ;
	// AMP設定 > 広告設定
		$ys_amp_advertisement_under_title = get_option('ys_amp_advertisement_under_title','') ;
		$ys_amp_advertisement_replace_more = get_option('ys_amp_advertisement_replace_more','') ;
		$ys_amp_advertisement_under_content = get_option('ys_amp_advertisement_under_content','') ;


		$ys_v2_migration = get_option('ys_v2_migration',0) ;


		// 配列作成
		$result = array(
										'ys_title_separate' => $ys_title_separate,	//タイトルの区切り文字
										'ys_copyright_year' => $ys_copyright_year,	//発行年

										'ys_ga_tracking_id' => $ys_ga_tracking_id,	//Google AanalyticsトラッキングID

										'ys_sns_share_on_entry_header' => $ys_sns_share_on_entry_header,	//Tweetポタンに via を出力するか
										'ys_sns_share_on_below_entry' => $ys_sns_share_on_below_entry,	//Tweetポタンに via を出力するか

										'ys_sns_share_tweet_via' => $ys_sns_share_tweet_via,	//Tweetポタンに via を出力するか
										'ys_sns_share_tweet_via_account' => $ys_sns_share_tweet_via_account,	//Twitter via アカウント
										'ys_sns_share_tweet_related_account' => $ys_sns_share_tweet_related_account,	//Twitter related アカウント

										'ys_ogp_fb_app_id' => $ys_ogp_fb_app_id,	//Facebook app id
										'ys_ogp_fb_admins' => $ys_ogp_fb_admins,	//facebook admins
										'ys_twittercard_user' => $ys_twittercard_user,	//Twitterカードのユーザー名
										'ys_ogp_default_image' => $ys_ogp_default_image,	//OGPデフォルト画像

										'ys_show_sidebar_mobile' => $ys_show_sidebar_mobile,	//モバイル表示でサイドバーを出力する
										'ys_show_emoji' => $ys_show_emoji,	//絵文字を出力する
										'ys_show_oembed' => $ys_show_oembed,	//oembedを出力する

										'ys_follow_url_twitter' => $ys_follow_url_twitter,	//TwitterフォローURL
										'ys_follow_url_facebook' => $ys_follow_url_facebook,	//facebookフォローURL
										'ys_follow_url_googlepuls' => $ys_follow_url_googlepuls,	//google+フォローURL
										'ys_follow_url_instagram' => $ys_follow_url_instagram,	//instagramフォローURL
										'ys_follow_url_tumblr' => $ys_follow_url_tumblr,	//tumblrフォローURL
										'ys_follow_url_youtube' => $ys_follow_url_youtube,	//YouTubeフォローURL
										'ys_follow_url_github' => $ys_follow_url_github,	//GitHubフォローURL
										'ys_follow_url_pinterest' => $ys_follow_url_pinterest,	//PinterestフォローURL
										'ys_follow_url_linkedin' => $ys_follow_url_linkedin,	//linkedinフォローURL

										'ys_subscribe_url_twitter' => $ys_subscribe_url_twitter,	//Twitterフォローリンク
										'ys_subscribe_url_facebook' => $ys_subscribe_url_facebook,	//Facebookページフォローリンク
										'ys_subscribe_url_googleplus' => $ys_subscribe_url_googleplus,	//Google＋ページフォローリンク
										'ys_subscribe_url_feedly' => $ys_subscribe_url_feedly,	//Feedlyフォローリンク

										'ys_show_post_related' => $ys_show_post_related,	//関連記事を出力する
										'ys_hide_post_paging' => $ys_hide_post_paging,	//次の記事・前の記事を表示しない

										'ys_archive_noindex_category' => $ys_archive_noindex_category,	//カテゴリー一覧をnoindexにする
										'ys_archive_noindex_tag' => $ys_archive_noindex_tag,	//タグ一覧をnoindexにする
										'ys_archive_noindex_author' => $ys_archive_noindex_author,	//投稿者一覧をnoindexにする
										'ys_archive_noindex_date' => $ys_archive_noindex_date,	//日別一覧をnoindexにする

										'ys_advertisement_under_title' => $ys_advertisement_under_title,	//広告　タイトル下
										'ys_advertisement_replace_more' => $ys_advertisement_replace_more,	//moreタグ置換
										'ys_advertisement_under_content_left' => $ys_advertisement_under_content_left,	//記事下　左
										'ys_advertisement_under_content_right' => $ys_advertisement_under_content_right,	//記事下　右
										'ys_advertisement_under_title_sp' => $ys_advertisement_under_title_sp,	//広告　タイトル下 SP
										'ys_advertisement_replace_more_sp' => $ys_advertisement_replace_more_sp,	//moreタグ置換 SP
										'ys_advertisement_under_content_sp' => $ys_advertisement_under_content_sp,	//記事下 SP

										'ys_hide_post_thumbnail' => $ys_hide_post_thumbnail,	//個別ページでアイキャッチ画像を非表示にする
										'ys_desabled_color_customizeser' => $ys_desabled_color_customizeser,	//テーマカスタマイザーの色設定を無効にする
										'ys_load_script_twitter' => $ys_load_script_twitter,	//Twitter埋め込み用js読み込み
										'ys_load_script_facebook' => $ys_load_script_facebook,	//Facebook埋め込み用js読み込み
										'ys_load_cdn_jquery_url' => $ys_load_cdn_jquery_url,	//CDNにホストされているjQueryを読み込む（URLを設定）
										'ys_not_load_jquery' => $ys_not_load_jquery,	//jQueryを読み込まない
										'ys_amp_enable' => $ys_amp_enable,	//AMPページを有効化するか

										'ys_ga_tracking_id_amp' => $ys_ga_tracking_id_amp,	//AMPのGoogle Analyticsトラッキングコード
										'ys_amp_share_fb_app_id' => $ys_amp_share_fb_app_id,	//AMPのfacebookシェアボタン用App id
										'ys_amp_normal_link_share_btn' => $ys_amp_normal_link_share_btn,	//通常ビューへのリンクを表示する
										'ys_amp_normal_link' => $ys_amp_normal_link,	//通常ビューへのリンクを表示する
										'ys_amp_del_script' => $ys_amp_del_script,	//scriptタグを削除してAMPページを作成
										'ys_amp_del_style' => $ys_amp_del_style,	//インラインで書かれたstyle属性を削除してAMPページを作成
										'ys_show_entry_footer_widget_amp' => $ys_show_entry_footer_widget_amp,	//AMPページで記事下ウィジェットを表示する

										'ys_amp_advertisement_under_title' => $ys_amp_advertisement_under_title,	//広告　タイトル下
										'ys_amp_advertisement_replace_more' => $ys_amp_advertisement_replace_more,	//moreタグ置換
										'ys_amp_advertisement_under_content' => $ys_amp_advertisement_under_content,	//記事下　左

										'ys_v2_migration' => $ys_v2_migration	//記事下　左

									);

		$ys_settings = $result;

		return apply_filters( 'ys_settings', $ys_settings );
	}
}




//-----------------------------------------------
//	設定取得
//-----------------------------------------------
if (!function_exists( 'ys_get_setting')) {
	function ys_get_setting($name) {
		$settings = ys_settings();

		return apply_filters('ys_get_setting',$settings[$name],$name);
	}
}



//-----------------------------------------------
//	OGP項目取得
//-----------------------------------------------
if (!function_exists( 'ys_settings_get_ogp')) {
	function ys_settings_get_ogp() {

		// OGP設定取得
		$ogpimage = ys_get_setting('ys_ogp_default_image');
		if(is_single() || is_page()){
			// アイキャッチが存在しない場合OGPデフォルト画像を指定しておく
			$ogpimage = ys_utilities_get_post_thumbnail_url('full',$ogpimage);
		}

		return array(
			'app_id'=>ys_get_setting('ys_ogp_fb_app_id'),
			'admins'=>ys_get_setting('ys_ogp_fb_admins'),
			'image'=>$ogpimage
		);
	}
}

?>
