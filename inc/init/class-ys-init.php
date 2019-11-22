<?php
/**
 * 初期化処理
 *
 * @package ystandard
 * @author  yosiakatsuki
 * @license GPL-2.0+
 */

/**
 * 初期化処理
 */
class YS_Init {
	/**
	 * YS_Init constructor.
	 */
	public function __construct() {
		add_action( 'after_setup_theme', array( $this, 'content_width' ), 1 );
		add_action( 'after_setup_theme', array( $this, 'after_setup_theme' ) );
		add_action( 'after_setup_theme', array( $this, 'remove_meta' ) );
		add_action( 'after_setup_theme', array( $this, 'tax_dscr_filter' ) );
		if ( ! ys_is_active_emoji() ) {
			add_action( 'after_setup_theme', array( $this, 'remove_emoji' ) );
		}
		if ( ! ys_is_active_oembed() ) {
			add_action( 'after_setup_theme', array( $this, 'remove_oembed' ) );
		}
	}

	/**
	 * コンテンツ幅の設定
	 */
	public function content_width() {
		global $content_width;

		if ( ! isset( $content_width ) ) {
			$content_width = 800;
		}
	}

	/**
	 * 初期化
	 */
	public function after_setup_theme() {
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
				'global'        => 'グローバルナビゲーション',
				'footer'        => 'フッターメニュー',
				'mobile-footer' => 'モバイルフッター',
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
				'default-text-color' => '222222',
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
		 * [yStandard]ブログカード機能
		 */
		ys_blog_card_embed_register_handler();
		/**
		 * Gutenbergサポート
		 */
		add_theme_support( 'wp-block-styles' );
		/**
		 * 幅広画像のサポート
		 */
		add_theme_support( 'align-wide' );
		/**
		 * レスポンシブ
		 */
		add_theme_support( 'responsive-embeds' );
		/**
		 * Gutenbergの文字サイズ選択設定
		 */
		add_theme_support( 'editor-font-sizes', ys_get_editor_font_sizes() );
		/**
		 * Gutenberg色設定
		 */
		add_theme_support( 'editor-color-palette', ys_get_editor_color_palette( false ) );
	}

	/**
	 * メタ情報の削除
	 */
	public function remove_meta() {
		// WPのバージョン削除.
		remove_action( 'wp_head', 'wp_generator' );

		// WP標準のcanonicalとnext,prevを削除.
		remove_action( 'wp_head', 'adjacent_posts_rel_link_wp_head' );
		remove_action( 'wp_head', 'rel_canonical' );
	}

	/**
	 * 絵文字の削除
	 */
	public function remove_emoji() {
		remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
		remove_action( 'admin_print_scripts', 'print_emoji_detection_script' );
		remove_action( 'wp_print_styles', 'print_emoji_styles' );
		remove_action( 'admin_print_styles', 'print_emoji_styles' );
		remove_filter( 'the_content_feed', 'wp_staticize_emoji' );
		remove_filter( 'comment_text_rss', 'wp_staticize_emoji' );
		remove_filter( 'wp_mail', 'wp_staticize_emoji_for_email' );
		add_filter( 'emoji_svg_url', '__return_false' );
	}

	/**
	 * Embed無効化
	 */
	public function remove_oembed() {
		add_filter( 'embed_oembed_discover', '__return_false' );
		remove_action( 'wp_head', 'rest_output_link_wp_head' );
		remove_action( 'wp_head', 'wp_oembed_add_discovery_links' );
		remove_action( 'wp_head', 'wp_oembed_add_host_js' );
	}

	/**
	 * タクソノミー説明の処理カスタマイズ
	 */
	public function tax_dscr_filter() {
		remove_filter( 'pre_term_description', 'wp_filter_kses' );
		add_filter( 'pre_term_description', 'wp_filter_post_kses' );
		if ( ! is_admin() ) {
			add_filter( 'term_description', 'do_shortcode' );
		}
	}
}