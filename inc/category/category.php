<?php
/**
 * カテゴリー関連の関数
 */
if( ! function_exists( 'ys_the_entry_category' ) ) {
	function ys_the_entry_category( $link = true, $post_id = 0 ) {
		$category = ys_get_the_category( 1, $link, $post_id );
		$html = '';
		if( ! empty( $category ) ) {
			$html = $category[0];
		}
		echo $html;
	}
}
/**
 * 投稿カテゴリー一覧取得
 */
if( ! function_exists( 'ys_get_the_category' ) ) {
	function ys_get_the_category( $count = 0, $link = true, $post_id = 0 ) {
		$post_id = 0 == $post_id ? get_the_ID() : $post_id;
		$categories = get_the_category( $post_id );
		$list = array();
		$i = 0;
		if( $categories ){
			foreach( $categories as $category ) {
				$html = sprintf(
									'<span class="category category-%s">%s</span>',
									esc_attr( $category->slug ),
									$category->cat_name
								);
				if( $link ) {
					$html = sprintf(
										'<a class="category__link" href="%s">%s</a>',
										get_category_link( $category->term_id ),
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