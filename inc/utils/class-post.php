<?php
/**
 * 投稿関連
 *
 * @package ystandard
 * @author  yosiakatsuki
 * @license GPL-3.0+
 */

namespace ystandard\utils;

defined( 'ABSPATH' ) || die();

/**
 * Class Post Utils
 */
class Post {
	/**
	 * ページのタイトル部分のみを取得
	 *
	 * @return string
	 */
	public static function get_page_title() {
		$sep       = apply_filters( 'document_title_separator', '-' );
		$sep       = wptexturize( $sep );
		$sep       = convert_chars( $sep );
		$sep       = esc_html( $sep );
		$sep       = capital_P_dangit( $sep );
		$title     = wp_get_document_title();
		$new_title = explode( $sep, $title );
		if ( ! empty( $new_title ) && 1 < count( $new_title ) ) {
			array_pop( $new_title );
			$title = implode( $sep, $new_title );
		}

		return $title;
	}

	/**
	 * 投稿本文を取得
	 *
	 * @param bool $do_filter フィルターをかけるか.
	 *
	 * @return string
	 */
	public static function get_post_content( $do_filter = true ) {
		/**
		 * Post.
		 *
		 * @global \WP_Post
		 */
		global $post;
		$content = $post->post_content;
		if ( $do_filter ) {
			$content = apply_filters( 'the_content', $content );
			$content = str_replace( ']]>', ']]&gt;', $content );
		}

		return $content;
	}
}
