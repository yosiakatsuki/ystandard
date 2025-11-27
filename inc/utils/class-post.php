<?php
/**
 * 投稿関連
 *
 * @package ystandard
 * @author  yosiakatsuki
 * @license GPL-3.0+
 */

namespace ystandard\utils;

use ystandard\Option;

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

	/**
	 * 投稿抜粋文を作成
	 *
	 * @param string $sep 抜粋最後の文字.
	 * @param integer $length 抜粋長さ.
	 * @param integer $post_id 投稿ID.
	 *
	 * @return string
	 */
	public static function get_custom_excerpt( $sep = ' …', $length = 0, $post_id = 0 ) {
		$length  = 0 === $length ? Option::get_option_by_int( 'ys_option_excerpt_length', 80 ) : $length;
		$post    = get_post( $post_id );
		$content = self::get_custom_excerpt_raw( $post_id );
		/**
		 * 長さ調節
		 */
		if ( empty( $post->post_excerpt ) && mb_strlen( $content ) > $length ) {
			$length  = $length - mb_strlen( $sep );
			$length  = 0 > $length ? 1 : $length;
			$content = mb_substr( $content, 0, $length ) . $sep;
		}

		return apply_filters( 'ys_get_the_custom_excerpt', $content, $post_id );
	}

	/**
	 * 切り取らない投稿抜粋文を作成
	 *
	 * @param integer $post_id 投稿ID.
	 *
	 * @return string
	 */
	public static function get_custom_excerpt_raw( $post_id = 0 ) {
		$post_id = 0 === $post_id ? get_the_ID() : $post_id;
		$post    = get_post( $post_id );
		if ( post_password_required( $post ) ) {
			return __( 'There is no excerpt because this is a protected post.' );
		}
		$content = $post->post_excerpt;
		if ( '' === $content ) {
			// Excerptが無ければ本文から作る.
			$content = $post->post_content;
			// Moreタグ以降を削除.
			$content = preg_replace( '/<!--more-->.+/is', '', $content );
			// ブロックエディタの埋め込みブロックコメントとコンテンツを削除.
			$content = preg_replace( '/<!-- wp:embed.+?<!-- \/wp:embed -->/is', '', $content );
			// その他の埋め込み系ブロックも削除.
			$content = preg_replace( '/<!-- wp:core-embed\/.+?<!-- \/wp:core-embed\/.+? -->/is', '', $content );
			$content = Text::get_plain_text( $content );
		}

		return $content;
	}
}
