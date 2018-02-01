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
		$dir = 'template-parts/singular/entry-footer-block/';
		$templates = array();
		/**
		 *  フッターウィジェット
		 */
		$templates[] = $dir . 'entry-footer-wdget';
		/**
		 * 広告
		 */
		$templates[] = $dir . 'entry-footer-ad';
		/**
		 * シェアボタン
		 */
		$templates[] = $dir . 'entry-footer-share';
		return apply_filters( 'ys_get_entry_footer_template', $templates );
	}
}