<?php
//------------------------------------------------------------------------------
//
//	テーマの初期化
//
//------------------------------------------------------------------------------



//------------------------------------------------------------------------------
//	初期化
//------------------------------------------------------------------------------
if (!function_exists( 'ys_setup_initialize')) {
	function ys_setup_initialize() {

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
					'gloval' => 'グローバルナビゲーション',
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

		add_image_size('yslistthumb',500,300,true);

		// テキストウィジェットでショートコードを有効化
		add_filter('widget_text', 'do_shortcode' );

	}
}//if (!function_exists( 'ys_setup_initialize')) {
add_action( 'after_setup_theme', 'ys_setup_initialize' );



//------------------------------------------------------------------------------
//	いろいろ無効化
//------------------------------------------------------------------------------
if (!function_exists( 'ys_setup_remove_action')) {
	function ys_setup_remove_action() {

		// WPのバージョン削除
		remove_action('wp_head', 'wp_generator');

		// WP標準のcanonicalとnext,prevを削除
		remove_action('wp_head', 'adjacent_posts_rel_link_wp_head');
		remove_action('wp_head', 'rel_canonical');

	}
}
add_action( 'after_setup_theme', 'ys_setup_remove_action' );




if (!function_exists( 'ys_setup_remove_emoji')) {
	function ys_setup_remove_emoji() {

		//絵文字
		remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
		remove_action( 'admin_print_scripts', 'print_emoji_detection_script' );
		remove_action( 'wp_print_styles', 'print_emoji_styles' );
		remove_action( 'admin_print_styles', 'print_emoji_styles' );
		remove_filter( 'the_content_feed', 'wp_staticize_emoji' );
		remove_filter( 'comment_text_rss', 'wp_staticize_emoji' );
		remove_filter( 'wp_mail', 'wp_staticize_emoji_for_email' );

	}
}
add_action( 'after_setup_theme', 'ys_setup_remove_emoji' );




if (!function_exists( 'ys_setup_remove_oembed')) {
	function ys_setup_remove_oembed() {

		//Embeds
		remove_action('wp_head','rest_output_link_wp_head');
		remove_action('wp_head','wp_oembed_add_discovery_links');
		remove_action('wp_head','wp_oembed_add_host_js');

	}
}
add_action( 'after_setup_theme', 'ys_setup_remove_oembed' );




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
?>