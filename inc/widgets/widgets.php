<?php
/**
 * ウィジェト
 *
 * @package ystandard
 * @author  yosiakatsuki
 * @license GPL-2.0+
 */

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
			'description'   => '記事直上に表示されるウィジェット<a href="' . $customizer_url . '">※カスタマイザーの「デザイン設定」→「投稿ページ設定」・「固定ページ設定」で記事上ウィジェットを有効にして下さい</a>',
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
			'description'   => '記事直下に表示されるウィジェット<a href="' . $customizer_url . '">※カスタマイザーの「デザイン設定」→「投稿ページ設定」・「固定ページ設定」で記事下ウィジェットを有効にして下さい</a>',
			'before_widget' => '<div id="%1$s" class="content-widget after-content-widget %2$s">',
			'after_widget'  => '</div>',
			'before_title'  => '<h2 class="widget-title">',
			'after_title'   => '</h2>',
		)
	);

	/**
	 * フッター
	 */
	register_sidebar(
		array(
			'name'          => 'フッター左',
			'id'            => 'footer-left',
			'description'   => 'フッターエリア左側',
			'before_widget' => '<div id="%1$s" class="widget %2$s">',
			'after_widget'  => '</div>',
			'before_title'  => '<h2 class="widget-title">',
			'after_title'   => '</h2>',
		)
	);
	register_sidebar(
		array(
			'name'          => 'フッター中央',
			'id'            => 'footer-center',
			'description'   => 'フッターエリア中央',
			'before_widget' => '<div id="%1$s" class="widget %2$s">',
			'after_widget'  => '</div>',
			'before_title'  => '<h2 class="widget-title">',
			'after_title'   => '</h2>',
		)
	);
	register_sidebar(
		array(
			'name'          => 'フッター右',
			'id'            => 'footer-right',
			'description'   => 'フッターエリア右側',
			'before_widget' => '<div id="%1$s" class="widget %2$s">',
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
	register_widget( 'YS_Widget_Advertisement' );
	register_widget( 'YS_Widget_Author_Box' );
	register_widget( 'YS_Widget_Post_Ranking' );
	register_widget( 'YS_Widget_Recant_Posts' );
	/**
	 * 以下はアドオン購入で使えるようになる予定です。
	 * 子テーマカスタマイズで有効化していただいても大丈夫ですが、
	 * アドオン販売するまではカスタマイズ記事などにはしてほしくないです。
	 * ご協力お願いします。
	 * ・YS_Widget_Share_Button
	 * ・YS_Widget_Post_Taxonomy
	 */
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
		$priority = ys_get_option( 'ys_post_before_content_widget_priority' );
	}
	if ( is_page() ) {
		$priority = ys_get_option( 'ys_page_before_content_widget_priority' );
	}
	if ( ys_is_amp() ) {
		$priority = ys_get_option( 'ys_amp_before_content_widget_priority' );
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
		$priority = ys_get_option( 'ys_post_after_content_widget_priority' );
	}
	if ( is_page() ) {
		$priority = ys_get_option( 'ys_page_after_content_widget_priority' );
	}
	if ( ys_is_amp() ) {
		$priority = ys_get_option( 'ys_amp_after_content_widget_priority' );
	}

	return apply_filters( 'ys_get_after_content_widget_priority', $priority );
}