<?php
/**
 * Init
 *
 * @package ystandard
 * @author  yosiakatsuki
 * @license GPL-2.0+
 */

if ( ! function_exists( 'ys_init' ) ) {
	/**
	 * 初期化
	 */
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
					'height'      => 100,
					'width'       => 100,
					'flex-height' => true,
					'flex-width'  => true,
				)
			)
		);
		/**
		 * カスタムヘッダー
		 */
		add_theme_support(
			'custom-header',
			array(
				'width'              => 1920,
				'height'             => 1080,
				'flex-width'         => true,
				'flex-height'        => true,
				'header-text'        => true,
				'video'              => true,
				'default-text-color' => '000000',
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
		 * テキストウィジェットでショートコードを有効にする
		 */
		add_filter( 'widget_text', 'do_shortcode' );
		/**
		 * テーマカスタマイザーでウィジェットの編集をしやすくする
		 */
		add_theme_support( 'customize-selective-refresh-widgets' );
		/**
		 * WPバージョン削除等
		 */
		ys_remove_action();
		/**
		 * 絵文字の削除
		 */
		ys_remove_emoji();
		/**
		 * OEmbedの削除
		 */
		ys_remove_oembed();
		/**
		 * [yStandard]ブログカード機能
		 */
		ys_blog_card_embed_register_handler();
		/**
		 * カテゴリー説明の拡張
		 */
		ys_tax_dscr_allowed_option();
		/**
		 * Gutenbergサポート
		 */
		ystd_theme_support_gutenberg();
		
	}
}
add_action( 'after_setup_theme', 'ys_init' );

if ( ! function_exists( 'ys_remove_action' ) ) {
	/**
	 * いろいろ削除
	 */
	function ys_remove_action() {

		// WPのバージョン削除.
		remove_action( 'wp_head', 'wp_generator' );

		// WP標準のcanonicalとnext,prevを削除.
		remove_action( 'wp_head', 'adjacent_posts_rel_link_wp_head' );
		remove_action( 'wp_head', 'rel_canonical' );
	}
}

if ( ! function_exists( 'ys_remove_emoji' ) ) {
	/**
	 * 絵文字無効化
	 */
	function ys_remove_emoji() {
		if ( ! ys_is_active_emoji() ) {
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

if ( ! function_exists( 'ys_remove_oembed' ) ) {
	/**
	 * Embed無効化
	 */
	function ys_remove_oembed() {
		if ( ! ys_is_active_oembed() ) {
			add_filter( 'embed_oembed_discover', '__return_false' );
			remove_action( 'wp_head', 'rest_output_link_wp_head' );
			remove_action( 'wp_head', 'wp_oembed_add_discovery_links' );
			remove_action( 'wp_head', 'wp_oembed_add_host_js' );
		}
	}
}


/**
 * Gutenbergテーマサポート
 */
function ystd_theme_support_gutenberg() {
	/**
	 * Gutenbergデフォルトスタイル読み込み
	 */
	add_theme_support( 'wp-block-styles' );
	/**
	 * 幅広画像のサポート
	 */
	add_theme_support( 'align-wide' );
	/**
	 * エディタースタイルの適用
	 */
	if ( ys_get_option( 'ys_admin_enable_block_editor_style' ) ) {
		add_theme_support( 'editor-styles' );
		add_editor_style( 'css/ys-block-editor-style.min.css' );
		add_editor_style( 'user-custom-editor-style.css' );
	}
}