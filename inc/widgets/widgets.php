<?php
/**
 * @package ystandard
 * @author yosiakatsuki
 * @license GPL-2.0+
 */
/**
 *
 * ウィジェト
 *
 */
/**
 * ウィジット有効化
 */
if ( ! function_exists( 'ys_widget_init' ) ) {
	function ys_widget_init() {
		/**
		 * サイドバー
		 */
		register_sidebar( array(
			'name'					 => 'サイドバー',
			'id'						 => 'sidebar-widget',
			'description'	   => 'サイドバー',
			'before_widget'  => '<div id="%1$s" class="widget %2$s">',
			'after_widget'	 => '</div>',
			'before_title'	 => '<h2 class="widget-title">',
			'after_title'	   => '</h2>',
		) );
		register_sidebar( array(
			'name'					 => 'サイドバー（追従）',
			'id'						 => 'sidebar-fixed',
			'description'	   => 'サイドバー',
			'before_widget'  => '<div id="%1$s" class="widget %2$s">',
			'after_widget'	 => '</div>',
			'before_title'	 => '<h2 class="widget-title">',
			'after_title'	   => '</h2>',
		) );
		/**
		 * 記事下CTAエリア
		 */
		register_sidebar( array(
			'name'					 => '記事下エリア',
			'id'						 => 'entry-footer',
			'description'	   => '記事直下に表示されるウィジェット',
			'before_widget'  => '<div id="%1$s" class="widget %2$s">',
			'after_widget'	 => '</div>',
			'before_title'	 => '<h2 class="widget-title">',
			'after_title'	   => '</h2>',
		) );
		/**
		 * フッター
		 */
		register_sidebar( array(
			'name'					 => 'フッター左',
			'id'						 => 'footer-left',
			'description'	   => 'フッターエリア左側',
			'before_widget'  => '<div id="%1$s" class="widget %2$s">',
			'after_widget'	 => '</div>',
			'before_title'	 => '<h2 class="widget-title">',
			'after_title'	   => '</h2>',
		) );
		register_sidebar( array(
			'name'					 => 'フッター中央',
			'id'						 => 'footer-center',
			'description'	   => 'フッターエリア中央',
			'before_widget'  => '<div id="%1$s" class="widget %2$s">',
			'after_widget'	 => '</div>',
			'before_title'	 => '<h2 class="widget-title">',
			'after_title'	   => '</h2>',
		) );
		register_sidebar( array(
			'name'					 => 'フッター右',
			'id'						 => 'footer-right',
			'description'	   => 'フッターエリア右側',
			'before_widget'  => '<div id="%1$s" class="widget %2$s">',
			'after_widget'	 => '</div>',
			'before_title'	 => '<h2 class="widget-title">',
			'after_title'	   => '</h2>',
		) );
	}
}
add_action( 'widgets_init', 'ys_widget_init' );

/**
 * ウィジェットの登録
 */
function ys_widgets_register_widget() {
		register_widget( 'YS_Ranking_Widget' );
		register_widget( 'YS_AD_Text_Widget' );
}
add_action( 'widgets_init', 'ys_widgets_register_widget' );