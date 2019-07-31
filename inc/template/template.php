<?php
/**
 * テンプレートのロード等関連の関数
 *
 * @package ystandard
 * @author  yosiakatsuki
 * @license GPL-2.0+
 */

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
		}
	} else {
		$template = 'home';
	}

	return apply_filters( 'ys_get_front_page_template', $template );
}

/**
 * 投稿・固定ページのヘッダーに表示するメタ情報
 */
function ys_get_singular_header_parts() {
	do_action( 'ys_singular_header_parts' );
	if ( apply_filters( 'ys_show_singular_header_parts', true ) ) {
		get_template_part( 'template-parts/singular/header-parts' );
	}
}

/**
 * 投稿・固定ページのフッターに表示するパーツ
 */
function ys_get_singular_footer_parts() {
	do_action( 'ys_singular_footer_parts' );
	if ( apply_filters( 'ys_show_singular_footer_parts', true ) ) {
		get_template_part( 'template-parts/singular/footer-parts' );
	}
}