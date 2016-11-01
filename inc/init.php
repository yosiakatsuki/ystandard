<?php
//------------------------------------------------------------------------------
//
//	テーマの初期化
//
//------------------------------------------------------------------------------



//------------------------------------------------------------------------------
//	初期化
//------------------------------------------------------------------------------
if (!function_exists( 'ys_init_initialize')) {
	function ys_init_initialize() {

		if ( ! isset( $content_width ) ) {
			$content_width = 900;
		}

		//投稿とコメントのフィード出す
		add_theme_support( 'automatic-feed-links' );

		//タイトル
		add_theme_support( 'title-tag' );

		//メニュー有効化
		register_nav_menus(
			array(
					'gloval' => 'グローバルメニュー',
					'footer' => 'フッターメニュー',
				)
			);

		//HTML5で出力
		add_theme_support(
			'html5',
				array(
					'search-form',
					'comment-form',
					'comment-list',
					'gallery',
					'caption',
				)
			);

		//カスタムロゴ
		add_theme_support(
			'custom-logo',
			ys_init_change_logo_size());

		// カスタム背景
		add_theme_support('custom-background');

		// アイキャッチ画像を有効に
		add_theme_support('post-thumbnails');

	}
}//if (!function_exists( 'ys_init_initialize')) {
add_action( 'after_setup_theme', 'ys_init_initialize' );




//------------------------------------------------------------------------------
//	カスタムロゴのサイズ指定用関数
//------------------------------------------------------------------------------
if (!function_exists( 'ys_init_change_logo_size')) {
	function ys_init_change_logo_size() {
		return array(
						'height' => 100,
						'width'	 => 100,
						'flex-height' => true,
						'flex-width' => true
					);
	}
}




//------------------------------------------------------------------------------
//	ウィジット有効化
//------------------------------------------------------------------------------
if (!function_exists( 'ys_init_widget_initialize')) {
	function ys_init_widget_initialize() {
		//右サイドバー
		register_sidebar( array(
			'name'					 => 'サイドバー',
			'id'						 => 'sidebar-main',
			'description'	   => '右サイドバー',
			'before_widget'  => '<section id="%1$s" class="widget %2$s">',
			'after_widget'	 => '</section>',
			'before_title'	 => '<h2 class="widget-title">',
			'after_title'	   => '</h2>',
		) );
	}
}
add_action( 'widgets_init', 'ys_init_widget_initialize' );



//------------------------------------------------------------------------------
//	いろいろ無効化
//------------------------------------------------------------------------------
if (!function_exists( 'ys_init_remove')) {
	function ys_init_remove() {

		// WPのバージョン削除
		remove_action('wp_head', 'wp_generator');
		// サイトアイコンを削除
		//remove_action('wp_head', 'wp_site_icon',99);

		//絵文字
    /*
		remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
		remove_action( 'admin_print_scripts', 'print_emoji_detection_script' );
		remove_action( 'wp_print_styles', 'print_emoji_styles' );
		remove_action( 'admin_print_styles', 'print_emoji_styles' );
		remove_filter( 'the_content_feed', 'wp_staticize_emoji' );
		remove_filter( 'comment_text_rss', 'wp_staticize_emoji' );
		remove_filter( 'wp_mail', 'wp_staticize_emoji_for_email' );
    */

		//Embeds
    /*
		remove_action('wp_head','rest_output_link_wp_head');
		remove_action('wp_head','wp_oembed_add_discovery_links');
		remove_action('wp_head','wp_oembed_add_host_js');
    */
	}
}
add_action( 'after_setup_theme', 'ys_init_remove' );


?>