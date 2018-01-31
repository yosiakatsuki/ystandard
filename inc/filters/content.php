<?php
/**
 * コンテンツ部分のフィルターフック
 */

/**
 * moreタグの置換
 */
if( ! function_exists( 'ys_more_tag_replace' ) ){
	function ys_more_tag_replace( $the_content ) {
		$replace = ys_get_ad_more_tag();
		$replace = apply_filters( 'ys_more_tag_replace' , $replace );
		if( '' !== $replace ){
			$the_content = preg_replace('/<p><span id="more-[0-9]+"><\/span><\/p>/', $replace, $the_content);
			/**
			 * 「remove_filter( 'the_content', 'wpautop' )」対策
			 */
			$the_content = preg_replace('/<span id="more-[0-9]+"><\/span>/', $replace, $the_content);
		}
		return $the_content;
	}
}
add_filter( 'the_content', 'ys_more_tag_replace');