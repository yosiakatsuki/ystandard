<?php
/**
 * @package ystandard
 * @author yosiakatsuki
 * @license GPL-2.0+
 */
/**
 * AMPページに関わるフィルター処理
 */
if( ! function_exists( 'ys_amp_convert_content' ) ) {
	function ys_amp_convert_content( $content ) {
		if( ! ys_is_amp() ) {
			return $content;
		}
		/**
		 * AMP関連の置換の前になにか置換したい場合
		 */
		$content = apply_filters( 'ys_amp_convert_before', $content );
		/**
		 * HTMLタグなどの置換
		 */
		$content = ys_amp_convert_html( $content );
		/**
		 * oembed埋め込みの置換
		 */
		$content = ys_amp_convert_oembed( $content );
		/**
		 * sns埋め込みの置換
		 */
		$content = ys_amp_convert_sns( $content );
		/**
		 * imgの置換
		 */
		$content = ys_amp_convert_image($content);
		/**
		 * iframeの置換
		 */
		$content = ys_amp_convert_iframe($content);
		/**
		 * scriptの削除
		 */
		$content = ys_amp_delete_script( $content );
		/**
		 * styleの削除
		 */
		$content = ys_amp_delete_style($content);
		return apply_filters( 'ys_convert_amp', $content );
	}
}
add_filter( 'the_content', 'ys_amp_convert_content', 11 );