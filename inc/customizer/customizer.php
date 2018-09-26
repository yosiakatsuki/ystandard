<?php
/**
 * テーマカスタマイザー
 *
 * @package ystandard
 * @author yosiakatsuki
 * @license GPL-2.0+
 */

/**
 * テーマカスタマイザー
 */
require_once get_template_directory() . '/inc/class/customizer/class-ys-customize-image-label-radio-control.php';
require_once get_template_directory() . '/inc/class/customizer/class-ys-customizer.php';
require_once get_template_directory() . '/inc/customizer/customizer-wp.php';
require_once get_template_directory() . '/inc/customizer/customizer-color.php';
require_once get_template_directory() . '/inc/customizer/customizer-site.php';
require_once get_template_directory() . '/inc/customizer/customizer-design.php';
require_once get_template_directory() . '/inc/customizer/customizer-post.php';
require_once get_template_directory() . '/inc/customizer/customizer-page.php';
require_once get_template_directory() . '/inc/customizer/customizer-front-page.php';
require_once get_template_directory() . '/inc/customizer/customizer-archive.php';
require_once get_template_directory() . '/inc/customizer/customizer-sns.php';
require_once get_template_directory() . '/inc/customizer/customizer-seo.php';
require_once get_template_directory() . '/inc/customizer/customizer-performance-tuning.php';
require_once get_template_directory() . '/inc/customizer/customizer-advertisement.php';
require_once get_template_directory() . '/inc/customizer/customizer-amp.php';
require_once get_template_directory() . '/inc/customizer/customizer-admin.php';
require_once get_template_directory() . '/inc/customizer/customizer-extension.php';

/**
 * カスタマイザー追加
 *
 * @param  WP_Customize_Manager $wp_customize wp_customize.
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
	 * デザイン設定
	 */
	ys_customizer_design( $wp_customize );
	/**
	 * デザイン設定 > 投稿ページ設定
	 */
	ys_customizer_post( $wp_customize );
	/**
	 * デザイン設定 > 固定ページ設定
	 */
	ys_customizer_page( $wp_customize );
	/**
	 * デザイン設定 > アーカイブページ設定
	 */
	ys_customizer_archive( $wp_customize );
	/**
	 * フロントページ設定
	 */
	ys_customizer_front_page( $wp_customize );
	/**
	 * SNS設定
	 */
	ys_customizer_sns( $wp_customize );
	/**
	 * SEO設定
	 */
	ys_customizer_seo( $wp_customize );
	/**
	 * サイト高速化設定
	 */
	ys_customizer_performance_tuning( $wp_customize );
	/**
	 * 広告設定
	 */
	ys_customizer_advertisement( $wp_customize );
	/**
	 * AMP設定
	 */
	ys_customizer_amp( $wp_customize );	
	/**
	 * サイト運営支援
	 */
	ys_customizer_admin( $wp_customize );
	/**
	 * 拡張機能
	 */
	ys_customizer_extension( $wp_customize );
}
add_action( 'customize_register', 'ys_theme_customizer' );
/**
 * カスタマイザー用画像アセットURL取得
 */
function ys_get_template_customizer_assets_img_dir_uri() {
	return get_template_directory_uri() . '/assets/images/customizer';
}
