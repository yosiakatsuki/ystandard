<?php
/**
 * ウィジェット
 *
 * @package ystandard
 * @author  yosiakatsuki
 * @license GPL-2.0+
 */

namespace ystandard;

defined( 'ABSPATH' ) || die();

/**
 * Class Widget
 *
 * @package ystandard
 */
class Widget {

	/**
	 * フックやショートコードの登録
	 */
	public function register() {
		add_action( 'widgets_init', [ $this, 'widget_init' ] );
		add_action( 'ys_set_singular_content', [ $this, 'set_widget' ] );
	}

	/**
	 * サイドバーが有効か
	 *
	 * @return bool
	 */
	public static function is_active_sidebar() {
		$pre = apply_filters( 'ys_pre_is_active_sidebar', null );
		if ( ! is_null( $pre ) ) {
			return $pre;
		}
		if ( Template::is_mobile() && Option::get_option_by_bool( 'ys_hide_sidebar_mobile', false ) ) {
			return false;
		}
		if ( ! is_active_sidebar( 'sidebar-widget' ) && ! is_active_sidebar( 'sidebar-fixed' ) ) {
			return false;
		}
		if ( Template::is_one_column() ) {
			return false;
		}

		return true;
	}

	/**
	 * フィルターのセット
	 */
	public function set_widget() {
		Content::set_singular_header(
			'widget',
			[ __CLASS__, 'singular_header_widget' ]
		);
		Content::set_singular_footer(
			'widget',
			[ __CLASS__, 'singular_footer_widget' ]
		);
	}

	/**
	 * 記事上ウィジェット
	 */
	public static function singular_header_widget() {

		if ( is_single() && is_active_sidebar( 'before-content' ) ) {
			self::before_post();
		}
		if ( is_page() && is_active_sidebar( 'before-content-page' ) ) {
			self::before_page();
		}
	}

	/**
	 * 記事下ウィジェット
	 */
	public static function singular_footer_widget() {
		if ( is_single() && is_active_sidebar( 'after-content' ) ) {
			self::after_post();
		}
		if ( is_page() && is_active_sidebar( 'after-content-page' ) ) {
			self::after_page();
		}
	}

	/**
	 * 投稿 コンテンツ前ウィジェット
	 */
	public static function before_post() {

		if ( ! apply_filters( 'ys_post_before_widget', true ) ) {
			return;
		}

		ob_start();
		dynamic_sidebar( 'before-content' );

		echo ob_get_clean();
	}

	/**
	 * 投稿 コンテンツ後ウィジェット
	 */
	public static function after_post() {

		if ( ! apply_filters( 'ys_post_after_widget', true ) ) {
			return;
		}

		ob_start();
		dynamic_sidebar( 'after-content' );

		echo ob_get_clean();
	}

	/**
	 * 固定ページ コンテンツ前ウィジェット
	 */
	public static function before_page() {

		if ( ! apply_filters( 'ys_page_before_widget', true ) ) {
			return;
		}

		ob_start();
		dynamic_sidebar( 'before-content-page' );

		echo ob_get_clean();
	}

	/**
	 * 固定ページ コンテンツ後ウィジェット
	 */
	public static function after_page() {

		if ( ! apply_filters( 'ys_page_after_widget', true ) ) {
			return;
		}

		ob_start();
		dynamic_sidebar( 'after-content-page' );

		echo ob_get_clean();
	}

	/**
	 * Widget init.
	 */
	public function widget_init() {
		/**
		 * サイドバー
		 */
		register_sidebar(
			[
				'name'          => 'サイドバー',
				'id'            => 'sidebar-widget',
				'description'   => 'サイドバー',
				'before_widget' => '<div id="%1$s" class="widget %2$s">',
				'after_widget'  => '</div>',
				'before_title'  => '<h2 class="widget-title">',
				'after_title'   => '</h2>',
			]
		);
		register_sidebar(
			[
				'name'          => 'サイドバー（追従）',
				'id'            => 'sidebar-fixed',
				'description'   => 'サイドバー',
				'before_widget' => '<div id="%1$s" class="widget %2$s">',
				'after_widget'  => '</div>',
				'before_title'  => '<h2 class="widget-title">',
				'after_title'   => '</h2>',
			]
		);
		/**
		 * 記事上下エリア(投稿)
		 */
		register_sidebar(
			[
				'name'          => '記事上エリア(投稿)',
				'id'            => 'before-content',
				'description'   => '投稿詳細ページの記事直上に表示されるウィジェット。',
				'before_widget' => '<div id="%1$s" class="content-widget before-content-widget %2$s">',
				'after_widget'  => '</div>',
				'before_title'  => '<h2 class="widget-title">',
				'after_title'   => '</h2>',
			]
		);
		/**
		 * 記事下CTAエリア(投稿)
		 */
		register_sidebar(
			[
				'name'          => '記事下エリア(投稿)',
				'id'            => 'after-content',
				'description'   => '投稿詳細ページの記事直下に表示されるウィジェット。',
				'before_widget' => '<div id="%1$s" class="content-widget after-content-widget %2$s">',
				'after_widget'  => '</div>',
				'before_title'  => '<h2 class="widget-title">',
				'after_title'   => '</h2>',
			]
		);
		/**
		 * 記事上下エリア(固定ページ)
		 */
		register_sidebar(
			[
				'name'          => '記事上エリア(固定ページ)',
				'id'            => 'before-content-page',
				'description'   => '固定ページ詳細ページの記事直上に表示されるウィジェット。',
				'before_widget' => '<div id="%1$s" class="content-widget before-content-widget %2$s">',
				'after_widget'  => '</div>',
				'before_title'  => '<h2 class="widget-title">',
				'after_title'   => '</h2>',
			]
		);
		/**
		 * 記事下CTAエリア(固定ページ)
		 */
		register_sidebar(
			[
				'name'          => '記事下エリア(固定ページ)',
				'id'            => 'after-content-page',
				'description'   => '固定ページ詳細ページの記事直下に表示されるウィジェット。',
				'before_widget' => '<div id="%1$s" class="content-widget after-content-widget %2$s">',
				'after_widget'  => '</div>',
				'before_title'  => '<h2 class="widget-title">',
				'after_title'   => '</h2>',
			]
		);
	}
}

$class_widget = new Widget();
$class_widget->register();
