<?php
/**
 * @package ystandard
 * @author yosiakatsuki
 * @license GPL-2.0+
 */
/**
 * ヘッダーロゴ取得
 */
if( ! function_exists( 'ys_get_header_logo' ) ) {
	function ys_get_header_logo() {
		$logo = get_bloginfo('name');
		if( has_custom_logo() && ! ys_get_option( 'ys_logo_hidden' ) ) {
			/**
			 * ロゴあり・表示する
			 */
			$logo = get_custom_logo();
		}
		$logo = apply_filters( 'ys_get_header_logo', $logo );
		$format = '<a href="' . esc_url( home_url( '/' ) )  . '" rel="home">%s</a>';
		$format = apply_filters( 'ys_get_header_logo_format', $format );
		return sprintf( $format, ys_amp_convert_image( $logo ) );
	}
}

/**
 * サイトキャッチフレーズを取得
 */
if( ! function_exists( 'ys_the_blog_description' ) ) {
	function ys_the_blog_description() {
		if( ys_get_option( 'ys_wp_hidden_blogdescription' ) ) {
			return;
		}
		$dscr = apply_filters( 'ys_the_blog_description', get_bloginfo( 'description', 'display' ) );
		$format = '<p class="site-description header__dscr color__font-sub">%s</p>';
		$format = apply_filters( 'ys_the_blog_description_format', $format );
		echo sprintf( $format, $dscr );
	}
}

/**
 * ヘッダータイプ class取得
 */
if( ! function_exists( 'ys_get_header_type_class' ) ) {
	function ys_get_header_type_class() {
		$type = ys_get_option( 'ys_design_header_type' );
		$class = apply_filters( 'ys_get_header_type_class', 'header--' . $type, $type );
		return $class;
	}
}

/**
 * ヘッダータイプ class出力
 */
if( ! function_exists( 'ys_the_header_type_class' ) ) {
	function ys_the_header_type_class() {
		echo ys_get_header_type_class();
	}
}