<?php
/**
 * post-meta取得
 */
if( ! function_exists( 'ys_get_post_meta' ) ) {
	function ys_get_post_meta( $key, $post_id = 0 ) {
		if( 0 === $post_id ) $post_id = get_the_ID();
		return apply_filters( 'ys_get_post_meta', get_post_meta( $post_id, $key, true ), $key, $post_id ) ;
	}
}