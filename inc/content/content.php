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