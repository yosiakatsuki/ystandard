<?php
/**
 * コンテンツ周りの処理
 *
 * @package ystandard
 * @author yosiakatsuki
 * @license GPL-2.0+
 */

if ( ! function_exists( 'ys_document_title_separator' ) ) {
	/**
	 * ブログ名区切り文字設定
	 *
	 * @param  string $sep 区切り文字.
	 * @return string
	 */
	function ys_document_title_separator( $sep ) {
		$sep_option = ys_get_option( 'ys_title_separate' );
		if ( '' != $sep_option ) {
			$sep = $sep_option;
		}
		return $sep;
	}
}
add_filter( 'document_title_separator', 'ys_document_title_separator' );

if ( ! function_exists( 'ys_more_tag_replace' ) ) {
	/**
	 * Moreタグの置換
	 *
	 * @param string $the_content 投稿本文.
	 */
	function ys_more_tag_replace( $the_content ) {
		$replace = ys_get_ad_more_tag();
		$replace = apply_filters( 'ys_more_tag_replace', $replace );
		if ( '' !== $replace ) {
			$the_content = preg_replace( '/<p><span id="more-[0-9]+"><\/span><\/p>/', $replace, $the_content );
			/**
			 * 「remove_filter( 'the_content', 'wpautop' )」対策
			 */
			$the_content = preg_replace( '/<span id="more-[0-9]+"><\/span>/', $replace, $the_content );
		}
		return $the_content;
	}
}
add_filter( 'the_content', 'ys_more_tag_replace' );

if ( ! function_exists( 'ys_add_iframe_responsive_container' ) ) {
	/**
	 * Iframeのレスポンシブ化
	 *
	 * @param string $the_content 投稿本文.
	 */
	function ys_add_iframe_responsive_container( $the_content ) {
		if ( is_singular() && ! ys_is_amp() ) {
			/**
			 * マッチさせたいiframeのURLをリスト化
			 */
			$pattern_list = array(
				'youtube\.com',
				'vine\.co',
				'https:\/\/www\.google\.com\/maps\/embed',
			);
			/**
			 * 置換する
			 */
			foreach ( $pattern_list as $value ) {
				$pattern     = '/<iframe[^>]+?' . $value . '[^<]+?<\/iframe>/is';
				$the_content = preg_replace( $pattern, '<div class="embed__container"><div class="embed__item">${0}</div></div>', $the_content );
			}
		}
		return $the_content;
	}
}
add_filter( 'the_content', 'ys_add_iframe_responsive_container' );
add_filter( 'widget_text', 'ys_add_iframe_responsive_container' );

if ( ! function_exists( 'ys_excerpt_length' ) ) {
	/**
	 * 投稿抜粋文字数
	 *
	 * @param int $length 抜粋文字数.
	 */
	function ys_excerpt_length( $length = null ) {
		if ( ! is_null( $length ) ) {
			return $length;
		}
		return ys_get_option( 'ys_option_excerpt_length' );
	}
}
add_filter( 'excerpt_length', 'ys_excerpt_length', 999 );

if ( ! function_exists( 'ys_excerpt_more' ) ) {
	/**
	 * 投稿抜粋の最後につける文字
	 *
	 * @param string $more 抜粋につける文字.
	 */
	function ys_excerpt_more( $more ) {
		$more_str = '…';
		if ( '' !== $more ) {
			$more_str = $more;
		}
		if ( 0 == ys_excerpt_length() ) {
			$more_str = '';
		}
		return $more_str;
	}
}
add_filter( 'excerpt_more', 'ys_excerpt_more' );

if ( ! function_exists( 'ys_get_the_custom_excerpt' ) ) {
	/**
	 * 投稿抜粋文を作成
	 *
	 * @param  string  $sep     抜粋最後の文字.
	 * @param  integer $length  抜粋長さ.
	 * @param  integer $post_id 投稿ID.
	 * @return string
	 */
	function ys_get_the_custom_excerpt( $sep = ' …', $length = 0, $post_id = 0 ) {
		if ( 0 == $post_id ) {
			$post_id = get_the_ID();
		}
		if ( 0 == $length ) {
			$length = ys_excerpt_length();
		}
		$post = get_post( $post_id );
		if ( post_password_required( $post ) ) {
			return __( 'There is no excerpt because this is a protected post.' );
		}
		$content = $post->post_excerpt;
		if ( '' === $content ) {
			/**
			 * Excerptが無ければ本文から作る
			 */
			$content = $post->post_content;
			/**
			* Moreタグ以降を削除
			*/
			$content = preg_replace( '/<!--more-->.+/is', '', $content );
			$content = ys_get_plain_text( $content );
		}
		/**
		* 長さ調節
		*/
		if ( mb_strlen( $content ) > $length ) {
			$content = mb_substr( $content, 0, $length - mb_strlen( $sep ) ) . $sep;
		}
		return apply_filters( 'ys_get_the_custom_excerpt', $content, $post_id );
	}
}
if ( ! function_exists( 'ys_the_custom_excerpt' ) ) {
	/**
	 * 投稿抜粋文を出力
	 *
	 * @param  string  $sep     抜粋最後の文字.
	 * @param  integer $length  抜粋長さ.
	 * @param  integer $post_id 投稿ID.
	 */
	function ys_the_custom_excerpt( $sep = ' …', $length = 0, $post_id = 0 ) {
		echo ys_get_the_custom_excerpt( $length, $sep, $post_id );
	}
}

if ( ! function_exists( 'ys_get_entry_read_more_text' ) ) {
	/**
	 * 続きを読む テキスト
	 */
	function ys_get_entry_read_more_text() {
		$read_more = 'READ MORE';
		return apply_filters( 'ys_entry_read_more_text', $read_more );
	}
}
/**
 * 続きを読む テキスト
 */
function ys_the_entry_read_more_text() {
	echo ys_get_entry_read_more_text();
}