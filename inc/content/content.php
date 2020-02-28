<?php
/**
 * コンテンツ周りの処理
 *
 * @package ystandard
 * @author  yosiakatsuki
 * @license GPL-2.0+
 */

/**
 * ブログ名区切り文字設定
 *
 * @param string $sep 区切り文字.
 *
 * @return string
 */
function ys_document_title_separator( $sep ) {
	$sep_option = ys_get_option( 'ys_title_separate', '' );
	if ( '' !== $sep_option ) {
		$sep = $sep_option;
	}

	return $sep;
}

add_filter( 'document_title_separator', 'ys_document_title_separator' );

/**
 * Moreタグの置換
 *
 * @param string $the_content 投稿本文.
 *
 * @return string
 */
function ys_more_tag_replace( $the_content ) {
	$replace = '';
	if ( ys_is_active_advertisement() ) {
		$replace = ys_the_ad_more_tag();
	}
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

add_filter( 'the_content', 'ys_more_tag_replace' );

/**
 * 埋め込みレスポンシブ化：iframe
 *
 * @param string $the_content 投稿本文.
 * @param string $type        タイプ.
 *
 * @return string
 */
function ys_add_iframe_responsive_container( $the_content, $type = '' ) {
	if ( ys_is_amp() ) {
		return $the_content;
	}
	/**
	 * マッチさせたいiframeのURLをリスト化
	 */
	$pattern_list = array(
		array(
			'url'    => 'https:\/\/www\.google\.com\/maps\/embed',
			'aspect' => '4-3',
		),

	);
	$pattern_list = apply_filters( 'ys_iframe_responsive_pattern_list', $pattern_list, $type );
	/**
	 * 置換する
	 */
	foreach ( $pattern_list as $value ) {
		if ( isset( $value['url'] ) && isset( $value['aspect'] ) ) {
			$replace     = '<div class="ys-embed-responsive wp-embed-aspect-' . $value['aspect'] . ' wp-has-aspect-ratio"><div class="wp-block-embed__wrapper">${0}</div></div>';
			$pattern     = '/<iframe[^>]+?' . $value['url'] . '[^<]+?<\/iframe>/is';
			$the_content = preg_replace( $pattern, $replace, $the_content );
		}
	}

	return $the_content;
}

/**
 * 投稿本文内のiframeレスポンシブ対応
 *
 * @param string $the_content コンテンツ.
 *
 * @return string
 */
function ys_iframe_responsive_content( $the_content ) {
	$the_content = ys_add_iframe_responsive_container( $the_content, 'the_content' );

	return $the_content;
}

add_filter( 'the_content', 'ys_iframe_responsive_content' );

/**
 * ウィジェット内のiframeレスポンシブ対応
 *
 * @param string $the_content コンテンツ.
 *
 * @return string
 */
function ys_iframe_responsive_widget( $the_content ) {
	$the_content = ys_add_iframe_responsive_container( $the_content, 'widget_text' );

	return $the_content;
}

add_filter( 'widget_text', 'ys_iframe_responsive_widget' );

/**
 * 投稿抜粋文字数
 *
 * @param int $length 抜粋文字数.
 *
 * @return string
 */
function ys_excerpt_length( $length = null ) {
	$option_length = ys_get_option_by_int( 'ys_option_excerpt_length', 110 );
	/**
	 * 直接呼び出しでもフックでも設定値を返す
	 */
	if ( null !== $length ) {
		return $option_length;
	}

	return $option_length;
}

add_filter( 'excerpt_length', 'ys_excerpt_length', 999 );

/**
 * 投稿抜粋の最後につける文字
 *
 * @param string $more 抜粋につける文字.
 *
 * @return string
 */
function ys_excerpt_more( $more ) {
	$more_str = ' …';
	if ( 0 === ys_excerpt_length() ) {
		$more_str = '';
	}

	return $more_str;
}

add_filter( 'excerpt_more', 'ys_excerpt_more' );

/**
 * 投稿抜粋文を作成
 *
 * @param string  $sep     抜粋最後の文字.
 * @param integer $length  抜粋長さ.
 * @param integer $post_id 投稿ID.
 *
 * @return string
 */
function ys_get_the_custom_excerpt( $sep = ' …', $length = 0, $post_id = 0 ) {
	if ( 0 === $post_id ) {
		$post_id = get_the_ID();
	}
	if ( 0 === $length ) {
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

/**
 * 投稿抜粋文を出力
 *
 * @param string  $sep     抜粋最後の文字.
 * @param integer $length  抜粋長さ.
 * @param integer $post_id 投稿ID.
 */
function ys_the_custom_excerpt( $sep = ' …', $length = 0, $post_id = 0 ) {
	echo ys_get_the_custom_excerpt( $length, $sep, $post_id );
}

/**
 * 続きを読む テキスト
 */
function ys_get_entry_read_more_text() {
	$read_more = 'READ MORE';

	return apply_filters( 'ys_entry_read_more_text', $read_more );
}

/**
 * 続きを読む テキスト
 */
function ys_the_entry_read_more_text() {
	echo ys_get_entry_read_more_text();
}
