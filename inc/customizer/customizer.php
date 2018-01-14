<?php
/**
 * テーマカスタマイザー
 */
require_once get_template_directory() . '/inc/customizer/customizer-control.php';
require_once get_template_directory() . '/inc/customizer/customizer-sanitize.php';
require_once get_template_directory() . '/inc/customizer/customizer-wp.php';
require_once get_template_directory() . '/inc/customizer/customizer-color.php';
require_once get_template_directory() . '/inc/customizer/customizer-site.php';
require_once get_template_directory() . '/inc/customizer/customizer-sns.php';
require_once get_template_directory() . '/inc/customizer/customizer-seo.php';
require_once get_template_directory() . '/inc/customizer/customizer-amp.php';

/**
 * カスタマイザー追加
 */
function ys_theme_customizer( $wp_customize ) {
	/**
	 * WordPress標準のカスタマイザー項目の変更・追加
	 */
	ys_customizer_wp( $wp_customize );
	/**
	 * 色設定
	 */
	ys_customizer_color( $wp_customize );
	/**
	 * サイト共通設定
	 */
	ys_customizer_site( $wp_customize );
	/**
	 * SNS設定
	 */
	ys_customizer_sns( $wp_customize );
	/**
	 * SEO設定
	 */
	ys_customizer_seo( $wp_customize );
	/**
	 * AMP設定
	 */
	ys_customizer_amp( $wp_customize );

}
add_action('customize_register', 'ys_theme_customizer');