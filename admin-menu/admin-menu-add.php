<?php
//------------------------------------------------------------------------------
//
//	管理画面メニュー追加
//
//------------------------------------------------------------------------------

//-----------------------------------------------
//	メニュー追加
//-----------------------------------------------
function ys_add_admin_menu() {

	$theme_name = get_template();

  add_menu_page(
        $theme_name.'設定',
        $theme_name.'設定',
        'manage_options',
        'ys_main_settings',
        'load_ys_main_settings',
        '',
        3);
}
add_action( 'admin_menu', 'ys_add_admin_menu' );



//-----------------------------------------------
//	メインメニュー項目定義
//-----------------------------------------------
function ys_register_main_settings() {
	register_setting( 'ys_main_settings', 'ys_ga_tracking_id' );
  register_setting( 'ys_main_settings', 'ys_archive_noindex_category','ys_sanitize_checkbox' );
  register_setting( 'ys_main_settings', 'ys_archive_noindex_tag','ys_sanitize_checkbox' );
  register_setting( 'ys_main_settings', 'ys_archive_noindex_author','ys_sanitize_checkbox' );
  register_setting( 'ys_main_settings', 'ys_archive_noindex_date','ys_sanitize_checkbox' );
  register_setting( 'ys_main_settings', 'ys_show_sidebar_mobile' );
  register_setting( 'ys_main_settings', 'ys_ogp_fb_app_id' );
  register_setting( 'ys_main_settings', 'ys_ogp_fb_admins' );
  register_setting( 'ys_main_settings', 'ys_twittercard_user' );
  register_setting( 'ys_main_settings', 'ys_ogp_default_image' );
}
add_action( 'admin_init', 'ys_register_main_settings' );



//-----------------------------------------------
//	メインメニュー呼び出し
//-----------------------------------------------
function load_ys_main_settings() {
  include TEMPLATEPATH . '/admin-menu/main-settings.php';
}

?>