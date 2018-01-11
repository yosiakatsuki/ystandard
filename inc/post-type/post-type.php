<?php
/**
 * 投稿タイプ取得
 */
function ys_get_post_type() {
	global $wp_query;
	$post_type = get_post_type();
	if ( ! $post_type ) {
		if ( isset( $wp_query->query['post_type'] ) ) {
			$post_type = $wp_query->query['post_type'];
		}
	}
	return $post_type;
}