<?php
/**
 * コンテンツ周りの処理
 */

/*
 *	ブログタイトルの区切り文字変更
 */
if( ! function_exists( 'ys_document_title_separator' ) ) {
	function ys_document_title_separator( $sep ) {
		$sep_option = ys_get_option( 'ys_title_separate' );
		if('' != $sep_option){
			$sep = $sep_option;
		}
		return $sep;
	}
}
add_filter( 'document_title_separator', 'ys_document_title_separator' );
/**
 * moreタグの置換
 */
if( ! function_exists( 'ys_more_tag_replace' ) ){
	function ys_more_tag_replace( $the_content ) {
		$replace = ys_get_ad_more_tag();
		$replace = apply_filters( 'ys_more_tag_replace' , $replace );
		if( '' !== $replace ){
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
/**
 * iframeのレスポンシブ化
 */
if( ! function_exists( 'ys_add_iframe_responsive_container' ) ) {
	function ys_add_iframe_responsive_container( $the_content ) {
		if ( is_singular() && ! ys_is_amp() ) {
			/**
			 * マッチさせたいiframeのURLをリスト化
			 */
			$pattern_list = array(
								'youtube\.com',
								'vine\.co',
								'https:\/\/www\.google\.com\/maps\/embed'
							);
			/**
			 * 置換する
			 */
			foreach ( $pattern_list as $value ) {
				$pattern = '/<iframe[^>]+?'.$value.'[^<]+?<\/iframe>/is';
				$the_content = preg_replace( $pattern, '<div class="responsive__container"><div class="responsive__item">${0}</div></div>', $the_content);
			}
		}
		return $the_content;
	}
}
add_filter( 'the_content', 'ys_add_iframe_responsive_container' );
/**
 * 投稿抜粋文字数
 */
if( ! function_exists( 'ys_excerpt_length' ) ){
	function ys_excerpt_length( $length ) {
		return 120;
	}
}
add_filter( 'excerpt_length', 'ys_excerpt_length', 999 );
/**
 * 投稿抜粋の最後につける文字
 */
if( ! function_exists( 'ys_excerpt_more' ) ){
	function ys_excerpt_more( $more ) {
		return '…';
	}
}
add_filter( 'excerpt_more', 'ys_excerpt_more' );

/**
 * 投稿抜粋文を作成
 */
if( ! function_exists( 'ys_get_the_custom_excerpt' ) ) {
	function ys_get_the_custom_excerpt( $sep = ' …', $length = 0, $post_id = 0 ){
		if( ! is_singular() ) {
			return '';
		}
		if( 0 == $post_id ) {
			$post_id = get_the_ID();
		}
		if( 0 == $length ) {
			$length = 80;
		}
		$post = get_post( $post_id );
		$content = $post->post_content;
		/**
		 * moreタグ以降を削除
		 */
		$content = preg_replace( '/<!--more-->.+/is', '', $content );
		/**
		 * HTMLタグ削除
		 */
		$content = wp_strip_all_tags( $content, true );
		/**
		 * ショートコード削除
		 */
		$content = strip_shortcodes( $content );
		/**
		 * 長さ調節
		 */
		if( mb_strlen( $content ) > $length ) {
			$content =  mb_substr( $content, 0, $length - mb_strlen( $sep ) ) . $sep;
		}
		return apply_filters( 'ys_get_the_custom_excerpt', $content, $post_id );
	}
}
/**
 * 投稿抜粋文を出力
 */
if( ! function_exists( 'ys_the_custom_excerpt' ) ) {
	function ys_the_custom_excerpt( $sep = ' …', $length = 0, $post_id = 0 ){
		echo ys_get_the_custom_excerpt( $length, $sep, $post_id );
	}
}
/**
 * 続きを読む テキスト
 */
if ( ! function_exists( 'ys_get_entry_read_more_text' ) ) {
	function ys_get_entry_read_more_text() {
		$read_more = 'READ MORE';
		return apply_filters( 'ys_entry_read_more_text', $read_more );
	}
}
function ys_the_entry_read_more_text() {
	echo ys_get_entry_read_more_text();
}