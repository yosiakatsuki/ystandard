<?php
/**
 * パンくずリスト
 *
 * @package ystandard
 * @author  yosiakatsuki
 * @license GPL-2.0+
 */

/**
 * 参考：https://github.com/inc2734/wp-breadcrumbs
 */

/**
 * パンくずリストデータ取得
 *
 * @return array
 */
function ys_get_breadcrumbs() {
	$items          = array();
	$show_on_front  = get_option( 'show_on_front' );
	$page_on_front  = get_option( 'page_on_front' );
	$page_for_posts = get_option( 'page_for_posts' );
	/**
	 * Front-page or Home
	 */
	$label = __( 'Home', 'ystandard' );
	if ( is_front_page() ) {
		$link = '';
		if ( $page_on_front ) {
			$label = get_the_title( $page_on_front );
			$link  = home_url( '/' );
		}
		$items = ys_set_breadcrumb_item(
			$items,
			$label,
			$link
		);

		return apply_filters( 'ys_get_breadcrumbs', $items );
	}
	$items = ys_set_breadcrumb_item( $items, $label, home_url( '/' ) );
	/**
	 * Page for posts
	 */
	$item = ys_get_page_for_posts_name( $items, $show_on_front, $page_for_posts );
	if ( $item ) {
		$items = $item;
	}
	/**
	 * ページ属性ごと
	 */
	if ( is_404() ) {
		/**
		 * 404 not found
		 */
		$items = ys_set_breadcrumb_item( $items, __( 'Page not found', 'ystandard' ) );
	} elseif ( is_search() ) {
		/**
		 * Search
		 */
		/* translators: %1$s 検索クエリ. */
		$title = sprintf( __( 'Search results of "%1$s"', 'ystandard' ), get_search_query() );
		$items = ys_set_breadcrumb_item(
			$items,
			$title,
			esc_url_raw( home_url( '?s=' . urlencode( get_query_var( 's' ) ) ) )
		);
	} elseif ( is_tax() ) {
		/**
		 * Taxonomy
		 */
		$taxonomy         = get_query_var( 'taxonomy' );
		$term             = get_term_by( 'slug', get_query_var( 'term' ), $taxonomy );
		$taxonomy_objects = get_taxonomy( $taxonomy );
		$post_types       = $taxonomy_objects->object_type;
		$post_type        = array_shift( $post_types );
		if ( $post_type ) {
			$post_type_object = get_post_type_object( $post_type );
			$label            = $post_type_object->label;
			if ( $post_type_object->has_archive ) {
				$items = ys_set_breadcrumb_item( $items, $label, get_post_type_archive_link( $post_type ) );
			}
		}
		if ( is_taxonomy_hierarchical( $taxonomy ) && $term->parent ) {
			$items = ys_set_breadcrumb_ancestors( $items, $term->term_id, $taxonomy );
		}
		$items = ys_set_breadcrumb_item( $items, $term->name );
	} elseif ( is_attachment() ) {
		/**
		 * Attachment
		 */
		$items = ys_set_breadcrumb_item( $items, get_the_title() );
	} elseif ( is_page() ) {
		/**
		 * Page
		 */
		$items = ys_set_breadcrumb_ancestors( $items, get_the_ID(), 'page' );
		$items = ys_set_breadcrumb_item( $items, get_the_title(), get_the_permalink() );
	} elseif ( is_post_type_archive() ) {
		/**
		 * Post_type_archive
		 */
		$post_type = ys_get_post_type();
		if ( $post_type && 'post' !== $post_type ) {
			$post_type_object = get_post_type_object( $post_type );
			$label            = $post_type_object->label;
			$items            = ys_set_breadcrumb_item( $items, $label, get_post_type_archive_link( $post_type_object->name ) );
		}
	} elseif ( is_single() ) {
		/**
		 * Single
		 */
		$post_type = ys_get_post_type();
		if ( $post_type && 'post' !== $post_type ) {
			$post_type_object = get_post_type_object( $post_type );
			$label            = $post_type_object->label;
			$taxonomies       = $post_type_object->taxonomies;
			$taxonomy         = array_shift( $taxonomies );
			$terms            = get_the_terms( get_the_ID(), $taxonomy );
			$items            = ys_set_breadcrumb_item(
				$items,
				$label,
				get_post_type_archive_link( $post_type )
			);
			if ( $terms ) {
				$term  = array_shift( $terms );
				$items = ys_set_breadcrumb_ancestors( $items, $term->term_id, $taxonomy );
				$items = ys_set_breadcrumb_item( $items, $term->name, get_term_link( $term ) );
			}
		} else {
			$categories = get_the_category( get_the_ID() );
			$category   = $categories[0];
			$items      = ys_set_breadcrumb_ancestors( $items, $category->term_id, 'category' );
			$link       = get_term_link( $category );
			if ( is_wp_error( $link ) ) {
				$link = '';
			}
			$items = ys_set_breadcrumb_item( $items, $category->name, $link );
		}
	} elseif ( is_category() ) {
		/**
		 * Category
		 */
		$category_name = single_cat_title( '', false );
		$category_id   = get_cat_ID( $category_name );
		$items         = ys_set_breadcrumb_ancestors( $items, $category_id, 'category' );
		$items         = ys_set_breadcrumb_item( $items, $category_name, get_category_link( $category_id ) );
	} elseif ( is_tag() ) {
		/**
		 * Tag
		 */
		$items = ys_set_breadcrumb_item( $items, single_tag_title( '', false ), get_tag_link( get_queried_object() ) );
	} elseif ( is_author() ) {
		/**
		 * Author
		 */
		$author_id = get_query_var( 'author' );
		$items     = ys_set_breadcrumb_item(
			$items,
			ys_get_author_display_name( $author_id ),
			get_author_posts_url( $author_id )
		);
	} elseif ( is_day() ) {
		/**
		 * Day
		 */
		$year = get_query_var( 'year' );
		if ( $year ) {
			$month = get_query_var( 'monthnum' );
			$day   = get_query_var( 'day' );
		} else {
			$ymd   = get_query_var( 'm' );
			$year  = substr( $ymd, 0, 4 );
			$month = substr( $ymd, 4, 2 );
			$day   = substr( $ymd, - 2 );
		}
		$items = ys_set_breadcrumb_year( $items, $year );
		$items = ys_set_breadcrumb_month( $items, $year, $month );
		$items = ys_set_breadcrumb_day( $items, $day, $month, $year );
	} elseif ( is_month() ) {
		/**
		 * Month
		 */
		$year = get_query_var( 'year' );
		if ( $year ) {
			$month = get_query_var( 'monthnum' );
		} else {
			$ymd   = get_query_var( 'm' );
			$year  = substr( $ymd, 0, 4 );
			$month = substr( $ymd, - 2 );
		}
		$items = ys_set_breadcrumb_year( $items, $year );
		$items = ys_set_breadcrumb_month( $items, $year, $month );
	} elseif ( is_year() ) {
		/**
		 * Year
		 */
		$year = get_query_var( 'year' );
		if ( ! $year ) {
			$ymd  = get_query_var( 'm' );
			$year = $ymd;
		}
		$items = ys_set_breadcrumb_year( $items, $year );
	} elseif ( is_home() ) {
		/**
		 * Home
		 */
		if ( 'page' === $show_on_front && $page_for_posts ) {
			$items = ys_set_breadcrumb_item( $items, get_the_title( $page_for_posts ), get_permalink( $page_for_posts ) );
		}
	}

	return apply_filters( 'ys_get_breadcrumbs', $items );
}

/**
 * パンくず用配列セット
 *
 * @param array  $items items.
 * @param string $title title.
 * @param string $link  url.
 *
 * @return array
 */
function ys_set_breadcrumb_item( $items, $title, $link = '' ) {
	if ( empty( $link ) ) {
		$link = '';
	}
	$items[] = array(
		'title' => $title,
		'link'  => $link,
	);

	return apply_filters( 'ys_set_breadcrumb_item', $items, $title, $link );
}

/**
 * フロントページ指定がある時の一覧ページタイトル
 *
 * @param array  $items          items.
 * @param string $show_on_front  show_on_front.
 * @param int    $page_for_posts page_for_posts.
 *
 * @return mixed
 */
function ys_get_page_for_posts_name( $items, $show_on_front, $page_for_posts ) {
	$post_type = ys_get_post_type();
	if ( ( is_single() && 'post' === $post_type ) || is_date() || is_author() || is_category() || is_tax() ) {
		if ( 'page' === $show_on_front && $page_for_posts && ys_get_option( 'ys_show_page_for_posts_on_breadcrumbs' ) ) {
			return ys_set_breadcrumb_item(
				$items,
				get_the_title( $page_for_posts ),
				get_permalink( $page_for_posts )
			);
		}
	}

	return false;
}

/**
 * 親の取得と並び替え
 *
 * @param int    $object_id     object_id.
 * @param string $object_type   object_type.
 * @param string $resource_type resource_type.
 *
 * @return array
 */
function ys_get_breadcrumb_ancestors( $object_id, $object_type, $resource_type = '' ) {
	$ancestors = get_ancestors( $object_id, $object_type, $resource_type );
	krsort( $ancestors );

	return apply_filters( 'ys_get_breadcrumb_ancestors', $ancestors, $object_id, $object_type, $resource_type );
}

/**
 * Set ancestors and krsort
 *
 * @param array  $items         items.
 * @param int    $object_id     object_id.
 * @param string $object_type   object_type.
 * @param string $resource_type resource_type.
 *
 * @return array
 */
function ys_set_breadcrumb_ancestors( $items, $object_id, $object_type, $resource_type = '' ) {
	$ancestors = ys_get_breadcrumb_ancestors( $object_id, $object_type, $resource_type );
	foreach ( $ancestors as $ancestor_id ) {
		if ( 'page' === $object_type ) {
			$items = ys_set_breadcrumb_item(
				$items,
				get_the_title( $ancestor_id ),
				get_permalink( $ancestor_id )
			);
		} else {
			$ancestors_term = get_term_by( 'id', $ancestor_id, $object_type );
			if ( $ancestors_term ) {
				$items = ys_set_breadcrumb_item(
					$items,
					$ancestors_term->name,
					get_term_link( $ancestor_id, $object_type )
				);
			}
		}
	}

	return apply_filters( 'ys_set_breadcrumb_ancestors', $items, $object_id, $object_type, $resource_type );
}

/**
 * 年のセット
 *
 * @param array   $items items.
 * @param int     $year  year.
 * @param boolean $link  set url.
 *
 * @return array
 */
function ys_set_breadcrumb_year( $items, $year, $link = true ) {
	$label = $year;
	$url   = '';
	if ( 'ja' === get_locale() ) {
		$label .= '年';
	}
	if ( $link ) {
		$url = get_year_link( $year );
	}

	return ys_set_breadcrumb_item( $items, $label, $url );
}

/**
 * 月
 *
 * @param array   $items items.
 * @param int     $year  year.
 * @param int     $month month.
 * @param boolean $link  set url.
 *
 * @return array
 */
function ys_set_breadcrumb_month( $items, $year, $month, $link = true ) {
	$label = $month;
	$url   = '';
	if ( 'ja' === get_locale() ) {
		$label .= '月';
	} else {
		$monthes = array(
			1  => 'January',
			2  => 'February',
			3  => 'March',
			4  => 'April',
			5  => 'May',
			6  => 'June',
			7  => 'July',
			8  => 'August',
			9  => 'September',
			10 => 'October',
			11 => 'November',
			12 => 'December',
		);
		$label   = $monthes[ $month ];
	}
	if ( $link ) {
		$url = get_month_link( $year, $month );
	}

	return ys_set_breadcrumb_item( $items, $label, $url );
}

/**
 * 日
 *
 * @param array $items items.
 * @param int   $day   day.
 * @param int   $month month.
 * @param int   $year  year.
 *
 * @return array
 */
function ys_set_breadcrumb_day( $items, $day, $month, $year ) {
	$label = $day;
	if ( 'ja' === get_locale() ) {
		$label .= '日';
	}

	return ys_set_breadcrumb_item( $items, $label, get_day_link( $year, $month, $day ) );
}
