<?php
/**
 * <head>タグ周りの関数
 *
 * @package ystandard
 * @author  yosiakatsuki
 * @license GPL-2.0+
 */

/**
 * <html>タグにつける属性
 */
function ys_the_html_attr() {
	$attr = array();
	if ( ys_is_amp() ) {
		$attr[] = 'amp';
	}
	$attr[] = get_language_attributes();
	$attr   = apply_filters( 'ys_the_html_attr', $attr );

	echo implode( ' ', $attr );
}

/**
 * <head>タグにつける属性取得
 */
function ys_the_head_attr() {
	$attr = array();
	if ( ! ys_is_amp() ) {
		if ( is_singular() ) {
			$attr[] = 'prefix="og: http://ogp.me/ns# fb: http://ogp.me/ns/fb# article: http://ogp.me/ns/article#"';
		} else {
			$attr[] = 'prefix="og: http://ogp.me/ns# fb: http://ogp.me/ns/fb# blog: http://ogp.me/ns/blog#"';
		}
	}
	$attr = apply_filters( 'ys_get_head_attr', $attr );

	echo implode( ' ', $attr );
}


/**
 * Preload
 */
function ys_the_preload() {
	/**
	 * Preloadタグを追加するリソースのセット
	 */
	$preload = apply_filters( 'ys_the_preload_list', array() );
	/**
	 * Preloadタグ展開
	 */
	foreach ( $preload as $key => $value ) {
		printf(
			'<link id="%s" rel="preload" as="%s" type="%s" href="%s" crossorigin />' . PHP_EOL,
			$key,
			$value['as'],
			$value['type'],
			$value['url']
		);
	}
}

add_action( 'wp_head', 'ys_the_preload', 2 );


/**
 * Prefetchタグを追加するリソースの登録（一覧作成用）
 *
 * @param array  $list 一覧作成用配列.
 * @param string $id   識別子.
 * @param string $url  URL.
 * @param string $as   as.
 * @param string $type type.
 *
 * @return array
 */
function ys_set_preload_item( $list, $id, $url, $as, $type ) {
	$list[ $id ] = array(
		'url'  => $url,
		'as'   => $as,
		'type' => $type,
	);

	return $list;
}


if ( ! function_exists( 'ys_the_apple_touch_icon' ) ) {
	/**
	 * Apple touch icon
	 */
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

if ( ! function_exists( 'ys_get_apple_touch_icon_url' ) ) {
	/**
	 * Apple touch icon用URLを取得
	 *
	 * @param integer $size    サイズ.
	 * @param string  $url     ロゴURL.
	 * @param integer $blog_id ブログID.
	 *
	 * @return string
	 */
	function ys_get_apple_touch_icon_url( $size = 512, $url = '', $blog_id = 0 ) {

		if ( is_multisite() && get_current_blog_id() !== (int) $blog_id ) {
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

if ( ! function_exists( 'ys_site_icon_meta_tags' ) ) {
	/**
	 * サイトアイコン
	 *
	 * @param mixed $meta_tags meta tag.
	 *
	 * @return array
	 */
	function ys_site_icon_meta_tags( $meta_tags ) {
		$meta_tags = array(
			sprintf( '<link rel="icon" href="%s" sizes="32x32" />', esc_url( get_site_icon_url( 32 ) ) ),
			sprintf( '<link rel="icon" href="%s" sizes="192x192" />', esc_url( get_site_icon_url( 192 ) ) ),
		);

		return $meta_tags;
	}
}
add_filter( 'site_icon_meta_tags', 'ys_site_icon_meta_tags' );

/**
 * Canonicalタグ出力
 */
function ys_the_canonical_tag() {

	$canonical = ys_get_the_canonical_url();
	if ( '' !== $canonical ) {
		printf( '<link rel="canonical" href="%s">' . PHP_EOL, $canonical );
	}
}

add_action( 'wp_head', 'ys_the_canonical_tag' );

/**
 * Canonical url 取得
 *
 * @return string
 */
function ys_get_the_canonical_url() {
	$canonical = '';
	if ( is_home() || is_front_page() ) {
		$canonical = home_url();

	} elseif ( is_category() ) {
		$canonical = get_category_link( get_query_var( 'cat' ) );

	} elseif ( is_tag() ) {
		$tag       = get_term_by( 'slug', urldecode( get_query_var( 'tag' ) ), 'post_tag' );
		$canonical = get_tag_link( $tag->term_id );

	} elseif ( is_search() ) {
		$canonical = get_search_link();

	} elseif ( is_page() || is_single() ) {
		$canonical = get_permalink();

	}

	return apply_filters( 'ys_get_the_canonical_url', $canonical );
}


if ( ! function_exists( 'ys_the_rel_link' ) ) {
	/**
	 * Next,Prevタグ出力
	 */
	function ys_the_rel_link() {
		if ( is_single() || is_page() ) {
			/**
			 * 固定ページ・投稿ページ
			 */
			global $post, $page;
			$pagecnt = substr_count( $post->post_content, '<!--nextpage-->' ) + 1;

			if ( $pagecnt > 1 ) {
				/**
				 * Prev
				 */
				if ( $page > 1 ) {
					printf( '<link rel="prev" href="%s" />' . PHP_EOL, ys_get_the_link_page( $page - 1 ) );
				}
				/**
				 * Next
				 */
				if ( $page < $pagecnt ) {
					$page = 0 === $page ? 1 : $page;
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
			$current = get_query_var( 'paged' ) ? (int) get_query_var( 'paged' ) : 1;
			if ( $current > 1 ) {
				printf( '<link rel="prev" href="%s" />' . PHP_EOL, get_pagenum_link( $current - 1 ) );
			}
			if ( $current < $total ) {
				printf( '<link rel="next" href="%s" />' . PHP_EOL, get_pagenum_link( $current + 1 ) );
			}
		}
	}
}
add_action( 'wp_head', 'ys_the_rel_link' );

if ( ! function_exists( 'ys_get_the_link_page' ) ) {
	/**
	 * Prev,Next用URL取得
	 *
	 * @param int $i ページ番号.
	 *
	 * @return string
	 */
	function ys_get_the_link_page( $i ) {
		global $wp_rewrite;
		$post = get_post();
		if ( 1 === $i ) {
			$url = get_permalink();
		} else {
			if ( '' === get_option( 'permalink_structure' ) || ys_in_array( $post->post_status, array( 'draft', 'pending' ) ) ) {
				$url = add_query_arg( 'page', $i, get_permalink() );
			} elseif ( 'page' === get_option( 'show_on_front' ) && get_option( 'page_on_front' ) === $post->ID ) {
				$url = trailingslashit( get_permalink() ) . user_trailingslashit( "$wp_rewrite->pagination_base/" . $i, 'single_paged' );
			} else {
				$url = trailingslashit( get_permalink() ) . user_trailingslashit( $i, 'single_paged' );
			}
		}

		return $url;
	}
}

/**
 * Noindex
 */
function ys_the_noindex() {

}

add_action( 'wp_head', 'ys_the_noindex' );

/**
 * OGP metaタグ出力
 */
function ys_the_ogp() {
	echo ys_get_the_ogp();
}

add_action( 'wp_head', 'ys_the_ogp' );

/**
 * Twitter Card metaタグ出力
 */
function ys_the_twitter_card() {
	echo ys_get_the_twitter_card();
}

add_action( 'wp_head', 'ys_the_twitter_card' );

/**
 * Google Analytics idの取得
 */
function ys_get_google_anarytics_tracking_id() {
	return apply_filters(
		'ys_get_google_anarytics_tracking_id',
		trim( ys_get_option( 'ys_ga_tracking_id', '' ) )
	);
}

if ( ! function_exists( 'ys_the_amphtml' ) ) {
	/**
	 * AMPページの存在タグ出力
	 */
	function ys_the_amphtml() {

		if ( is_single() && ys_is_amp_enable() ) {
			$permalink = add_query_arg( 'amp', '1', get_the_permalink() );
			echo '<link rel="amphtml" href="' . $permalink . '">' . PHP_EOL;
		}
	}
}
add_action( 'wp_head', 'ys_the_amphtml' );

/**
 * ユーザーカスタムHEAD出力
 */
function ys_the_uc_custom_head() {
	ys_get_template_part( 'user-custom-head' );
}

add_action( 'wp_head', 'ys_the_uc_custom_head', 11 );
