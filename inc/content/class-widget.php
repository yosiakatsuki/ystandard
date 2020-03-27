<?php
/**
 * ウィジェット
 *
 * @package ystandard
 * @author  yosiakatsuki
 * @license GPL-2.0+
 */

namespace ystandard;

/**
 * Class Widget
 *
 * @package ystandard
 */
class Widget {

	/**
	 * Widget constructor.
	 */
	public function __construct() {
		add_action( 'widgets_init', [ $this, 'widget_init' ] );
		add_action( 'wp', [ $this, 'set_content_widget' ] );
	}

	/**
	 * コンテンツ上下ウィジェットのセット
	 */
	public function set_content_widget() {
		if ( is_single() ) {
			if ( is_active_sidebar( 'before-content' ) ) {
				add_filter(
					'the_content',
					[ $this, 'before_post' ],
					Option::get_option_by_int( 'ys_post_before_content_widget_priority', 10 )
				);
			}
			if ( is_active_sidebar( 'after-content' ) ) {
				add_filter(
					'the_content',
					[ $this, 'after_post' ],
					Option::get_option_by_int( 'ys_post_after_content_widget_priority', 10 )
				);
			}
		}
		if ( is_page() ) {
			if ( is_active_sidebar( 'before-content-page' ) ) {
				add_filter(
					'the_content',
					[ $this, 'before_page' ],
					Option::get_option_by_int( 'ys_page_before_content_widget_priority', 10 )
				);
			}
			if ( is_active_sidebar( 'after-content-page' ) ) {
				add_filter(
					'the_content',
					[ $this, 'after_page' ],
					Option::get_option_by_int( 'ys_page_after_content_widget_priority', 10 )
				);
			}
		}
	}

	/**
	 * 投稿 コンテンツ前ウィジェット
	 *
	 * @param string $content コンテンツ.
	 *
	 * @return string
	 */
	public function before_post( $content ) {
		ob_start();
		dynamic_sidebar( 'before-content' );

		return ob_get_clean() . $content;
	}
	/**
	 * 投稿 コンテンツ後ウィジェット
	 *
	 * @param string $content コンテンツ.
	 *
	 * @return string
	 */
	public function after_post( $content ) {
		ob_start();
		dynamic_sidebar( 'after-content' );

		return $content . ob_get_clean();
	}
	/**
	 * 固定ページ コンテンツ前ウィジェット
	 *
	 * @param string $content コンテンツ.
	 *
	 * @return string
	 */
	public function before_page( $content ) {
		ob_start();
		dynamic_sidebar( 'before-content-page' );

		return ob_get_clean() . $content;
	}
	/**
	 * 固定ページ コンテンツ後ウィジェット
	 *
	 * @param string $content コンテンツ.
	 *
	 * @return string
	 */
	public function after_page( $content ) {
		ob_start();
		dynamic_sidebar( 'after-content-page' );

		return $content . ob_get_clean();
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

new Widget();
