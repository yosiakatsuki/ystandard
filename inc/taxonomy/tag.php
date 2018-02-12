<?php
/**
 * タグ関連の関数
 */

/**
 * 投稿タグ一覧取得
 */
if( ! function_exists( 'ys_get_the_tag' ) ) {
	function ys_get_the_tag( $count = 0, $link = true, $post_id = 0 ) {
		$post_id = 0 == $post_id ? get_the_ID() : $post_id;
		$tags = get_the_tags( $post_id );
		$list = array();
		$i = 0;
		if( $tags ){
			foreach( $tags as $tag ) {
				$html = sprintf(
									'<span class="tag tag-%s">%s</span>',
									esc_attr( $tag->slug ),
									$tag->name
								);
				if( $link ) {
					$html = sprintf(
										'<a class="tag__link" href="%s">%s</a>',
										get_tag_link( $tag->term_id ),
										$html
									);
				}
				$list[] = $html;
				$i += 1;
				if( 0 < $count && $count <= $i ){
					break;
				}
			}
		}
		return $list;
	}
}
/**
 * 投稿についているタグ一覧を表示
 */
if( ! function_exists( 'ys_get_the_tag_list' ) ) {
	function ys_get_the_tag_list( $separator = '', $link = true, $post_id = 0 ) {
		$list = ys_get_the_tag( 0, $link, $post_id );
		if( empty( $list ) ) {
			return '';
		}
		return implode( $separator, $list );
	}
}
if( ! function_exists( 'ys_the_tag_list' ) ) {
	function ys_the_tag_list( $separator = '', $link = true, $post_id = 0 ) {
		echo ys_get_the_tag_list( $separator, $link, $post_id );
	}
}