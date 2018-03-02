<?php
/**
 * @package ystandard
 * @author yosiakatsuki
 * @license GPL-2.0+
 */
/**
 * カテゴリー関連の関数
 */

/**
 * 投稿についたカテゴリー1件を表示する
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
/**
 * 投稿についているカテゴリー一覧を表示
 */
if( ! function_exists( 'ys_the_category_list' ) ) {
	function ys_the_category_list( $separator = '', $link = true, $post_id = 0 ) {
		$list = ys_get_the_category( 0, $link = true, $post_id = 0 );
		echo implode( $separator, $list );
	}
}
/**
 * カテゴリーIDリスト取得
 */
if( ! function_exists( 'ys_get_the_category_id_list' ) ) {
	function ys_get_the_category_id_list( $children = false ) {
		$cat_list = array();
		if( is_category() ) {
			$cat = get_queried_object();
			$cat_list[] = $cat->term_id;
		} elseif( is_single() ) {
			$cat = get_the_category();
			if( ! $cat ) {
				return array();
			}
			foreach( $cat as $cat_obj ){
				$cat_list[] = $cat_obj->cat_ID;
			}
		}
		/**
		 * 子孫の取得
		 */
		if( $children ) {
			foreach( $cat_list as $cat_id ){
				$cat_children = get_term_children( (int)$cat_id, 'category' );
				foreach( $cat_children as $cat_children_id ){
					$cat_list[] = $cat_children_id;
				}
			}
		}
		return $cat_list;
	}
}