<?php
/**
 * ピンバックURLの出力
 */
if( ! function_exists( 'ys_the_pingback_url' ) ) {
	function ys_the_pingback_url() {
		if ( is_singular() && pings_open( get_queried_object() ) ){
			echo '<link rel="pingback" href="' . get_bloginfo( 'pingback_url' ) . '">';
		}
	}
}
add_action( 'wp_head', 'ys_the_pingback_url' );

/**
 * apple touch icon
 */
if( ! function_exists( 'ys_the_apple_touch_icon' ) ){
	function ys_the_apple_touch_icon() {
		if ( ! ys_utilities_get_apple_touch_icon_url( 512, '' ) && ! is_customize_preview() ) {
			return;
		}
		printf(
			'<link rel="apple-touch-icon-precomposed" href="%s" />',
			esc_url( ys_utilities_get_apple_touch_icon_url( 180 ) )
		);
		printf(
			'<meta name="msapplication-TileImage" content="%s" />',
			esc_url( ys_utilities_get_apple_touch_icon_url( 270 ) )
		);
	}
}
add_filter( 'wp_head', 'ys_the_apple_touch_icon' );

/**
 * canonicalタグ出力
 */
if( ! function_exists( 'ys_the_canonical_tag' ) ) {
	function ys_the_canonical_tag(){

		$canonical = '';
		if( is_home() || is_front_page() ) {
				$canonical = home_url();

		} elseif ( is_category() ) {
			$canonical = get_category_link( get_query_var('cat') );

		} elseif ( is_tag() ) {
			$tag = get_term_by( 'name', urldecode( get_query_var('tag') ), 'post_tag' );
			$canonical = get_tag_link( $tag->term_id );

		} elseif ( is_search() ) {
			$canonical = get_search_link();

		} elseif ( is_page() || is_single() ) {
			$canonical = get_permalink();

		}
		if( $canonical !== '' ){
			printf( '<link rel="canonical" href="%s">', $canonical );
		}
	}
}
add_action( 'wp_head', 'ys_the_canonical_tag' );

/**
 * next,prevタグ出力
 */
if(!function_exists( 'ys_the_rel_link')) {
	function ys_the_rel_link(){
		if( is_single() || is_page() ) {
			//固定ページ・投稿ページ
			global $post,$page;
			$pagecnt = substr_count( $post->post_content, '<!--nextpage-->' ) + 1;

			if ( $pagecnt > 1 ){
				//prev
				if( $page > 1 ) {
					printf( '<link rel="prev" href="%s" />', ys_utilities_get_the_link_page( $page - 1 ) );
				}
				//next
				if( $page < $pagecnt ) {
					$page = 0 == $page ? 1 : $page;
					printf( '<link rel="next" href="%s" />', ys_utilities_get_the_link_page( $page + 1 ) );
				}
			}
		} else {
			//アーカイブ
			global $wp_query;
			// MAXページ数と現在ページ数を取得
			$total   = isset( $wp_query->max_num_pages ) ? $wp_query->max_num_pages : 1;
			$current = get_query_var( 'paged' ) ? (int) get_query_var( 'paged' )  : 1;
			if( $current > 1 ) {
				printf( '<link rel="prev" href="%s" />', get_pagenum_link( $current - 1 ) );
			}
			if($current < $total) {
				printf( '<link rel="next" href="%s" />', get_pagenum_link( $current + 1 ) );
			}
		}
	}
}
add_action( 'wp_head', 'ys_the_rel_link' );

/**
 * noindex
 */
if( ! function_exists( 'ys_the_noindex' ) ) {
	function ys_the_noindex(){
		$noindexoutput = false;

		if( is_404() ){
			//  404ページをnoindex
			$noindexoutput = true;

		} elseif( is_search() ) {
			// 検索結果をnoindex
			$noindexoutput = true;

		} elseif( is_category() && 1 == ys_get_setting( 'ys_archive_noindex_category', 0 ) ) {
			// カテゴリーページのnoindex設定がされていればnoindex
			$noindexoutput = true;

		} elseif( is_tag() && 1 == ys_get_setting( 'ys_archive_noindex_tag', 1 ) ){
			// カテゴリーページのnoindex設定がされていればnoindex
			$noindexoutput = true;

		} elseif( is_author() && 1 == ys_get_setting( 'ys_archive_noindex_author',1 ) ){
			// カテゴリーページのnoindex設定がされていればnoindex
			$noindexoutput = true;

		} elseif( is_date() && 1 == ys_get_setting( 'ys_archive_noindex_date', 1 ) ){
			// カテゴリーページのnoindex設定がされていればnoindex
			$noindexoutput = true;

		} elseif( is_single() || is_page() ){
			if( '1' === get_post_meta( get_the_ID(), 'ys_noindex', true ) ){
				// 投稿・固定ページでnoindex設定されていればnoindex
				$noindexoutput = true;
			}
		}
		$noindexoutput = apply_filters( 'ys_the_noindex', $noindexoutput );
		// noindex出力
		if( $noindexoutput ){
			echo '<meta name="robots" content="noindex,follow">' . PHP_EOL;
		}
	}
}
add_action( 'wp_head', 'ys_the_noindex' );