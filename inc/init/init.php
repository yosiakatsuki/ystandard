<?php
/**
 * Init
 *
 * @package ystandard
 * @author  yosiakatsuki
 * @license GPL-2.0+
 */

/**
 * 初期化
 */
function ys_init() {
	global $content_width;
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
	add_theme_support( 'editor-color-palette', ys_get_editor_color_palette() );
}

add_action( 'after_setup_theme', 'ys_init' );

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

/**
 * Gutenberg文字サイズ設定
 *
 * @return array
 */
function ys_get_editor_font_sizes() {
	$size = array(
		array(
			'name'      => __( '極小', 'ystandard' ),
			'shortName' => __( 'x-small', 'ystandard' ),
			'size'      => 12,
			'slug'      => 'x-small',
		),
		array(
			'name'      => __( '小', 'ystandard' ),
			'shortName' => __( 'small', 'ystandard' ),
			'size'      => 14,
			'slug'      => 'small',
		),
		array(
			'name'      => __( '標準', 'ystandard' ),
			'shortName' => __( 'normal', 'ystandard' ),
			'size'      => 16,
			'slug'      => 'normal',
		),
		array(
			'name'      => __( '中', 'ystandard' ),
			'shortName' => __( 'medium', 'ystandard' ),
			'size'      => 18,
			'slug'      => 'medium',
		),
		array(
			'name'      => __( '大', 'ystandard' ),
			'shortName' => __( 'large', 'ystandard' ),
			'size'      => 20,
			'slug'      => 'large',
		),
		array(
			'name'      => __( '極大', 'ystandard' ),
			'shortName' => __( 'x-large', 'ystandard' ),
			'size'      => 22,
			'slug'      => 'x-large',
		),
		array(
			'name'      => __( '巨大', 'ystandard' ),
			'shortName' => __( 'xx-large', 'ystandard' ),
			'size'      => 26,
			'slug'      => 'xx-large',
		),
	);

	return apply_filters( 'ys_editor_font_sizes', $size );
}

/**
 * 色設定の定義
 *
 * @return array
 */
function ys_get_editor_color_palette() {
	$color = array(
		array(
			'name'  => '青',
			'slug'  => 'ys-blue',
			'color' => '#216497',
		),

		array(
			'name'  => '水色',
			'slug'  => 'ys-light-blue',
			'color' => '#37ADD7',
		),
		array(
			'name'  => '赤',
			'slug'  => 'ys-red',
			'color' => '#A42323',
		),
		array(
			'name'  => '緑',
			'slug'  => 'ys-green',
			'color' => '#3C773C',
		),
		array(
			'name'  => '黄',
			'slug'  => 'ys-yellow',
			'color' => '#ECA713',
		),
		array(
			'name'  => '紫',
			'slug'  => 'ys-purple',
			'color' => '#5C2F65',
		),
		array(
			'name'  => '灰色',
			'slug'  => 'ys-gray',
			'color' => '#757575',
		),
		array(
			'name'  => '薄灰色',
			'slug'  => 'ys-light-gray',
			'color' => '#FAFAFA',
		),
		array(
			'name'  => '黒',
			'slug'  => 'ys-black',
			'color' => '#000',
		),
		array(
			'name'  => '白',
			'slug'  => 'ys-white',
			'color' => '#fff',
		),
	);

	return apply_filters( 'ys_editor_color_palette', $color );
}