<?php
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
		return sprintf( $format, $logo );
	}
}
/**
 * カスタムロゴオブジェクト取得
 */
if (!function_exists( 'ys_get_custom_logo_image_object')) {
	function ys_get_custom_logo_image_object( $blog_id = 0 ) {
		if ( is_multisite() && (int) $blog_id !== get_current_blog_id() ) {
				switch_to_blog( $blog_id );
		}
		$custom_logo_id = get_theme_mod( 'custom_logo' );
		$image = false;
		// We have a logo. Logo is go.
		if ( $custom_logo_id ) {
				$image = wp_get_attachment_image_src( $custom_logo_id, 'full' );
		}
		if ( is_multisite() && ms_is_switched() ) {
				restore_current_blog();
		}
		return $image;
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
		$format = '<p class="site-description header__dscr color__site-dscr">%s</p>';
		$format = apply_filters( 'ys_the_blog_description_format', $format );
		echo sprintf( $format, $dscr );
	}
}