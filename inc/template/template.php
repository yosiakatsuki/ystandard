<?php
/**
 * @package ystandard
 * @author yosiakatsuki
 * @license GPL-2.0+
 */
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
		 * フッターウィジェット
		 */
		$templates['wdget'] = $dir . 'entry-footer-wdget';
		/**
		 * 広告
		 */
		$templates['ad'] = $dir . 'entry-footer-ad';
		/**
		 * シェアボタン
		 */
		$templates['share'] = $dir . 'entry-footer-share';
		/**
		 * タクソノミー
		 */
		if ( is_single() ) {
			$templates['taxonomy'] = $dir . 'entry-footer-taxonomy';
		}
		/**
		 * 購読
		 */
		$templates['subscribe'] = $dir . 'entry-footer-subscribe';
		/**
		 * 投稿者表示
		 */
		$templates['author'] = $dir . 'entry-footer-author';
		return apply_filters( 'ys_get_entry_footer_template', $templates );
	}
}