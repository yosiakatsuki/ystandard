<?php
/**
 * テンプレートのロード等関連の関数
 *
 * @package ystandard
 * @author yosiakatsuki
 * @license GPL-2.0+
 */

if ( ! function_exists( 'ys_get_front_page_template' ) ) {
	/**
	 * Front-pageでロードするテンプレート
	 */
	function ys_get_front_page_template() {
		$type = get_option( 'show_on_front' );
		if ( 'page' === $type ) {
			$template      = 'page';
			$page_template = get_page_template_slug();

			if ( $page_template ) {
				$template = str_replace( '.php', '', $page_template );
			} elseif ( 'normal' !== ys_get_option( 'ys_front_page_type' ) ) {
				$template = 'template-parts/front-page/' . ys_get_option( 'ys_front_page_type' );
			}
		} else {
			$template = 'home';
		}
		return apply_filters( 'ys_get_front_page_template', $template );
	}
}

if ( ! function_exists( 'ys_get_entry_footer_template' ) ) {
	/**
	 * 記事フッターでロードするテンプレート
	 */
	function ys_get_entry_footer_template() {
		$dir       = 'template-parts/entry/entry-footer-block/';
		$templates = array();
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
/**
 * ヒーローエリアのテンプレート名取得
 *
 * @return string
 */
function ys_get_hero_template() {
	$template = '';
	return apply_filters( 'ys_hero_template', $template );
}
/**
 * アーカイブヘッダーテンプレート名取得
 *
 * @return string
 */
function ys_get_archive_header_template() {
	$template = '';
	if ( is_author() ) {
		$template = 'author';
	}
	return apply_filters( 'ys_get_archive_header_template', $template );
}
/**
 * ページテンプレート名取得
 *
 * @return string
 */
function ys_get_page_template() {
	$template = '';
	if ( ys_is_one_column() ) {
		if ( ys_is_one_column_thumbnail_type() ) {
			$template = 'one-column';
		}
	}
	return apply_filters( 'ys_get_page_template', $template );
}
/**
 * 投稿テンプレート名取得
 *
 * @return string
 */
function ys_get_single_template() {
	$template = '';
	if ( ys_is_one_column() || ys_is_amp() ) {
		if ( ys_is_one_column_thumbnail_type() ) {
			$template = 'one-column';
		}
	}
	return apply_filters( 'ys_get_single_template', $template );
}
