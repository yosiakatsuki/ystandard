<?php
/**
 * 初期化関連
 *
 * @package ystandard
 * @author  yosiakatsuki
 * @license GPL-3.0+
 */

namespace ystandard;

defined( 'ABSPATH' ) || die();

/**
 * Class Init
 *
 * @package ystandard
 */
class Init {

	/**
	 * Init constructor.
	 */
	public function __construct() {
		/**
		 * head関連の処理はHeadクラスにまとめています。
		 *
		 * @see inc/head/class-head.php
		 */
		add_action( 'after_setup_theme', [ $this, 'load_textdomain' ], 9 );
		add_action( 'after_setup_theme', [ $this, 'theme_support' ] );
		add_filter( 'body_class', [ $this, 'add_body_class' ] );
	}

	/**
	 * body_classにクラスを追加
	 *
	 * @param array $classes クラス.
	 *
	 * @return mixed
	 */
	public function add_body_class( $classes ) {

		$classes[] = 'ystandard';
		$classes[] = 'ystd'; // CSS用の省略形.

		return $classes;
	}

	/**
	 * Theme Support関連
	 *
	 * @return void
	 */
	public function theme_support() {
		// 投稿とコメントのフィード出力.
		add_theme_support( 'automatic-feed-links' );
		// タイトル出力.
		add_theme_support( 'title-tag' );
		// カスタム背景.
		add_theme_support( 'custom-background' );
		// アイキャッチ画像を有効.
		add_theme_support( 'post-thumbnails' );
		// 埋め込みをレスポンシブに.
		add_theme_support( 'responsive-embeds' );
	}

	/**
	 * 翻訳ファイル読み込み
	 *
	 * @return void
	 */
	public function load_textdomain() {
		load_theme_textdomain(
			'ystandard',
			get_template_directory() . '/languages'
		);
	}
}

new Init();
