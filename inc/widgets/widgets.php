<?php
/**
 * ウィジェト
 *
 * @package ystandard
 * @author  yosiakatsuki
 * @license GPL-2.0+
 */

/**
 * クラス読み込み
 */
require_once __DIR__ . '/class-ys-widget-utility.php';
require_once __DIR__ . '/class-ys-widget-base.php';
require_once __DIR__ . '/class-ys-widget-get-posts.php';
require_once __DIR__ . '/class-ys-widget-post-ranking.php';
require_once __DIR__ . '/class-ys-widget-recent-posts.php';
require_once __DIR__ . '/class-ys-widget-custom-html.php';
require_once __DIR__ . '/class-ys-widget-text.php';
require_once __DIR__ . '/class-ys-widget-share-button.php';
require_once __DIR__ . '/class-ys-widget-post-taxonomy.php';
require_once __DIR__ . '/class-ys-widget-parts.php';

/**
 * ウィジット有効化
 *
 * @return void
 */
function ys_widget_init() {
	$current_url    = ys_get_page_url();
	$customizer_url = esc_url( add_query_arg( 'return', urlencode( $current_url ), wp_customize_url() ) );
	/**
	 * サイドバー
	 */
	register_sidebar(
		array(
			'name'          => 'サイドバー',
			'id'            => 'sidebar-widget',
			'description'   => 'サイドバー',
			'before_widget' => '<div id="%1$s" class="widget %2$s">',
			'after_widget'  => '</div>',
			'before_title'  => '<h2 class="widget-title">',
			'after_title'   => '</h2>',
		)
	);
	register_sidebar(
		array(
			'name'          => 'サイドバー（追従）',
			'id'            => 'sidebar-fixed',
			'description'   => 'サイドバー',
			'before_widget' => '<div id="%1$s" class="widget %2$s">',
			'after_widget'  => '</div>',
			'before_title'  => '<h2 class="widget-title">',
			'after_title'   => '</h2>',
		)
	);
	/**
	 * 記事上下エリア
	 */
	register_sidebar(
		array(
			'name'          => '記事上エリア',
			'id'            => 'before-content',
			'description'   => '記事直上に表示されるウィジェット。<a href="' . $customizer_url . '">※カスタマイザーの「デザイン設定」→「投稿ページ設定」・「固定ページ設定」で記事上ウィジェットの表示・非表示を切り替えできます。</a>',
			'before_widget' => '<div id="%1$s" class="content-widget before-content-widget %2$s">',
			'after_widget'  => '</div>',
			'before_title'  => '<h2 class="widget-title">',
			'after_title'   => '</h2>',
		)
	);
	/**
	 * 記事下CTAエリア
	 */
	register_sidebar(
		array(
			'name'          => '記事下エリア',
			'id'            => 'after-content',
			'description'   => '記事直下に表示されるウィジェット。<a href="' . $customizer_url . '">※カスタマイザーの「デザイン設定」→「投稿ページ設定」・「固定ページ設定」で記事下ウィジェットの表示・非表示を切り替えできます。</a>',
			'before_widget' => '<div id="%1$s" class="content-widget after-content-widget %2$s">',
			'after_widget'  => '</div>',
			'before_title'  => '<h2 class="widget-title">',
			'after_title'   => '</h2>',
		)
	);
}

add_action( 'widgets_init', 'ys_widget_init' );

/**
 * ウィジェットの登録
 */
function ys_widgets_register_widget() {
	register_widget( 'YS_Widget_Text' );
	register_widget( 'YS_Widget_Custom_HTML' );

	register_widget( 'YS_Widget_Author_Box' );
	register_widget( 'YS_Widget_Post_Ranking' );
	register_widget( 'YS_Widget_Recent_Posts' );
	register_widget( 'YS_Widget_Parts' );
}

add_action( 'widgets_init', 'ys_widgets_register_widget' );

/**
 * コンテンツ前のウィジェット
 *
 * @param string $content コンテンツ.
 *
 * @return mixed
 */
function ys_before_content_widget( $content ) {
	if ( ! is_singular() || is_front_page() ) {
		return $content;
	}
	if ( ys_is_active_before_content_widget() ) {
		ob_start();
		dynamic_sidebar( 'before-content' );
		$content = ob_get_clean() . $content;
	}

	return $content;
}

/**
 * コンテンツ後のウィジェット
 *
 * @param string $content コンテンツ.
 *
 * @return mixed
 */
function ys_after_content_widget( $content ) {
	if ( ! is_singular() || is_front_page() ) {
		return $content;
	}
	if ( ys_is_active_after_content_widget() ) {
		ob_start();
		dynamic_sidebar( 'after-content' );
		$content = $content . ob_get_clean();
	}

	return $content;
}


/**
 * コンテンツ前後のウィジェットをセットする。
 */
function ys_set_content_widget() {
	if ( ! is_singular() ) {
		return;
	}
	add_filter(
		'the_content',
		'ys_before_content_widget',
		ys_get_before_content_widget_priority()
	);

	add_filter(
		'the_content',
		'ys_after_content_widget',
		ys_get_after_content_widget_priority()
	);

}

add_action( 'wp', 'ys_set_content_widget' );

/**
 * 記事上ウィジェットの優先順位
 *
 * @return int
 */
function ys_get_before_content_widget_priority() {
	$priority = 10;
	if ( is_single() ) {
		$priority = ys_get_option_by_int( 'ys_post_before_content_widget_priority', 10 );
	}
	if ( is_page() ) {
		$priority = ys_get_option_by_int( 'ys_page_before_content_widget_priority', 10 );
	}
	if ( ys_is_amp() ) {
		$priority = ys_get_option_by_int( 'ys_amp_before_content_widget_priority', 10 );
	}

	return apply_filters( 'ys_get_before_content_widget_priority', $priority );
}

/**
 * 記事下ウィジェットの優先順位
 *
 * @return int
 */
function ys_get_after_content_widget_priority() {
	$priority = 10;
	if ( is_single() ) {
		$priority = ys_get_option_by_int( 'ys_post_after_content_widget_priority', 10 );
	}
	if ( is_page() ) {
		$priority = ys_get_option_by_int( 'ys_page_after_content_widget_priority', 10 );
	}
	if ( ys_is_amp() ) {
		$priority = ys_get_option_by_int( 'ys_amp_after_content_widget_priority', 10 );
	}

	return apply_filters( 'ys_get_after_content_widget_priority', $priority );
}
