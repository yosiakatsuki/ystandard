<?php
if( ! function_exists( 'ys_init' ) ) {
	function ys_init() {
		/**
		 * コンテンツ幅
		 */
		if ( ! isset( $content_width ) ) {
			$content_width = 800;
		}
		/**
		 * 投稿とコメントのフィード出力
		 */
		add_theme_support( 'automatic-feed-links' );
		/**
		 * タイトル出力
		 */
		add_theme_support( 'title-tag' );
		/**
		 * メニュー有効化
		 */
		register_nav_menus(
			array(
					'global' => 'グローバルナビゲーション',
					'footer' => 'フッターメニュー',
				)
			);
		/**
		 * HTML5
		 */
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
		/**
		 * カスタムロゴ
		 */
		add_theme_support(
			'custom-logo',
			apply_filters(
					'ys_custom_logo_param',
					array(
						'height' => 100,
						'width'	 => 100,
						'flex-height' => true,
						'flex-width' => true
					)
			)
		);
		/**
		 * カスタム背景
		 */
		add_theme_support( 'custom-background' );
		/**
		 * アイキャッチ画像を有効
		 */
		add_theme_support( 'post-thumbnails' );
		/**
		 * yStandardサムネイルサイズ
		 */
		add_image_size( 'yslistthumb', 686, 412, true );
		/**
		 * テキストウィジェットでショートコードを有効にする
		 */
		add_filter( 'widget_text', 'do_shortcode' );

		add_theme_support( 'customize-selective-refresh-widgets' );

		ys_remove_action();

		ys_remove_emoji();

		ys_remove_oembed();
	}
}
add_action( 'after_setup_theme', 'ys_init' );

/**
 * いろいろ削除
 */
if( ! function_exists( 'ys_remove_action' ) ) {
	function ys_remove_action() {

		// WPのバージョン削除
		remove_action('wp_head', 'wp_generator');

		// WP標準のcanonicalとnext,prevを削除
		remove_action('wp_head', 'adjacent_posts_rel_link_wp_head');
		remove_action('wp_head', 'rel_canonical');
	}
}
/**
 * 絵文字無効化
 */
if ( ! function_exists( 'ys_remove_emoji' ) ) {
	function ys_remove_emoji() {
		if( ! ys_is_active_emoji() ) {
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
}
/**
 * embed無効化
 */
if ( ! function_exists( 'ys_remove_oembed' ) ) {
	function ys_remove_oembed() {
		if( ! ys_is_active_oembed() ) {
			//Embeds
			add_filter('embed_oembed_discover', '__return_false');
			remove_action('wp_head','rest_output_link_wp_head');
			remove_action('wp_head','wp_oembed_add_discovery_links');
			remove_action('wp_head','wp_oembed_add_host_js');
		}
	}
}