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
 * メタデスクリプション取得
 */
function ys_get_meta_description() {
	global $post;
	$length = ys_get_option( 'ys_option_meta_description_length' );
	$dscr   = '';
	/**
	 * TOPページの場合
	 */
	if ( ys_is_top_page() ) {
		$dscr = trim( ys_get_option( 'ys_wp_site_description' ) );
	} elseif ( is_category() && ! is_paged() ) {
		/**
		 * カテゴリー
		 */
		$dscr = category_description();
	} elseif ( is_tag() && ! is_paged() ) {
		/**
		 * タグ
		 */
		$dscr = tag_description();
	} elseif ( is_tax() ) {
		/**
		 * その他タクソノミー
		 */
		$taxonomy = get_query_var( 'taxonomy' );
		$term     = get_term_by( 'slug', get_query_var( 'term' ), $taxonomy );
		$dscr     = term_description( $term->term_id, $taxonomy );
	} elseif ( is_singular() ) {
		/**
		 * 投稿ページ
		 */
		if ( ! get_query_var( 'paged' ) ) {
			$dscr = $post->post_excerpt;
			if ( ! $dscr ) {
				$dscr = ys_get_the_custom_excerpt( '', $length, $post->ID );
			}
		}
	}
	if ( '' !== $dscr ) {
		$dscr = mb_substr( $dscr, 0, $length );
	}

	return apply_filters( 'ys_get_meta_description', wp_strip_all_tags( $dscr, true ) );
}

/**
 * TOPページのmeta description出力
 */
function ys_the_meta_description() {
	$html = '';
	$dscr = ys_get_meta_description();
	/**
	 * Metaタグの作成
	 */
	if ( '' !== $dscr && ys_is_enable_meta_description() ) {
		$html = '<meta name="description" content="' . $dscr . '" />' . PHP_EOL;
	}
	echo $html;
}

add_action( 'wp_head', 'ys_the_meta_description' );

/**
 * ピンバックURLの出力
 */
function ys_the_pingback_url() {
	if ( is_singular() && pings_open( get_queried_object() ) ) {
		echo '<link rel="pingback" href="' . get_bloginfo( 'pingback_url' ) . '" />' . PHP_EOL;
	}
}

add_action( 'wp_head', 'ys_the_pingback_url' );

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
	$noindex = false;
	if ( is_404() ) {
		/**
		 * 404ページをnoindex
		 */
		$noindex = true;
	} elseif ( is_search() ) {
		/**
		 * 検索結果をnoindex
		 */
		$noindex = true;
	} elseif ( is_category() && ys_get_option( 'ys_archive_noindex_category' ) ) {
		/**
		 * カテゴリーページのnoindex設定がされていればnoindex
		 */
		$noindex = true;
	} elseif ( is_tag() && ys_get_option( 'ys_archive_noindex_tag' ) ) {
		/**
		 * カテゴリーページのnoindex設定がされていればnoindex
		 */
		$noindex = true;
	} elseif ( is_author() && ys_get_option( 'ys_archive_noindex_author' ) ) {
		/**
		 * カテゴリーページのnoindex設定がされていればnoindex
		 */
		$noindex = true;

	} elseif ( is_date() && ys_get_option( 'ys_archive_noindex_date' ) ) {
		/**
		 * カテゴリーページのnoindex設定がされていればnoindex
		 */
		$noindex = true;
	} elseif ( is_single() || is_page() ) {
		if ( '1' === ys_get_post_meta( 'ys_noindex' ) ) {
			/**
			 * 投稿・固定ページでnoindex設定されていればnoindex
			 */
			$noindex = true;
		}
	}
	$noindex = apply_filters( 'ys_the_noindex', $noindex );
	/**
	 * Noindex出力
	 */
	if ( $noindex ) {
		echo '<meta name="robots" content="noindex,follow">' . PHP_EOL;
	}
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
 * Google Analyticsタグ出力
 */
function ys_the_google_anarytics() {
	/**
	 * 管理画面ログイン中はGAタグを出力しない
	 */
	if ( ! ys_is_enable_google_analytics() ) {
		return;
	}
	/**
	 * トラッキング タイプ
	 */
	$ga_type = ys_get_option( 'ys_ga_tracking_type' );
	get_template_part( 'template-parts/parts/ga', $ga_type );
}

add_action( 'wp_head', 'ys_the_google_anarytics', 99 );

/**
 * Google Analytics idの取得
 */
function ys_get_google_anarytics_tracking_id() {
	return apply_filters(
		'ys_get_google_anarytics_tracking_id',
		trim( ys_get_option( 'ys_ga_tracking_id' ) )
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
	get_template_part( 'user-custom-head' );
}

add_action( 'wp_head', 'ys_the_uc_custom_head', 11 );
