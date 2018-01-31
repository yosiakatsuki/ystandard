<?php
/**
 * <head>タグ取得
 */
if( ! function_exists( 'ys_get_the_head_tag' ) ) {
	function ys_get_the_head_tag() {
		$html = '<head prefix="og: http://ogp.me/ns# fb: http://ogp.me/ns/fb# blog: http://ogp.me/ns/blog#">';
		if( is_singular() ){
			$html = '<head prefix="og: http://ogp.me/ns# fb: http://ogp.me/ns/fb# article: http://ogp.me/ns/article#">';
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
			$style = sprintf( '<style type="text/css">%s</style>', $style );
		}
		echo $style;
	}
}

/**
 * TOPページのmeta description出力
 */
if( ! function_exists( 'ys_the_meta_description' ) ) {
	function ys_the_meta_description(){
		$dscr = trim( ys_get_option( 'ys_wp_site_description' ) );
		if( ys_is_toppage() && '' != $dscr ){
			echo '<meta name="description"  content="'. $dscr . '">' . PHP_EOL;
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
			/**
			 * 404ページをnoindex
			 */
			$noindexoutput = true;

		} elseif( is_search() ) {
			/**
			 * 検索結果をnoindex
			 */
			$noindexoutput = true;

		} elseif( is_category() && 1 == ys_get_option( 'ys_archive_noindex_category', 0 ) ) {
			/**
			 * カテゴリーページのnoindex設定がされていればnoindex
			 */
			$noindexoutput = true;

		} elseif( is_tag() && 1 == ys_get_option( 'ys_archive_noindex_tag', 1 ) ){
			/**
			 * カテゴリーページのnoindex設定がされていればnoindex
			 */
			$noindexoutput = true;

		} elseif( is_author() && 1 == ys_get_option( 'ys_archive_noindex_author', 1 ) ){
			/**
			 * カテゴリーページのnoindex設定がされていればnoindex
			 */
			$noindexoutput = true;

		} elseif( is_date() && 1 == ys_get_option( 'ys_archive_noindex_date', 1 ) ){
			/**
			 * カテゴリーページのnoindex設定がされていればnoindex
			 */
			$noindexoutput = true;

		} elseif( is_single() || is_page() ){
			if( '1' === ys_get_post_meta( 'ys_noindex' ) ){
				/**
				 * 投稿・固定ページでnoindex設定されていればnoindex
				 */
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

/**
 * OGP metaタグ作成
 */
if( ! function_exists( 'ys_get_the_ogp') ) {
	function ys_get_the_ogp(){
		if( ! ys_get_option( 'ys_ogp_enable' ) ) {
			return '';
		}
		$ogp = '';
		$param = ys_get_ogp_and_twitter_card_param();
		if( ! empty( $param['ogp_site_name'] ) ) {
			$ogp .= '<meta name="og:site_name" content="' . $param['ogp_site_name'] . '" />' . PHP_EOL;
		}
		if( ! empty( $param['ogp_locale'] ) ) {
			$ogp .= '<meta name="og:locale" content="' . $param['ogp_locale'] . '" />' . PHP_EOL;
		}
		if( ! empty( $param['ogp_app_id'] ) ) {
			$ogp .= '<meta name="og:app_id" content="' . $param['ogp_app_id'] . '" />' . PHP_EOL;
		}
		if( ! empty( $param['ogp_admins'] ) ) {
			$ogp .= '<meta name="og:admins" content="' . $param['ogp_admins'] . '" />' . PHP_EOL;
		}
		if( ! empty( $param['ogp_type'] ) ) {
			$ogp .= '<meta name="og:type" content="' . $param['ogp_type'] . '" />' . PHP_EOL;
		}
		if( ! empty( $param['title'] ) ) {
			$ogp .= '<meta name="og:title" content="' . $param['title'] . '" />' . PHP_EOL;
		}
		if( ! empty( $param['description'] ) ) {
			$ogp .= '<meta name="og:description" content="' . $param['description'] . '" />' . PHP_EOL;
		}
		if( ! empty( $param['image'] ) ) {
			$ogp .= '<meta name="og:image" content="' . $param['image'] . '" />' . PHP_EOL;
		}
		if( ! empty( $param['url'] ) ) {
			$ogp .= '<meta name="og:url" content="' . $param['url'] . '" />' . PHP_EOL;
		}
		return apply_filters( 'ys_get_the_ogp', $ogp );
	}
}
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
 * Twitter Card metaタグ作成
 */
if( ! function_exists( 'ys_get_the_twitter_card') ) {
	function ys_get_the_twitter_card(){

		if( ! ys_get_option( 'ys_twittercard_enable' ) ) {
			return '';
		}
		$twitter_card = '';
		$param = ys_get_ogp_and_twitter_card_param();
		if( ! empty( $param['twitter_card_type'] ) ) {
			$twitter_card .= '<meta name="twitter:card" content="' . $param['twitter_card_type'] . '" />' . PHP_EOL;
		}
		if( ! empty( $param['twitter_account'] ) ) {
			$twitter_card .= '<meta name="twitter:site" content="' . $param['twitter_account'] . '" />' . PHP_EOL;
		}
		if( ! empty( $param['title'] ) ) {
			$twitter_card .= '<meta name="twitter:title" content="' . $param['title'] . '" />' . PHP_EOL;
		}
		if( ! empty( $param['description'] ) ) {
			$twitter_card .= '<meta name="twitter:description" content="' . $param['description'] . '" />' . PHP_EOL;
		}
		if( ! empty( $param['image'] ) ) {
			$twitter_card .= '<meta name="twitter:image" content="' . $param['image'] . '" />' . PHP_EOL;
		}
		return apply_filters( 'ys_get_the_twitter_card', $twitter_card );
	}
}

/**
 * Twitter Card metaタグ出力
 */
if( ! function_exists( 'ys_the_twitter_card') ) {
	function ys_the_twitter_card() {
		echo ys_get_the_twitter_card();
	}
}
add_action( 'wp_head', 'ys_the_twitter_card');

if( ! function_exists( 'ys_get_ogp_and_twitter_card_param') ) {
	function ys_get_ogp_and_twitter_card_param() {
		$param = array(
			'title' => get_bloginfo('name'),
			'description' => get_bloginfo('description'),
			'image' => ys_get_option( 'ys_ogp_default_image' ),
			'url' => get_bloginfo('url'),
			'ogp_site_name' => get_bloginfo('name'),
			'ogp_locale' => 'ja_JP',
			'ogp_type' => 'website',
			'ogp_app_id' => ys_get_option( 'ys_ogp_fb_app_id' ),
			'ogp_admins' => ys_get_option( 'ys_ogp_fb_admins' ),
			'twitter_account' => ys_get_option( 'ys_twittercard_user' ),
			'twitter_card_type' => ys_get_option( 'ys_twittercard_type' ),
		);
		/**
		 * 投稿・固定ページ系
		 */
		if( is_singular() && ! ys_is_toppage() ) {
			$param['title'] = get_the_title();
			$param['description'] = ys_util_get_the_custom_excerpt('');
			$param['image'] = get_the_post_thumbnail_url();
			$param['url'] = get_the_permalink();
			$param['ogp_type'] = 'article';
		}
		/**
		 * アーカイブ系
		 */
		if( is_archive() && ! ys_is_toppage() ){
			$param['title'] = ys_get_the_archive_title( '' );
			$param['url'] = ys_get_the_archive_url();
		}
		/**
		 * フィルタ
		 */
		return apply_filters( 'ys_get_ogp_and_twitter_card_param', $param );
	}
}


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