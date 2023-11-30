<?php
/**
 * フロントページ関連
 *
 * @package ystandard
 * @author  yosiakatsuki
 * @license GPL-3.0+
 */

namespace ystandard;

defined( 'ABSPATH' ) || die();

/**
 * class Front_Page.
 *
 * @package ystandard
 */
class Front_Page {

	/**
	 * Constructor.
	 */
	public function __construct() {
		add_filter( 'body_class', [ $this, 'add_body_class' ] );
	}

	/**
	 * body_classにクラスを追加
	 *
	 * @param array $classes クラス.
	 *
	 * @return array
	 */
	public function add_body_class( $classes ) {
		if ( self::is_single_front_page() ) {
			$classes[] = 'is-front-page';
		}

		return $classes;
	}

	/**
	 * フロントページのテンプレート情報を取得
	 *
	 * @return string
	 */
	public static function get_front_page_template() {
		// 設定→表示設定取得.
		$type = get_option( 'show_on_front' );

		if ( 'page' === $type ) {
			// 固定ページの場合.
			$template      = 'page';
			$page_template = get_page_template_slug();

			// ページテンプレートのチェック.
			if ( $page_template ) {
				if ( file_exists( get_theme_file_path( $page_template ) ) || file_exists( $page_template ) ) {
					$template = str_replace( '.php', '', $page_template );
				}
			}
		} else {
			// 最新の投稿の場合.
			$template = 'home';
		}

		return $template;
	}

	/**
	 * 固定ページで設定されたフロントページかどうか
	 *
	 * @return bool
	 */
	public static function is_single_front_page() {
		if ( 'page' === get_option( 'show_on_front' ) ) {
			return is_front_page();
		}

		return false;
	}

	/**
	 * TOPページ(フロントページもしくは最新の投稿一覧)か
	 *
	 * @return bool
	 */
	public static function is_top_page() {
		if ( 'page' === get_option( 'show_on_front' ) ) {
			// ホームページの表示が固定ページの場合.
			return is_front_page();
		} else {
			// 最新の投稿の場合、1ページ目が表示されているか.
			if ( is_home() && ! is_paged() ) {
				return true;
			}
		}

		return false;
	}
}

new Front_Page();
