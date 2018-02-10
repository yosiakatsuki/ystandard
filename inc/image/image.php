<?php
/**
 * 画像関連の処理
 */
/**
 * 画像オブジェクト取得
 */
if ( ! function_exists( 'ys_get_the_image_object' ) ) {
	function ys_get_the_image_object( $size = 'full', $post_id = null ) {
		$image = false;
		$thumbnail_id = get_post_thumbnail_id( $post_id );
		if( !empty( $post_thumbnail_id ) ) {
			/**
			 * 画像オブジェクト取得
			 */
			$image = wp_get_attachment_image_src( $post_thumbnail_id, $size );
		}
		return $image;
	}
}
/**
 * 画像URLから画像IDを取得する
 */
if ( ! function_exists( 'get_attachment_id_from_src' ) ) {
	function get_attachment_id_from_src( $src ) {
		global $wpdb;
		$attachment = $wpdb->get_col( $wpdb->prepare( "SELECT ID FROM $wpdb->posts WHERE guid='%s';", $image_url ) );
		if( empty( $attachment ) ) {
			return null;
		}
		return $attachment[0];
	}
}
/**
 * 投稿1番目の画像を取得
 */
if ( ! function_exists( 'ys_get_the_first_image_url' ) ) {
	function ys_get_the_first_image_url( $post_id = 0 ) {
		if( 0 === $post_id ){
			$post_id = get_the_ID();
		}
		/**
		 * 記事内容取得
		 */
		$post_content = get_post( $post_id )->post_content;
		$pattern = '/<img.+src=[\'"]([^\'"]+?)[\'"].*?>/i';
		$output = preg_match_all( $pattern, $post_content, $matches );
		if( false === $output || 0 >= $output ) {
			return false;
		}
		return $matches[1][0];
	}
}
/**
 * 投稿1番目の画像を取得
 */
if ( ! function_exists( 'ys_get_the_first_image_object' ) ) {
	function ys_get_the_first_image_object( $post_id = 0 ) {
		$url = ys_get_the_first_image_url( $post_id );
		if( false === $url ){
			return false;
		}
		$attachment_id = get_attachment_id_from_src( $url );
		if( is_null( $attachment_id ) ){
			return false;
		}
		/**
		 * 画像オブジェクト取得
		 */
		$image = wp_get_attachment_image_src( $attachment_id, 'full' );
		return $image;
	}
}
/*
 *	ImageObject用meta
 */
if ( ! function_exists( 'ys_get_the_image_object_meta' ) ) {
	function ys_get_the_image_object_meta( $image = null ) {
		if( empty( $image ) || is_array( $image ) ) {
			/**
			 * TODO:構造化エラー対処
			 */
			 global $post;
			 $image = ys_get_the_image_object( 'full', $post->ID );
		}
		$meta = '';
		if( $image ) {
			$meta .= '<meta itemprop="url" content="' . $image[0] . '" />';
			$meta .= '<meta itemprop="width" content="' . $image[1] . '" />';
			$meta .= '<meta itemprop="height" content="' . $image[2] . '" />';
		}
		return $meta;
	}
}
/**
 *	画像サイズ取得
 */
if ( ! function_exists( 'ys_get_image_size' ) ) {
	function ys_get_image_size( $img_path ) {
		$size = false;
		if ( file_exists( $img_path ) ) {
			$size = getimagesize( $img_path );
		}
		if ( false === $size ) {
			$size = array( 0, 0 );
		}
		return $size;
	}
}