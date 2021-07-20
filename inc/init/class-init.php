<?php
/**
 * 初期化処理
 *
 * @package ystandard
 * @author  yosiakatsuki
 * @license GPL-2.0+
 */

namespace ystandard;

defined( 'ABSPATH' ) || die();

/**
 * 初期化処理
 */
class Init {
	/**
	 * YS_Init constructor.
	 */
	public function __construct() {
		add_action( 'after_setup_theme', [ $this, 'content_width' ], 1 );
		add_action( 'after_setup_theme', [ $this, 'init' ] );
		add_action( 'after_setup_theme', [ $this, 'remove_meta' ] );
		add_action( 'after_setup_theme', [ $this, 'tax_dscr_filter' ] );
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
	public function init() {
		load_theme_textdomain(
			'ystandard',
			get_template_directory() . '/languages'
		);
		// 投稿とコメントのフィード出力.
		add_theme_support( 'automatic-feed-links' );
		// タイトル出力.
		add_theme_support( 'title-tag' );
		// メニュー有効化.
		register_nav_menus(
			[
				'global'        => 'グローバルナビゲーション',
				'footer'        => 'フッターメニュー',
				'mobile-footer' => 'モバイルフッター',
			]
		);
		// HTML5.
		add_theme_support(
			'html5',
			[
				'navigation-widgets',
				'search-form',
				'comment-form',
				'comment-list',
				'gallery',
				'caption',
			]
		);
		// カスタムロゴ.
		add_theme_support(
			'custom-logo',
			apply_filters(
				'ys_custom_logo_param',
				[
					'height'      => 50,
					'width'       => 250,
					'flex-height' => true,
					'flex-width'  => true,
				]
			)
		);
		// カスタムヘッダー.
		add_theme_support(
			'custom-header',
			[
				'width'              => 1920,
				'height'             => 1080,
				'flex-width'         => true,
				'flex-height'        => true,
				'header-text'        => true,
				'video'              => true,
				'default-text-color' => '222222',
			]
		);
		// カスタム背景.
		add_theme_support( 'custom-background' );
		// アイキャッチ画像を有効.
		add_theme_support( 'post-thumbnails' );
		// テキストウィジェットでショートコードを有効にする.
		add_filter( 'widget_text', 'do_shortcode' );
		// テーマカスタマイザーでウィジェットの編集をしやすくする.
		add_theme_support( 'customize-selective-refresh-widgets' );
		// ブロックエディターサポート.
		add_theme_support( 'wp-block-styles' );
		// 幅広画像のサポート.
		add_theme_support( 'align-wide' );
		// レスポンシブ.
		add_theme_support( 'responsive-embeds' );
		// line-height(5.5~).
		add_theme_support( 'custom-line-height' );
		// カバーブロックの高さ単位(5.5~).
		add_theme_support( 'custom-units', 'px', 'rem', 'em', 'percentages', 'vh', 'vw' );
		// カスタム余白(5.6~).
		add_theme_support( 'custom-spacing' );
		// 固定ページで抜粋を有効化.
		add_post_type_support( 'page', 'excerpt' );
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

new Init();
