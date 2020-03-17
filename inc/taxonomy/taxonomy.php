<?php
/**
 * タクソノミー関連の関数
 *
 * @package ystandard
 * @author  yosiakatsuki
 * @license GPL-2.0+
 */

/**
 * クラス読み込み
 */
require_once __DIR__ . '/class-taxonomy.php';

/**
 * 投稿についたカテゴリー1件を表示する
 *
 * @param boolean $link    create link.
 * @param int     $post_id post ID.
 */
function ys_the_entry_category( $link = true, $post_id = 0 ) {
	$category = ys_get_the_category( 1, $link, $post_id );
	$html     = '';
	if ( ! empty( $category ) ) {
		$html = $category[0];
	}
	echo $html;
}

/**
 * 投稿カテゴリー一覧取得
 *
 * @param int     $count   count.
 * @param boolean $link    create link.
 * @param int     $post_id post ID.
 *
 * @return array
 */
function ys_get_the_category( $count = 0, $link = true, $post_id = 0 ) {
	$post_id    = 0 === $post_id ? get_the_ID() : $post_id;
	$categories = get_the_category( $post_id );
	$list       = array();
	/**
	 * カテゴリー一覧ページの場合は現在のカテゴリーを先頭にする
	 */
	if ( is_category() ) {
		if ( $categories ) {
			$cat_main = array();
			$cat_sub  = array();
			foreach ( $categories as $category ) {
				if ( single_cat_title( '', false ) === $category->name ) {
					$cat_main[] = $category;
				} else {
					$cat_sub[] = $category;
				}
			}
			$categories = array_merge( $cat_main, $cat_sub );
		}
	}
	/**
	 * 一覧作成
	 */
	$i = 0;
	if ( $categories ) {
		foreach ( $categories as $category ) {
			$html = sprintf(
				'<span class="category category-%s">%s</span>',
				esc_attr( $category->slug ),
				$category->cat_name
			);
			if ( $link ) {
				$html = sprintf(
					'<a class="category__link" href="%s">%s</a>',
					get_category_link( $category->term_id ),
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

/**
 * 投稿についているカテゴリー一覧を表示
 *
 * @param string  $separator separator.
 * @param boolean $link      create link.
 * @param int     $post_id   post ID.
 */
function ys_the_category_list( $separator = '', $link = true, $post_id = 0 ) {
	$list = ys_get_the_category( 0, $link, $post_id );
	ys_the_array_implode( $list, $separator );
}


/**
 * カテゴリーID一覧取得
 *
 * @param boolean $children 子カテゴリーを含むか.
 *
 * @return array
 */
function ys_get_the_category_id_list( $children = false ) {
	$cat_list = array();
	if ( is_category() ) {
		$cat        = get_queried_object();
		$cat_list[] = $cat->term_id;
	} elseif ( is_single() ) {
		$cat = get_the_category();
		if ( ! $cat ) {
			return array();
		}
		foreach ( $cat as $cat_obj ) {
			$cat_list[] = $cat_obj->cat_ID;
		}
	}
	/**
	 * 子孫の取得
	 */
	if ( $children ) {
		foreach ( $cat_list as $cat_id ) {
			$cat_children = get_term_children( (int) $cat_id, 'category' );
			foreach ( $cat_children as $cat_children_id ) {
				$cat_list[] = $cat_children_id;
			}
		}
	}

	return $cat_list;
}


/**
 * 投稿タグ一覧取得
 *
 * @param int     $count   count.
 * @param boolean $link    create link.
 * @param int     $post_id post ID.
 *
 * @return array
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

/**
 * 投稿についているタグ一覧を取得
 *
 * @param string  $separator separator.
 * @param boolean $link      create link.
 * @param int     $post_id   post ID.
 *
 * @return string
 */
function ys_get_the_tag_list( $separator = '', $link = true, $post_id = 0 ) {
	$list = ys_get_the_tag( 0, $link, $post_id );
	if ( empty( $list ) ) {
		return '';
	}

	return implode( $separator, $list );
}

/**
 * 投稿についているタグ一覧を表示
 *
 * @param string  $separator separator.
 * @param boolean $link      create link.
 * @param int     $post_id   post ID.
 */
function ys_the_tag_list( $separator = '', $link = true, $post_id = 0 ) {
	echo ys_get_the_tag_list( $separator, $link, $post_id );
}
