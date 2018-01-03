<?php
/**
 *	テーマバージョン取得
 */
if ( ! function_exists( 'ys_util_get_theme_version') ) {
	function ys_util_get_theme_version( $template = false ) {
		/**
		 * 子テーマ情報
		 */
		$theme = wp_get_theme();
		if( $template && get_template() != get_stylesheet() ){
			/**
			 * 親テーマ情報
			 */
			$theme = wp_get_theme( get_template() );
		}
		return $theme->Version;
	}
}

/**
 * クエリストリング追加
 */
if ( ! function_exists( 'ys_util_add_query_string') ) {
	function ys_util_add_query_string( $url, $query_str ) {
		$sep = false === strrpos( '?', $url ) ? '?' : '&';
		return $url . $sep . $query_str;
	}
}