<?php
/**
 *
 *	管理画面メニュー追加
 *
 */

/**
 *	メニュー追加
 */
function ys_add_admin_menu() {

	$theme_name = get_template();

	add_menu_page(
				$theme_name.'設定',
				$theme_name.'設定',
				'manage_options',
				'ys_settings_start',
				'load_ys_settings_start',
				'',
				3);

	// 簡単設定ページ追加
	add_submenu_page(
		'ys_settings_start',
		'簡単設定',
		'簡単設定',
		'manage_options',
		'ys_collective_settings',
		'load_ys_collective_settings'
	);


	// 高度な設定ページ追加
	add_submenu_page(
		'ys_settings_start',
		'高度な設定',
		'高度な設定',
		'manage_options',
		'ys_advanced_settings',
		'load_ys_advanced_settings'
	);

		add_submenu_page(
			'ys_settings_start',
			'AMP設定',
			'AMP設定',
			'manage_options',
			'ys_amp_settings',
			'load_ys_amp_settings'
		);
	// 便利ツール
	add_submenu_page(
		'ys_settings_start',
		'便利ツール',
		'便利ツール',
		'manage_options',
		'ys_tools',
		'load_ys_tools'
	);

}
add_action( 'admin_menu', 'ys_add_admin_menu' );



/**
 *	設定項目定義
 */
function ys_register_settings() {
	// 基本設定
	// 基本設定 > Copyright
	register_setting( 'ys_main_settings', 'ys_title_separate' , 'sanitize_text_field');
	register_setting( 'ys_main_settings', 'ys_copyright_year' , 'sanitize_text_field');
	// 基本設定 > アクセス解析設定
	register_setting( 'ys_main_settings', 'ys_ga_tracking_id' , 'sanitize_text_field');

	// 基本設定 > SNSシェアボタン設定
	register_setting( 'ys_main_settings', 'ys_sns_share_on_entry_header','ys_utilities_sanitize_checkbox' );
	register_setting( 'ys_main_settings', 'ys_sns_share_on_below_entry','ys_utilities_sanitize_checkbox' );

	// 基本設定 > Twitterシェアボタン設定
	register_setting( 'ys_main_settings', 'ys_sns_share_tweet_via','ys_utilities_sanitize_checkbox' );
	register_setting( 'ys_main_settings', 'ys_sns_share_tweet_via_account' );
	register_setting( 'ys_main_settings', 'ys_sns_share_tweet_related_account' );
	// 基本設定 > OGP設定
	register_setting( 'ys_main_settings', 'ys_ogp_fb_app_id' );
	register_setting( 'ys_main_settings', 'ys_ogp_fb_admins' );
	register_setting( 'ys_main_settings', 'ys_twittercard_user' );
	register_setting( 'ys_main_settings', 'ys_ogp_default_image' );
	// 基本設定 > 購読設定
	register_setting( 'ys_main_settings', 'ys_subscribe_url_twitter' );
	register_setting( 'ys_main_settings', 'ys_subscribe_url_facebook' );
	register_setting( 'ys_main_settings', 'ys_subscribe_url_googleplus' );
	register_setting( 'ys_main_settings', 'ys_subscribe_url_feedly' );

	// 基本設定 > サイト表示設定
	register_setting( 'ys_main_settings', 'ys_show_sidebar_mobile','ys_utilities_sanitize_checkbox'  );
	register_setting( 'ys_main_settings', 'ys_show_emoji','ys_utilities_sanitize_checkbox'  );
	register_setting( 'ys_main_settings', 'ys_show_oembed','ys_utilities_sanitize_checkbox'  );

	// 基本設定 > SNSフォロー
	register_setting( 'ys_main_settings', 'ys_follow_url_twitter' );
	register_setting( 'ys_main_settings', 'ys_follow_url_facebook' );
	register_setting( 'ys_main_settings', 'ys_follow_url_googlepuls' );
	register_setting( 'ys_main_settings', 'ys_follow_url_instagram' );
	register_setting( 'ys_main_settings', 'ys_follow_url_tumblr' );
	register_setting( 'ys_main_settings', 'ys_follow_url_youtube' );
	register_setting( 'ys_main_settings', 'ys_follow_url_github' );
	register_setting( 'ys_main_settings', 'ys_follow_url_pinterest' );
	register_setting( 'ys_main_settings', 'ys_follow_url_linkedin' );
	// 基本設定 > 投稿設定
	register_setting( 'ys_main_settings', 'ys_show_post_related','ys_utilities_sanitize_checkbox' );
	register_setting( 'ys_main_settings', 'ys_hide_post_paging','ys_utilities_sanitize_checkbox' );
	// 基本設定 > SEO設定
	register_setting( 'ys_main_settings', 'ys_archive_noindex_category','ys_utilities_sanitize_checkbox' );
	register_setting( 'ys_main_settings', 'ys_archive_noindex_tag','ys_utilities_sanitize_checkbox' );
	register_setting( 'ys_main_settings', 'ys_archive_noindex_author','ys_utilities_sanitize_checkbox' );
	register_setting( 'ys_main_settings', 'ys_archive_noindex_date','ys_utilities_sanitize_checkbox' );
	// 基本設定 > 広告設定
	register_setting( 'ys_main_settings', 'ys_advertisement_under_title' );
	register_setting( 'ys_main_settings', 'ys_advertisement_replace_more' );
	register_setting( 'ys_main_settings', 'ys_advertisement_under_content_left' );
	register_setting( 'ys_main_settings', 'ys_advertisement_under_content_right' );
	register_setting( 'ys_main_settings', 'ys_advertisement_under_title_sp' );
	register_setting( 'ys_main_settings', 'ys_advertisement_replace_more_sp' );
	register_setting( 'ys_main_settings', 'ys_advertisement_under_content_sp' );

	//高度な設定 - 投稿設定
	register_setting( 'ys_advanced_settings', 'ys_hide_post_thumbnail','ys_utilities_sanitize_checkbox' );
	//高度な設定 - css,javascript設定
	register_setting( 'ys_advanced_settings', 'ys_desabled_color_customizeser','ys_utilities_sanitize_checkbox' );
	register_setting( 'ys_advanced_settings', 'ys_load_script_twitter','ys_utilities_sanitize_checkbox' );
	register_setting( 'ys_advanced_settings', 'ys_load_script_facebook','ys_utilities_sanitize_checkbox' );
	register_setting( 'ys_advanced_settings', 'ys_load_cdn_jquery_url' );
	register_setting( 'ys_advanced_settings', 'ys_not_load_jquery','ys_utilities_sanitize_checkbox' );
	//高度な設定 - AMP設定
	register_setting( 'ys_advanced_settings', 'ys_amp_enable','ys_utilities_sanitize_checkbox' );

	// AMP設定
	register_setting( 'ys_amp_settings', 'ys_ga_tracking_id_amp' );
	register_setting( 'ys_amp_settings', 'ys_amp_share_fb_app_id' );
	register_setting( 'ys_amp_settings', 'ys_amp_normal_link' ,'ys_utilities_sanitize_checkbox');
	register_setting( 'ys_amp_settings', 'ys_amp_normal_link_share_btn' ,'ys_utilities_sanitize_checkbox');
	register_setting( 'ys_amp_settings', 'ys_amp_del_script' ,'ys_utilities_sanitize_checkbox');
	register_setting( 'ys_amp_settings', 'ys_amp_del_style' ,'ys_utilities_sanitize_checkbox');
	register_setting( 'ys_amp_settings', 'ys_show_entry_footer_widget_amp' ,'ys_utilities_sanitize_checkbox');
	// AMP設定 > 広告設定
	register_setting( 'ys_amp_settings', 'ys_amp_advertisement_under_title' );
	register_setting( 'ys_amp_settings', 'ys_amp_advertisement_replace_more' );
	register_setting( 'ys_amp_settings', 'ys_amp_advertisement_under_content' );




}
add_action( 'admin_init', 'ys_register_settings' );


/**
 *	スタートページ呼び出し
 */
function load_ys_settings_start() {
	include TEMPLATEPATH . '/inc/theme-option/ys-setting-start.php';
}

/**
 *	簡単設定呼び出し
 */
function load_ys_collective_settings() {
	include TEMPLATEPATH . '/inc/theme-option/collective-settings.php';
}

/**
 *	基本設定呼び出し
 */
function load_ys_main_settings() {
	include TEMPLATEPATH . '/inc/theme-option/main-settings.php';
}

/**
 *	高度な設定呼び出し
 */
function load_ys_advanced_settings() {
	include TEMPLATEPATH . '/inc/theme-option/advanced-settings.php';
}


/**
 *	AMPメニュー呼び出し
 */
function load_ys_amp_settings() {
	include TEMPLATEPATH . '/inc/theme-option/amp-settings.php';
}

/**
 *	便利ツール呼び出し
 */
function load_ys_tools() {
	include TEMPLATEPATH . '/inc/theme-option/ys-tools.php';
}

?>