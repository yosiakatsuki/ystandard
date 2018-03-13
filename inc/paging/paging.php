<?php
/**
 * @package ystandard
 * @author yosiakatsuki
 * @license GPL-2.0+
 */
/**
 * ページング
 */
if( ! function_exists( 'ys_paging' ) ) {
	function ys_paging() {
		$paging = array();
		if ( ! ys_get_option( 'ys_show_post_paging' ) ) {
			return false;
		}
		if ( ys_is_amp() ) {
			return false;
		}
		/**
		 * 前後の記事を取得
		 */
		$post_prev = get_previous_post();
		$post_next = get_next_post();
		if( empty( $post_prev ) && empty( $post_next ) ) {
			return false;
		}
		/**
		 * 前の記事
		 */
		$image_prev = '';
		if( $post_prev ) {
			if( has_post_thumbnail( $post_prev->ID ) ) {
				$image_prev = get_the_post_thumbnail( 
												$post_prev->ID, 
												'post-thumbnail', 
												array( 'class' => 'entry-paging__image' ) 
											);
			}
			$paging['prev'] = array(
													'url' => esc_url( get_permalink( $post_prev->ID ) ),
													'title' => get_the_title( $post_prev->ID ),
													'image' => $image_prev,
													'text' => '«前の記事'
												);
		} else {
			$paging['prev'] = false;
		}
		$image_next = '';
		if( $post_next ) {
			if( has_post_thumbnail( $post_next->ID ) ) {
				$image_next = get_the_post_thumbnail( 
												$post_next->ID,
												'post-thumbnail', 
												array( 'class' => 'entry-paging__image' ) 
											);
			}
			$paging['next'] = array(
													'url' => esc_url( get_permalink( $post_next->ID ) ),
													'title' => get_the_title( $post_next->ID ),
													'image' => $image_next,
													'text' => '次の記事»'
												);
		} else {
			$paging['next'] = false;
		}
		return $paging;
	}
}