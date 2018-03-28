<?php
/**
 * RSS関連の処理
 *
 * @package ystandard
 * @author yosiakatsuki
 * @license GPL-2.0+
 */

if ( ! function_exists( 'ys_rss_add_post_thumbnail' ) ) {
	/**
	 * RSSフィードにアイキャッチ画像を表示
	 *
	 * @param string $content content.
	 */
	function ys_rss_add_post_thumbnail( $content ) {
		global $post;
		if ( ys_is_active_post_thumbnail( $post->ID ) ) {
			$content = get_the_post_thumbnail( $post->ID ) . $content;
		}
		return $content;
	}
}
add_filter( 'the_excerpt_rss', 'ys_rss_add_post_thumbnail' );
add_filter( 'the_content_feed', 'ys_rss_add_post_thumbnail' );