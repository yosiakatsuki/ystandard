<?php
/**
 * ヘッダー関連
 *
 * @package ystandard
 * @author  yosiakatsuki
 * @license GPL-2.0+
 */

/**
 * テーマ用get_header
 *
 * @param string $name The name of the specialised header.
 */
function ys_get_header( $name = null ) {
	$name = apply_filters( 'ys_get_header_name', $name );
	/**
	 * AMPの時とテンプレート分ける
	 */
	if ( ys_is_ystd_amp() ) {
		get_template_part( '/template-parts/amp/amp-header', $name );
	} else {
		get_header( $name );
	}
}

/**
 * ヘッダーロゴ取得
 */
function ys_get_header_logo() {
	if ( has_custom_logo() && ! ys_get_option( 'ys_logo_hidden' ) ) {
		/**
		 * ロゴあり・表示する
		 */
		$logo = ys_get_custom_logo();
	} else {
		$logo = get_bloginfo( 'name' );
	}
	$logo   = apply_filters( 'ys_get_header_logo', $logo );
	$logo   = ys_amp_get_amp_image_tag( $logo );
	$format = '<a href="' . esc_url( home_url( '/' ) ) . '" rel="home">%s</a>';
	$format = apply_filters( 'ys_get_header_logo_format', $format );
	$logo   = sprintf( $format, $logo );

	return $logo;
}

/**
 * サイトキャッチフレーズを取得
 */
function ys_the_blog_description() {
	if ( ys_get_option( 'ys_wp_hidden_blogdescription' ) ) {
		return;
	}
	$dscr   = apply_filters( 'ys_the_blog_description', get_bloginfo( 'description', 'display' ) );
	$format = '<p class="site-description header__dscr text-sub">%s</p>';
	$format = apply_filters( 'ys_the_blog_description_format', $format );
	echo sprintf( $format, $dscr );
}

/**
 * ヘッダー row class取得
 *
 * @param array $class 追加クラス.
 *
 * @return array
 */
function ys_get_header_row_class( $class = array() ) {
	$classes = array();
	if ( is_array( $class ) && ! empty( $class ) ) {
		$classes = array_merge( $classes, $class );
	}
	$type      = ys_get_option( 'ys_design_header_type' );
	$classes[] = 'header__row';
	$classes[] = '-' . $type;

	$classes = apply_filters( 'ys_get_header_row_class', $classes, $type );

	return $classes;
}

/**
 * ヘッダー row class出力
 *
 * @param array $class 追加クラス.
 */
function ys_the_header_row_class( $class = array() ) {
	echo implode( ' ', ys_get_header_row_class( $class ) );
}

/**
 * ヘッダー col class取得
 *
 * @param string $pos   logo or nav.
 * @param array  $class 追加クラス.
 *
 * @return array
 */
function ys_get_header_col_class( $pos, $class = array() ) {
	$classes = array();
	if ( is_array( $class ) && ! empty( $class ) ) {
		$classes = array_merge( $classes, $class );
	}
	$type      = ys_get_option( 'ys_design_header_type' );
	$classes[] = '-' . $type;
	$classes   = apply_filters( 'ys_get_header_col_class', $classes, $type, $pos );

	return $classes;
}

/**
 * ヘッダー col class出力
 *
 * @param string $pos   logo or nav.
 * @param array  $class 追加クラス.
 */
function ys_the_header_col_class( $pos, $class = array() ) {
	echo implode( ' ', ys_get_header_col_class( $pos, $class ) );
}