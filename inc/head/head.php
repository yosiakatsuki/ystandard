<?php
/**
 * @package ystandard
 * @author yosiakatsuki
 * @license GPL-2.0+
 */
/**
 * <head>タグ取得
 */
if( ! function_exists( 'ys_get_the_head_tag' ) ) {
	function ys_get_the_head_tag() {
		$html = '<head prefix="og: http://ogp.me/ns# fb: http://ogp.me/ns/fb# blog: http://ogp.me/ns/blog#">' . PHP_EOL;
		if( is_singular() ){
			$html = '<head prefix="og: http://ogp.me/ns# fb: http://ogp.me/ns/fb# article: http://ogp.me/ns/article#">' . PHP_EOL;
		}
		return apply_filters( 'ys_get_the_head_tag', $html );
	}
}

/**
 * <head>タグ出力
 */
if( ! function_exists( 'ys_the_head_tag' ) ) {
	function ys_the_head_tag() {
		echo ys_get_the_head_tag();
	}
}

/**
 * インラインCSSセット
 */
if( ! function_exists( 'ys_set_inline_style' ) ) {
	function ys_set_inline_style( $style, $minify = true ) {
		global $ys_enqueue;
		$style = $ys_enqueue->set_inline_style( $style, $minify );
	}
}

/**
 * インラインCSS取得
 */
if( ! function_exists( 'ys_get_the_inline_style' ) ) {
	function ys_get_the_inline_style( $is_amp ) {
		global $ys_enqueue;
		$style = $ys_enqueue->get_inline_style( $is_amp );
		return apply_filters( 'ys_get_the_inline_style', $style );
	}
}

/**
 * インラインCSS出力
 */
if( ! function_exists( 'ys_the_inline_style' ) ) {
	function ys_the_inline_style() {
		$style = ys_get_the_inline_style( ys_is_amp() );
		if( ys_is_amp() ) {
			$style = sprintf( '<style amp-custom>%s</style>', $style );
		} else {
			$style = sprintf( '<style id="ystandard-inline-style">%s</style>', $style );
		}
		echo $style . PHP_EOL;
	}
}

/**
 * TOPページのmeta description出力
 */
if( ! function_exists( 'ys_the_meta_description' ) ) {
	function ys_the_meta_description(){
		$dscr = trim( ys_get_option( 'ys_wp_site_description' ) );
		if( ys_is_top_page() && '' != $dscr ){
			echo '<meta name="description"  content="'. $dscr . '" />' . PHP_EOL;
		}
	}
}
add_action( 'wp_head', 'ys_the_meta_description' );

/**
 * ピンバックURLの出力
 */
if( ! function_exists( 'ys_the_pingback_url' ) ) {
	function ys_the_pingback_url() {
		if ( is_singular() && pings_open( get_queried_object() ) ){
			echo '<link rel="pingback" href="' . get_bloginfo( 'pingback_url' ) . '" />' . PHP_EOL;
		}
	}
}
add_action( 'wp_head', 'ys_the_pingback_url' );

/**
 * apple touch icon
 */
if( ! function_exists( 'ys_the_apple_touch_icon' ) ){
	function ys_the_apple_touch_icon() {
		if ( ! ys_get_apple_touch_icon_url( 512, '' ) && ! is_customize_preview() ) {
			return;
		}
		printf(
			'<link rel="apple-touch-icon-precomposed" href="%s" />' . PHP_EOL,
			esc_url( ys_get_apple_touch_icon_url( 180 ) )
		);
		printf(
			'<meta name="msapplication-TileImage" content="%s" />' . PHP_EOL,
			esc_url( ys_get_apple_touch_icon_url( 270 ) )
		);
	}
}
add_filter( 'wp_head', 'ys_the_apple_touch_icon' );

/**
 * apple touch icon用URLを取得
 */
if (!function_exists( 'ys_get_apple_touch_icon_url')) {
	function ys_get_apple_touch_icon_url( $size = 512, $url = '', $blog_id = 0 ) {

		if ( is_multisite() && (int) $blog_id !== get_current_blog_id() ) {
			switch_to_blog( $blog_id );
		}

		$site_icon_id = get_option( 'ys_apple_touch_icon' );
		if ( $site_icon_id ) {
			if ( $size >= 512 ) {
				$size_data = 'full';
			} else {
				$size_data = array( $size, $size );
			}
			$url = wp_get_attachment_image_url( $site_icon_id, $size_data );
		}
		if ( is_multisite() && ms_is_switched() ) {
			restore_current_blog();
		}
		return $url;
	}
}

/**
 * サイトアイコン
 */
if( ! function_exists( 'ys_site_icon_meta_tags' )){
	function ys_site_icon_meta_tags( $meta_tags ) {
		$meta_tags = array(
				sprintf( '<link rel="icon" href="%s" sizes="32x32" />', esc_url( get_site_icon_url( 32 ) ) ),
				sprintf( '<link rel="icon" href="%s" sizes="192x192" />', esc_url( get_site_icon_url( 192 ) ) )
		);
		return $meta_tags;
	}
}
add_filter( 'site_icon_meta_tags', 'ys_site_icon_meta_tags' );

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
			printf( '<link rel="canonical" href="%s">' . PHP_EOL, $canonical );
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
			/**
			 * 固定ページ・投稿ページ
			 */
			global $post,$page;
			$pagecnt = substr_count( $post->post_content, '<!--nextpage-->' ) + 1;

			if ( $pagecnt > 1 ){
				/**
				 * prev
				 */
				if( $page > 1 ) {
					printf( '<link rel="prev" href="%s" />' . PHP_EOL, ys_get_the_link_page( $page - 1 ) );
				}
				/**
				 * next
				 */
				if( $page < $pagecnt ) {
					$page = 0 == $page ? 1 : $page;
					printf( '<link rel="next" href="%s" />' . PHP_EOL, ys_get_the_link_page( $page + 1 ) );
				}
			}
		} else {
			/**
			 * アーカイブ
			 */
			global $wp_query;
			/**
			 * MAXページ数と現在ページ数を取得
			 */
			$total   = isset( $wp_query->max_num_pages ) ? $wp_query->max_num_pages : 1;
			$current = get_query_var( 'paged' ) ? (int) get_query_var( 'paged' )  : 1;
			if( $current > 1 ) {
				printf( '<link rel="prev" href="%s" />' . PHP_EOL, get_pagenum_link( $current - 1 ) );
			}
			if($current < $total) {
				printf( '<link rel="next" href="%s" />' . PHP_EOL, get_pagenum_link( $current + 1 ) );
			}
		}
	}
}
add_action( 'wp_head', 'ys_the_rel_link' );
/**
 *	prev,next用URL取得
 */
if ( !function_exists( 'ys_get_the_link_page' ) ) {
	function ys_get_the_link_page( $i ) {
		global $wp_rewrite;
    $post = get_post();
		if ( 1 == $i ) {
			$url = get_permalink();
		} else {
			if ( '' == get_option( 'permalink_structure' ) || in_array( $post->post_status, array( 'draft', 'pending' ) ) )
				$url = add_query_arg( 'page', $i, get_permalink() );
			elseif ( 'page' == get_option( 'show_on_front' ) && $post->ID == get_option( 'page_on_front' ) )
				$url = trailingslashit( get_permalink() ) . user_trailingslashit( "$wp_rewrite->pagination_base/" . $i, 'single_paged' );
			else
				$url = trailingslashit( get_permalink() ) . user_trailingslashit( $i, 'single_paged' );
		}
		return $url;
	}
}


/**
 * noindex
 */
if( ! function_exists( 'ys_the_noindex' ) ) {
	function ys_the_noindex(){
		$noindex = false;

		if( is_404() ){
			/**
			 * 404ページをnoindex
			 */
			$noindex = true;

		} elseif( is_search() ) {
			/**
			 * 検索結果をnoindex
			 */
			$noindex = true;

		} elseif( is_category() && ys_get_option( 'ys_archive_noindex_category' ) ) {
			/**
			 * カテゴリーページのnoindex設定がされていればnoindex
			 */
			$noindex = true;

		} elseif( is_tag() && ys_get_option( 'ys_archive_noindex_tag' ) ){
			/**
			 * カテゴリーページのnoindex設定がされていればnoindex
			 */
			$noindex = true;

		} elseif( is_author() && ys_get_option( 'ys_archive_noindex_author' ) ){
			/**
			 * カテゴリーページのnoindex設定がされていればnoindex
			 */
			$noindex = true;

		} elseif( is_date() && ys_get_option( 'ys_archive_noindex_date' ) ){
			/**
			 * カテゴリーページのnoindex設定がされていればnoindex
			 */
			$noindex = true;

		} elseif( is_single() || is_page() ){
			if( '1' === ys_get_post_meta( 'ys_noindex' ) ){
				/**
				 * 投稿・固定ページでnoindex設定されていればnoindex
				 */
				$noindex = true;
			}
		}
		$noindex = apply_filters( 'ys_the_noindex', $noindex );
		/**
		 * noindex出力
		 */
		if( $noindex ){
			echo '<meta name="robots" content="noindex,follow">' . PHP_EOL;
		}
	}
}
add_action( 'wp_head', 'ys_the_noindex' );

/**
 * OGP metaタグ出力
 */
if( ! function_exists( 'ys_the_ogp') ) {
	function ys_the_ogp() {
		echo ys_get_the_ogp();
	}
}
add_action( 'wp_head', 'ys_the_ogp');

/**
 * Twitter Card metaタグ出力
 */
if( ! function_exists( 'ys_the_twitter_card') ) {
	function ys_the_twitter_card() {
		echo ys_get_the_twitter_card();
	}
}
add_action( 'wp_head', 'ys_the_twitter_card');

/**
 * google analyticsタグ出力
 */
if( ! function_exists( 'ys_the_google_anarytics' ) ) {
	function ys_the_google_anarytics(){
		/**
		 * 管理画面ログイン中はGAタグを出力しない
		 */
		if( ! ys_is_enable_google_analytics() ) {
			return;
		}
		/**
		 * トラッキング タイプ
		 */
		$ga_type = ys_get_option( 'ys_ga_tracking_type' );
		get_template_part( 'template-parts/google-analytics/' . $ga_type );
	}
}
add_action( 'wp_head', 'ys_the_google_anarytics', 99 );


/**
 * Google Analytics idの取得
 */
if( ! function_exists( 'ys_get_google_anarytics_tracking_id' ) ) {
	function ys_get_google_anarytics_tracking_id() {
		return apply_filters( 'ys_get_google_anarytics_tracking_id', trim( ys_get_option( 'ys_ga_tracking_id' ) ) );
	}
}

/**
 * ampページの存在タグ出力
 */
if( ! function_exists( 'ys_the_amphtml' ) ) {
	function ys_the_amphtml(){

		if( is_single() && ys_is_amp_enable() ){
			$permalink = add_query_arg( 'amp', '1', get_the_permalink() );
			echo '<link rel="amphtml" href="'. $permalink . '">' . PHP_EOL;
		}
	}
}
add_action( 'wp_head', 'ys_the_amphtml' );

/**
 * ユーザーカスタムHEAD出力
 */
if( ! function_exists( 'ys_the_uc_custom_head' ) ) {
	function ys_the_uc_custom_head() {
		get_template_part( 'user-custom-head' );
	}
}
add_action( 'wp_head', 'ys_the_uc_custom_head', 11 );