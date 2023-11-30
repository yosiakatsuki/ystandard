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
 * Class Post
 *
 * @package ystandard
 */
class Post {

	/**
	 * ページのタイトル部分のみを取得
	 *
	 * @return string
	 */
	public static function get_page_title() {
		$sep       = self::get_document_title_separator();
		$title     = wp_get_document_title();
		$new_title = explode( $sep, $title );

		if ( ! empty( $new_title ) && 1 < count( $new_title ) ) {
			array_pop( $new_title );
			$title = implode( $sep, $new_title );
		}

		return $title;
	}

	/**
	 * ページタイトルに使われる区切り文字を取得.
	 *
	 * @return string
	 */
	public static function get_document_title_separator() {
		$sep = apply_filters( 'document_title_separator', '-' );
		$sep = wptexturize( $sep );
		$sep = convert_chars( $sep );
		$sep = esc_html( $sep );
		$sep = capital_P_dangit( $sep );

		return $sep;
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


	/**
	 * 投稿タイプ取得
	 *
	 * @param array $args    args.
	 * @param bool  $exclude 除外.
	 *
	 * @return array
	 */
	public static function get_post_types( $args = [], $exclude = true ) {
		$args = array_merge(
			[ 'public' => true ],
			$args
		);

		$types = get_post_types( $args );

		if ( is_array( $exclude ) ) {
			$exclude[] = 'attachment';
			foreach ( $exclude as $item ) {
				unset( $types[ $item ] );
			}
		}

		if ( true === $exclude ) {
			unset( $types['attachment'] );
		}

		foreach ( $types as $key => $value ) {
			$post_type = get_post_type_object( $key );
			if ( $post_type ) {
				$types[ $key ] = $post_type->labels->singular_name;
			}
		}

		return $types;
	}
}
