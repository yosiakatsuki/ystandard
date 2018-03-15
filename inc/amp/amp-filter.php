<?php
/**
 * AMPページに関わるフィルター処理
 *
 * @package ystandard
 * @author yosiakatsuki
 * @license GPL-2.0+
 */

if ( ! function_exists( 'ys_amp_convert_content' ) ) {
	/**
	 * 投稿内容をAMPに変換する
	 *
	 * @param  string $content 投稿内容.
	 * @return string
	 */
	function ys_amp_convert_content( $content ) {
		if ( ! ys_is_amp() ) {
			return $content;
		}
		/**
		 * AMP関連の置換の前になにか置換したい場合
		 */
		$content = apply_filters( 'ys_amp_convert_before', $content );
		$content = ys_amp_convert_all( $content );
		return apply_filters( 'ys_convert_amp', $content );
	}
}
add_filter( 'the_content', 'ys_amp_convert_content', 11 );

if ( ! function_exists( 'ys_amp_convert_all' ) ) {
	function ys_amp_convert_all( $content ) {
			/**
		 * HTMLタグなどの置換
		 */
		$content = ys_amp_convert_html( $content );
		/**
		 * sns埋め込みの置換
		 */
		$content = ys_amp_convert_sns( $content );
		/**
		 * imgの置換
		 */
		$content = ys_amp_convert_image( $content );
		/**
		 * iframeの置換
		 */
		$content = ys_amp_convert_iframe( $content );
		/**
		 * scriptの削除
		 */
		$content = ys_amp_delete_script( $content );
		/**
		 * styleの削除
		 */
		$content = ys_amp_delete_style( $content );
		return $content;
	}
}