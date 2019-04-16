<?php
/**
 * タグ関連の関数
 *
 * @package ystandard
 * @author yosiakatsuki
 * @license GPL-2.0+
 */

if ( ! function_exists( 'ys_get_the_tag' ) ) {
	/**
	 * 投稿タグ一覧取得
	 *
	 * @param int     $count count.
	 * @param boolean $link create link.
	 * @param int     $post_id post ID.
	 */
	function ys_get_the_tag( $count = 0, $link = true, $post_id = 0 ) {
		$post_id = 0 === $post_id ? get_the_ID() : $post_id;
		$tags    = get_the_tags( $post_id );
		$list    = array();
		/**
		 * 一覧作成
		 */
		$i = 0;
		if ( $tags ) {
			foreach ( $tags as $tag ) {
				$html = sprintf(
					'<span class="tag tag-%s">%s</span>',
					esc_attr( $tag->slug ),
					$tag->name
				);
				if ( $link ) {
					$html = sprintf(
						'<a class="tag__link" href="%s">%s</a>',
						get_tag_link( $tag->term_id ),
						$html
					);
				}
				$list[] = $html;
				$i ++;
				if ( 0 < $count && $count <= $i ) {
					break;
				}
			}
		}
		return $list;
	}
}

if ( ! function_exists( 'ys_get_the_tag_list' ) ) {
	/**
	 * 投稿についているタグ一覧を取得
	 *
	 * @param string  $separator separator.
	 * @param boolean $link create link.
	 * @param int     $post_id post ID.
	 */
	function ys_get_the_tag_list( $separator = '', $link = true, $post_id = 0 ) {
		$list = ys_get_the_tag( 0, $link, $post_id );
		if ( empty( $list ) ) {
			return '';
		}
		return implode( $separator, $list );
	}
}
if ( ! function_exists( 'ys_the_tag_list' ) ) {
	/**
	 * 投稿についているタグ一覧を表示
	 *
	 * @param string  $separator separator.
	 * @param boolean $link create link.
	 * @param int     $post_id post ID.
	 */
	function ys_the_tag_list( $separator = '', $link = true, $post_id = 0 ) {
		echo ys_get_the_tag_list( $separator, $link, $post_id );
	}
}