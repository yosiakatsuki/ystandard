<?php
/**
 * アーカイブ関連の処理
 *
 * @package ystandard
 * @author  yosiakatsuki
 * @license GPL-2.0+
 */

/**
 * アーカイブタイトル
 *
 * @param string $title title.
 *
 * @return string
 */
function ys_get_the_archive_title( $title ) {
	/**
	 * 標準フォーマット
	 */
	$title_format = apply_filters( 'ys_archive_title_format', '%s' );
	/**
	 * ページング
	 */
	$paged = get_query_var( 'paged' );

	if ( is_category() ) {
		$title = sprintf( $title_format, single_cat_title( '', false ) );
	} elseif ( is_tag() ) {
		$title = sprintf( $title_format, single_tag_title( '', false ) );
	} elseif ( is_author() ) {
		$title = sprintf( $title_format, get_the_author() );
	} elseif ( is_search() ) {
		$title_format = '「%s」の検索結果';
		$title        = sprintf( $title_format, esc_html( get_search_query( false ) ) );
	} elseif ( is_post_type_archive() ) {
		$title = sprintf( $title_format, post_type_archive_title( '', false ) );
	} elseif ( is_tax() ) {
		$object = get_queried_object();
		$tax    = get_taxonomy( $object->taxonomy );
		/* translators: 1: Taxonomy singular name, 2: Current taxonomy term */
		$title = sprintf( '%1$s : %2$s', $tax->labels->singular_name, single_term_title( '', false ) );
	} elseif ( is_home() ) {
		if ( 'page' === get_option( 'show_on_front' ) && get_option( 'page_for_posts' ) ) {
			$title = get_the_title( get_option( 'page_for_posts' ) );
		} else {
			$title = '';
		}
	}

	return apply_filters( 'ys_get_the_archive_title', $title, $paged );
}

add_filter( 'get_the_archive_title', 'ys_get_the_archive_title' );

/**
 * アーカイブURL
 */
function ys_get_the_archive_url() {
	$url            = '';
	$queried_object = get_queried_object();
	if ( is_category() ) {
		$url = get_category_link( $queried_object->term_id );
	} elseif ( is_tag() ) {
		$url = get_tag_link( $queried_object->term_id );
	} elseif ( is_author() ) {
		$author_id = get_query_var( 'author' );
		$url       = esc_url( get_author_posts_url( get_the_author_meta( 'ID', $author_id ) ) );
	} elseif ( is_search() ) {
		$url = home_url( '/?s=' . urlencode( get_search_query( false ) ) );
		$url = get_post_type_archive_link( esc_url_raw( $url ) );
	} elseif ( is_post_type_archive() ) {
		$post_type = ys_get_post_type();
		$url       = get_post_type_archive_link( $post_type );
	} elseif ( is_tax() ) {
		$tax = get_taxonomy( $queried_object->taxonomy );
		$url = get_term_link( $queried_object->term_id, $tax->name );
	}

	return apply_filters( 'ys_get_the_archive_url', $url );
}

/**
 * アーカイブ明細クラス作成
 *
 * @return array
 */
function ys_get_archive_post_class() {
	$class = array();
	/**
	 * 共通でセットするクラス
	 */
	$class[] = 'archive__item';
	$class[] = 'flex__col--1';
	/**
	 * タイプ別
	 */
	if ( 'card' === ys_get_option( 'ys_archive_type', 'list' ) ) {
		$class[] = '-card';
		$class[] = 'flex__col--md-2';
		if ( ys_is_one_column() ) {
			$class[] = 'flex__col--lg-3';
		} else {
			$class[] = 'flex__col--lg-2';
		}
	} else {
		$class[] = '-list';
		$class[] = 'item-list__item';
	}

	return $class;
}

/**
 * アーカイブ明細クラス出力
 */
function ys_the_archive_post_class() {
	$classes = ys_get_archive_post_class();
	echo implode( ' ', $classes );
}

/**
 * アーカイブテンプレートタイプ取得
 */
function ys_get_archive_template_type() {
	return apply_filters( 'ys_get_archive_template_type', ys_get_option( 'ys_archive_type', 'list' ) );
}
