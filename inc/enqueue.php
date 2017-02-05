<?php
//------------------------------------------------------------------------------
//
//	script,cssの読み込み
//
//------------------------------------------------------------------------------


//------------------------------------------------------------------------------
//	スタイルシート・スクリプトの読み込み停止
//------------------------------------------------------------------------------
if (!function_exists( 'ys_enqueue_deregister')) {
	function ys_enqueue_deregister() {

		//WordPressのjqueryを停止
		//wp_deregister_script('jquery');

	}
}
add_action( 'wp_enqueue_scripts', 'ys_enqueue_deregister',9 );




//------------------------------------------------------------------------------
//	スタイルシートの読み込み
//------------------------------------------------------------------------------
if (!function_exists( 'ys_enqueue_styles')) {
	function ys_enqueue_styles() {

		//------------------------------------------
		//	スタイルシート
		//------------------------------------------
		// 高速化対策のため</body>で遅延読み込み
		// wp_enqueue_style( 'ystandard-styles', get_template_directory_uri().'/css/ys-style.min.css', array(), '' );
		// wp_enqueue_style( 'font-awesome', get_template_directory_uri().'/css/font-awesome.min.css', array(), null );
	}
}
add_action( 'wp_enqueue_scripts', 'ys_enqueue_styles' );




//------------------------------------------------------------------------------
//	スクリプトの読み込み
//------------------------------------------------------------------------------
if (!function_exists( 'ys_enqueue_scripts')) {
	function ys_enqueue_scripts() {

		//------------------------------------------
		//	javascript
		//------------------------------------------
		//coreだけ読み込む・フッター側
		//wp_enqueue_script( 'jquery-core', false, array(), null, true );

		//テーマのjs読み込む
		wp_enqueue_script( 'ystandard-scripts', get_template_directory_uri() . '/js/ys.js', array('jquery','jquery-core'), '', true );
		// SNS関連のjs読み込み

	}
}
add_action( 'wp_enqueue_scripts', 'ys_enqueue_scripts' );




//------------------------------------------------------------------------------
//	管理画面-javascriptの読み込み
//------------------------------------------------------------------------------
if (!function_exists( 'ys_enqueue_admin_scripts')) {
	function ys_enqueue_admin_scripts($hook_suffix){
		// メディアアップローダ
		wp_enqueue_media();
		wp_enqueue_script( 'ys-custom_uploader-scripts', get_template_directory_uri() . '/js/admin/custom_uploader.js', array('jquery','jquery-core'), '', true );

	}
}
add_action( 'admin_enqueue_scripts', 'ys_enqueue_admin_scripts' );




//------------------------------------------------------------------------------
//	管理画面-スタイルシートの読み込み
//------------------------------------------------------------------------------
if (!function_exists( 'ys_enqueue_admin_styles')) {
	function ys_enqueue_admin_styles($hook_suffix){
			wp_enqueue_style( 'ys_admin_style', get_template_directory_uri().'/css/ys-editor-style.css' );
	}
}
add_action( 'admin_enqueue_scripts', 'ys_enqueue_admin_styles' );


//------------------------------------------------------------------------------
//	管理画面-テーマカスタマイザーページでのスタイルシートの読み込み
//------------------------------------------------------------------------------
if (!function_exists( 'ys_enqueue_customizer_styles')) {
	function ys_enqueue_customizer_styles($hook_suffix){
		wp_enqueue_style( 'ys_customizer_style', get_template_directory_uri().'/css/ys-customizer-style.css' );
	}
}
add_action('customize_controls_print_styles', 'ys_enqueue_customizer_styles');

?>