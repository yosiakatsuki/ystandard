<?php
/**
 * 投稿サムネイル関連
 *
 * @package ystandard
 * @author  yosiakatsuki
 * @license GPL-3.0+
 */
namespace ystandard\utils;

defined( 'ABSPATH' ) || die();

/**
 * Class Thumbnail
 *
 * @package ystandard
 */
class Thumbnail {

	/**
	 * アイキャッチ画像の画像オブジェクト
	 *
	 * @param int $post_id int Post ID.
	 * @param string $size Size.
	 * @param bool $icon Icon.
	 *
	 * @return array|false
	 */
	public static function get_post_thumbnail_src( $post_id = 0, $size = 'full', $icon = false ) {
		$post_id       = 0 === $post_id ? get_the_ID() : $post_id;
		$attachment_id = get_post_thumbnail_id( $post_id );

		return wp_get_attachment_image_src( $attachment_id, $size, $icon );
	}

}
