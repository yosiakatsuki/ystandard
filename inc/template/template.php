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