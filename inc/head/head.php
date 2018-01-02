<?php
/**
 * <head>タグ取得
 */
if( ! function_exists( 'ys_get_the_head_tag' ) ) {
	function ys_get_the_head_tag() {
		$html = '<head prefix="og: http://ogp.me/ns# fb: http://ogp.me/ns/fb# blog: http://ogp.me/ns/blog#">';
		if( is_singular() ){
			$html = '<head prefix="og: http://ogp.me/ns# fb: http://ogp.me/ns/fb# article: http://ogp.me/ns/article#">';
		}
		return apply_filters( 'ys_get_the_head_tag', $html );
	}
}

/**
 * <head>タグ出力
 */
if( ! function_exists( 'ys_the_head_tag' ) ) {
	function ys_the_head_tag() {
		echo ys_get_the_head_tag();
	}
}

/**
 * インラインCSSセット
 */
if( ! function_exists( 'ys_set_inline_style' ) ) {
	function ys_set_inline_style( $style, $minify = true ) {
		global $ys_styles;
		$style = $ys_styles->set_inline_style( $style, $minify );
	}
}

/**
 * インラインCSS取得
 */
if( ! function_exists( 'ys_get_the_inline_style' ) ) {
	function ys_get_the_inline_style( $is_amp ) {
		global $ys_styles;
		$style = $ys_styles->get_inline_style( $is_amp );
		return apply_filters( 'ys_get_the_inline_style', $style );
	}
}

/**
 * インラインCSS出力
 */
if( ! function_exists( 'ys_the_inline_style' ) ) {
	function ys_the_inline_style() {
		$style = ys_get_the_inline_style( ys_is_amp() );
		if( ys_is_amp() ) {
			$style = sprintf( '<style amp-custom>%s</style>', $style );
		} else {
			$style = sprintf( '<style type="text/css">%s</style>', $style );
		}
		echo $style;
	}
}