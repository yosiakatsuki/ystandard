<?php
//------------------------------------------------------------------------------
//
//	ウィジェト
//
//------------------------------------------------------------------------------


//------------------------------------------------------------------------------
//	ウィジット有効化
//------------------------------------------------------------------------------
if (!function_exists( 'ys_widget_init')) {
	function ys_widget_init() {

		//右サイドバー
		register_sidebar( array(
			'name'					 => '右サイドバー',
			'id'						 => 'sidebar-right',
			'description'	   => '右サイドバー',
			'before_widget'  => '<section id="%1$s" class="widget %2$s">',
			'after_widget'	 => '</section>',
			'before_title'	 => '<h2 class="widget-title">',
			'after_title'	   => '</h2>',
		) );
		register_sidebar( array(
			'name'					 => '右サイドバー（追従）',
			'id'						 => 'sidebar-fixed',
			'description'	   => '右サイドバー',
			'before_widget'  => '<section id="%1$s" class="widget %2$s">',
			'after_widget'	 => '</section>',
			'before_title'	 => '<h2 class="widget-title">',
			'after_title'	   => '</h2>',
		) );
		//フッター左
		register_sidebar( array(
			'name'					 => 'フッター左',
			'id'						 => 'footer-left',
			'description'	   => 'フッターエリア左側',
			'before_widget'  => '<section id="%1$s" class="widget %2$s">',
			'after_widget'	 => '</section>',
			'before_title'	 => '<h2 class="widget-title">',
			'after_title'	   => '</h2>',
		) );
		//フッター中央
		register_sidebar( array(
			'name'					 => 'フッター中央',
			'id'						 => 'footer-center',
			'description'	   => 'フッターエリア中央',
			'before_widget'  => '<section id="%1$s" class="widget %2$s">',
			'after_widget'	 => '</section>',
			'before_title'	 => '<h2 class="widget-title">',
			'after_title'	   => '</h2>',
		) );
		//フッター右
		register_sidebar( array(
			'name'					 => 'フッター右',
			'id'						 => 'footer-right',
			'description'	   => 'フッターエリア右側',
			'before_widget'  => '<section id="%1$s" class="widget %2$s">',
			'after_widget'	 => '</section>',
			'before_title'	 => '<h2 class="widget-title">',
			'after_title'	   => '</h2>',
		) );
	}
}
add_action( 'widgets_init', 'ys_widget_init' );
?>