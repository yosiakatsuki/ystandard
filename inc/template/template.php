<?php
/**
 *
 * テンプレート関連の関数
 *
 */

/**
 * front-pageでロードするテンプレート
 */
if( ! function_exists( 'ys_get_front_page_template' ) ) {
	function ys_get_front_page_template() {
		$type = get_option( 'show_on_front' );
		if( 'page' == $type ){
			$template = 'page';
			$page_template = get_page_template_slug();

			if( $page_template ) {
				$template = str_replace( '.php', '', $page_template );
			}
		} else {
			$template = 'home';
		}
		return apply_filters( 'ys_get_front_page_template', $template );
	}
}

/**
 * 記事フッターでロードするテンプレート
 */
if( ! function_exists( 'ys_get_entry_footer_template' ) ) {
	function ys_get_entry_footer_template() {
		$dir = 'template-parts/entry/entry-footer-block/';
		$templates = array();
		/**
		 * タクソノミー
		 */
		if( is_single() ) {
			$templates = wp_parse_args( array( 'taxonomy' => $dir . 'entry-footer-taxonomy' ) ,$templates );
		}
		/**
		 * フッターウィジェット
		 */
		$templates = wp_parse_args( array( 'wdget' => $dir . 'entry-footer-wdget' ) ,$templates );
		/**
		 * 広告
		 */
		$templates = wp_parse_args( array( 'ad' => $dir . 'entry-footer-ad' ) ,$templates );
		/**
		 * シェアボタン
		 */
		$templates = wp_parse_args( array( 'share' => $dir . 'entry-footer-share' ) ,$templates );
		/**
		 * 購読
		 */
		$templates = wp_parse_args( array( 'subscribe' => $dir . 'entry-footer-subscribe' ) ,$templates );
		/**
		 * 投稿者表示
		 */
		$templates = wp_parse_args( array( 'author' => $dir . 'entry-footer-author' ) ,$templates );
		return apply_filters( 'ys_get_entry_footer_template', $templates );
	}
}