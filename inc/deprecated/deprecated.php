<?php
/**
 * そのう消える予定の関数群
 *
 * @package ystandard
 * @author  yosiakatsuki
 * @license GPL-2.0+
 */

/**
 * 記事下ウィジェットを表示するか
 *
 * @deprecated ys_is_active_after_content_widgetを使う.
 */
function ys_is_active_entry_footer_widget() {
	YS_Utility::deprecated_comment( 'ys_is_active_entry_footer_widget', 'v3.0.0' );

	return ys_is_active_after_content_widget();
}

/**
 * テーマ内で使用する設定の取得
 *
 * @return array
 * @deprecated v3.0.0
 */
function ys_get_options() {
	YS_Utility::deprecated_comment( 'ys_get_options', 'v3.0.0' );

	return apply_filters( 'ys_get_options', array() );
}


/**
 * 投稿者画像取得
 *
 * @param boolean $user_id user id.
 * @param integer $size    image size.
 * @param array   $class   class.
 *
 * @return string
 * @deprecated v3.1.0
 */
function ys_get_author_avatar( $user_id = false, $size = 96, $class = array() ) {
	YS_Utility::deprecated_comment( 'ys_get_author_avatar', 'v3.1.0' );

	if ( ! get_option( 'show_avatars', true ) ) {
		return '';
	}
	$user_id   = ys_check_user_id( $user_id );
	$author_id = $user_id;
	if ( ! $user_id ) {
		$author_id = get_the_author_meta( 'ID' );
	}
	$alt         = esc_attr( ys_get_author_display_name() );
	$user_avatar = get_avatar( $author_id, $size, '', $alt, array( 'class' => 'author__img' ) );
	/**
	 * カスタムアバター取得
	 */
	$custom_avatar = apply_filters(
		'ys_get_author_custom_avatar_src',
		get_user_meta( $author_id, 'ys_custom_avatar', true ),
		$author_id,
		$size,
		$class
	);
	/**
	 * Imgタグ作成
	 */
	$img       = '';
	$img_class = array( 'author__img' );
	if ( ! empty( $class ) ) {
		$img_class = array_merge( $img_class, $class );
	}
	if ( '' !== $custom_avatar ) {
		$img = sprintf(
			'<img class="%s" src="%s" alt="%s" %s />',
			implode( ' ', $img_class ),
			$custom_avatar,
			$alt,
			image_hwstring( $size, $size )
		);
	}
	/**
	 * カスタムアバターが無ければ通常アバター
	 */
	if ( '' === $img ) {
		$img = $user_avatar;
	}
	$img = ys_amp_get_amp_image_tag( $img );

	return apply_filters( 'ys_get_author_avatar', $img, $author_id, $size );
}

/**
 * 投稿者画像出力
 *
 * @param boolean $user_id user id.
 * @param integer $size    image size.
 * @param array   $class   class.
 *
 * @deprecated v3.1.0
 */
function ys_the_author_avatar( $user_id = false, $size = 96, $class = array() ) {
	YS_Utility::deprecated_comment( 'ys_the_author_avatar', 'v3.1.0' );
	echo ys_get_author_avatar( $user_id, $size, $class );
}

/**
 * Twitter用JavaScript URL取得
 *
 * @return string
 * @deprecated v3.6.0
 */
function ys_get_twitter_widgets_js() {
	YS_Utility::deprecated_comment( 'ys_get_twitter_widgets_js', 'v3.6.0' );

	return YS_Utility::get_twitter_widgets_js();
}

/**
 * Facebook用JavaScript URL取得
 *
 * @return string
 * @deprecated v3.6.0
 */
function ys_get_facebook_sdk_js() {
	YS_Utility::deprecated_comment( 'ys_get_facebook_sdk_js', 'v3.6.0' );

	return YS_Utility::get_facebook_sdk_js();
}

/**
 * 読み込むCSSファイルのURLを取得する
 *
 * @return string
 * @deprecated v3.6.0
 */
function ys_get_enqueue_css_file_uri() {
	YS_Utility::deprecated_comment( 'ys_get_enqueue_css_file_uri', 'v3.6.0' );

	return YS_Scripts::get_enqueue_css_file_uri();
}

/**
 * 読み込むCSSファイルのパスを取得する
 *
 * @return string
 * @deprecated v3.6.0
 */
function ys_get_enqueue_css_file_path() {
	YS_Utility::deprecated_comment( 'ys_get_enqueue_css_file_path', 'v3.6.0' );

	return YS_Scripts::get_enqueue_css_file_path();
}

/**
 * 読み込むCSSファイルの名前を取得する
 *
 * @return string
 * @deprecated v3.6.0
 */
function ys_get_enqueue_css_file_name() {
	YS_Utility::deprecated_comment( 'ys_get_enqueue_css_file_name', 'v3.6.0' );

	return YS_Scripts::get_enqueue_css_file_name();
}

/**
 * いろいろ削除
 *
 * @deprecated v3.6.0
 */
function ys_remove_action() {
	YS_Utility::deprecated_comment( 'ys_remove_action', 'v3.6.0' );
}


/**
 * 絵文字無効化
 *
 * @deprecated v3.6.0
 */
function ys_remove_emoji() {
	YS_Utility::deprecated_comment( 'ys_remove_emoji', 'v3.6.0' );
}

/**
 * Embed無効化
 *
 * @deprecated v3.6.0
 */
function ys_remove_oembed() {
	YS_Utility::deprecated_comment( 'ys_remove_oembed', 'v3.6.0' );
}

/**
 * タクソノミー説明の処理カスタマイズ
 *
 * @deprecated v3.6.0
 */
function ys_tax_dscr_allowed_option() {
	YS_Utility::deprecated_comment( 'ys_tax_dscr_allowed_option', 'v3.6.0' );
}

/**
 * ファイル内容の取得
 *
 * @param string $file ファイルパス.
 *
 * @return string
 * @deprecated v3.6.0
 */
function ys_file_get_contents( $file ) {
	YS_Utility::deprecated_comment( 'ys_file_get_contents', 'v3.6.0' );

	return YS_Utility::file_get_contents( $file );
}


/**
 * CSSのセット
 *
 * @param string $handle Handle.
 * @param string $src    CSSのURL.
 * @param bool   $inline インライン読み込みするか.
 * @param array  $deps   deps.
 * @param bool   $ver    クエリストリング.
 * @param string $media  media.
 * @param bool   $minify Minifyするか.
 *
 * @return void
 * @deprecated v3.6.0
 */
function ys_enqueue_css( $handle, $src, $inline = true, $deps = array(), $ver = false, $media = 'all', $minify = true ) {
	YS_Utility::deprecated_comment( 'ys_enqueue_css', 'v3.6.0' );

	$scripts = ys_scripts();
	$scripts->set_enqueue_style( $handle, $src, $inline, $deps, $ver, $media, $minify );
}

/**
 * インラインCSSのセット
 *
 * @param string $style  CSS.
 * @param bool   $minify Minifyするか.
 *
 * @return void
 * @deprecated v3.6.0
 */
function ys_enqueue_inline_css( $style, $minify = true ) {
	YS_Utility::deprecated_comment( 'ys_enqueue_inline_css', 'v3.6.0' );

	$scripts = ys_scripts();
	$scripts->set_inline_style( $style, $minify );
}


/**
 * カスタマイザー設定のCSSにメディアクエリを追加
 *
 * @param string $css        Styles.
 * @param string $breakpoint Breakpoint.
 * @param string $type       Breakpoint type(min or max).
 *
 * @return string
 * @deprecated v3.6.0
 */
function ys_customizer_add_media_query( $css, $breakpoint, $type = 'min' ) {
	YS_Utility::deprecated_comment( 'ys_customizer_add_media_query', 'v3.6.0' );

	/**
	 * ブレークポイント
	 */
	$breakpoints = array(
		'md' => 600,
		'lg' => 1025,
	);
	/**
	 * 切り替え位置取得
	 */
	if ( isset( $breakpoints[ $breakpoint ] ) ) {
		$breakpoint = $breakpoints[ $breakpoint ];
		if ( 'max' === $type ) {
			$breakpoint = $breakpoint - 0.1;
		}
	}
	/**
	 * 以上・以下判断
	 */
	if ( 'min' !== $type && 'max' !== $type ) {
		return $css;
	}

	return sprintf(
		'@media screen and (%s-width: %spx) {%s}',
		$type,
		$breakpoint,
		$css
	);
}

/**
 * テーマカスタマイザーでの色指定 CSS取得
 *
 * @return string
 * @deprecated v3.6.0
 */
function ys_get_customizer_inline_css_color() {
	YS_Utility::deprecated_comment( 'ys_get_customizer_inline_css_color', 'v3.6.0' );

	if ( ys_get_option_by_bool( 'ys_desabled_color_customizeser', false ) ) {
		return '';
	}
	$inline_css = new YS_Inline_Css();
	/**
	 * 設定取得
	 */
	$css = '';
	/**
	 * CSS
	 */
	$css .= $inline_css->get_site_css();
	$css .= $inline_css->get_header_css();
	$css .= $inline_css->get_nav_css();
	$css .= $inline_css->get_footer_css();

	return apply_filters( 'ys_get_customizer_inline_css_color', $css );
}


/**
 * テーマカスタマイザーでのCSS設定 カスタムヘッダー
 *
 * @return string
 * @deprecated v3.6.0
 */
function ys_get_customizer_inline_css_custom_header() {
	YS_Utility::deprecated_comment( 'ys_get_customizer_inline_css_custom_header', 'v3.6.0' );
	$inline_css = new YS_Inline_Css();

	return $inline_css->get_custom_header_css();
}


/**
 * モバイルフッター設定によって追加するCSS
 *
 * @deprecated v3.6.0
 */
function ys_get_inline_css_mobile_css() {
	YS_Utility::deprecated_comment( 'ys_get_inline_css_mobile_css', 'v3.6.0' );

	$inline_css = new YS_Inline_Css();

	return $inline_css->get_mobile_footer_css();
}


/**
 * セレクタとプロパティをくっつけてCSS作る
 *
 * @param array $selectors  セレクタ.
 * @param array $properties プロパティ.
 *
 * @return string
 * @deprecated v3.6.0
 */
function ys_customizer_create_inline_css( $selectors, $properties ) {
	YS_Utility::deprecated_comment( 'ys_customizer_create_inline_css', 'v3.6.0' );
	$property = '';
	foreach ( $properties as $key => $value ) {
		$property .= $key . ':' . $value . ';';
	}

	return implode( ',', $selectors ) . '{' . $property . '}';
}

/**
 * テーマカスタマイザー/設定関連で変更する CSS取得
 *
 * @return string
 * @deprecated v3.6.0
 */
function ys_get_customizer_inline_css() {
	YS_Utility::deprecated_comment( 'ys_get_customizer_inline_css', 'v3.6.0' );

	$css = '';
	/**
	 * カスタマイザー色指定
	 */
	$css .= ys_get_customizer_inline_css_color();

	/**
	 * カスタムヘッダー
	 */
	$css .= ys_get_customizer_inline_css_custom_header();

	/**
	 * モバイルフッター
	 */
	$css .= ys_get_inline_css_mobile_css();

	return apply_filters( 'ys_get_customizer_inline_css', $css );
}


/**
 * カスタマイザー用画像アセットURL取得
 *
 * @deprecated v3.7.0
 */
function ys_get_template_customizer_assets_img_dir_uri() {
	YS_Utility::deprecated_comment( 'ys_get_template_customizer_assets_img_dir_uri', 'v3.7.0' );

	return get_template_directory_uri() . '/assets/images/customizer';
}

/**
 * チェックボックスのサニタイズ
 *
 * @param mixed $value 値.
 *
 * @return bool
 * @deprecated v3.7.0
 */
function ys_sanitize_bool( $value ) {
	YS_Utility::deprecated_comment( 'ys_get_template_customizer_assets_img_dir_uri', 'v3.7.0' );

	return ys_to_bool( $value );
}


/**
 * パンくずリストデータ取得
 *
 * @return array
 */
function ys_get_breadcrumbs() {
	YS_Utility::deprecated_comment( 'ys_get_breadcrumbs', 'v3.11.0' );

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
		if ( 'page' === $show_on_front && $page_for_posts && ys_get_option_by_bool( 'ys_show_page_for_posts_on_breadcrumbs', true ) ) {
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


/**
 * 設定デフォルト値取得
 *
 * @param string $name    設定名.
 * @param mixed  $default デフォルト値.
 *
 * @return mixed
 * @deprecated
 */
function ys_get_option_default( $name, $default = false ) {
	YS_Utility::deprecated_comment( 'ys_get_breadcrumbs', 'v3.13.0' );

	return YS_Option::get_default_option( $name, $default );
}

/**
 * 設定のデフォルト値リストを取得
 *
 * @return array
 * @deprecated
 */
function ys_get_option_defaults() {
	YS_Utility::deprecated_comment( 'ys_get_breadcrumbs', 'v3.13.0' );

	return array(
		'ys_logo_hidden'                            => 0, // ロゴを出力しない.
		'ys_logo_width_pc'                          => 0, // ロゴの幅指定.
		'ys_logo_width_sp'                          => 0, // ロゴの幅指定.
		'ys_wp_hidden_blogdescription'              => 0, // キャッチフレーズを出力しない.
		'ys_wp_site_description'                    => '', // TOPページのmeta description.
		'ys_info_bar_text'                          => '', // お知らせバーテキスト.
		'ys_info_bar_url'                           => '', // お知らせバーリンクURL.
		'ys_info_bar_external'                      => false, // お知らせバーリンクを.
		'ys_info_bar_text_color'                    => '#222222', // お知らせバー文字色.
		'ys_info_bar_bg_color'                      => '#f1f1f3', // お知らせバー背景色.
		'ys_info_bar_text_bold'                     => true, // お知らせバーを太字にする.
		'ys_design_font_type'                       => 'yugo', // フォントタイプ.
		'ys_design_header_type'                     => 'row1', // ヘッダータイプ.
		'ys_color_header_bg'                        => '#ffffff',
		'ys_color_header_font'                      => '#222222',
		'ys_color_header_dscr_font'                 => '#757575',
		'ys_header_fixed'                           => 0, // 固定ヘッダー.
		'ys_header_fixed_height_pc'                 => 0, // ヘッダー高さ(PC).
		'ys_header_fixed_height_tablet'             => 0, // ヘッダー高さ(PC).
		'ys_header_fixed_height_mobile'             => 0, // ヘッダー高さ(PC).
		'ys_breadcrumbs_position'                   => 'header', // パンくずリストの表示位置設定.
		'ys_show_page_for_posts_on_breadcrumbs'     => 1, // パンくずリストの「投稿ページ」表示.
		'ys_color_site_bg'                          => '#ffffff',
		'ys_color_nav_bg_sp'                        => '#000000',
		'ys_color_nav_font_sp'                      => '#ffffff',
		'ys_color_nav_btn_sp_open'                  => '#222222',
		'ys_color_nav_btn_sp'                       => '#ffffff',
		'ys_color_footer_font'                      => '#ffffff',
		'ys-color-palette-ys-blue'                  => '#82B9E3',
		'ys-color-palette-ys-light-blue'            => '#77C7E4',
		'ys-color-palette-ys-red'                   => '#D53939',
		'ys-color-palette-ys-pink'                  => '#E28DA9',
		'ys-color-palette-ys-green'                 => '#92C892',
		'ys-color-palette-ys-yellow'                => '#F5EC84',
		'ys-color-palette-ys-orange'                => '#EB962D',
		'ys-color-palette-ys-purple'                => '#B67AC2',
		'ys-color-palette-ys-gray'                  => '#757575',
		'ys-color-palette-ys-light-gray'            => '#F1F1F3',
		'ys-color-palette-ys-black'                 => '#000000',
		'ys-color-palette-ys-white'                 => '#ffffff',
		'ys-color-palette-ys-user-1'                => '#ffffff',
		'ys-color-palette-ys-user-2'                => '#ffffff',
		'ys-color-palette-ys-user-3'                => '#ffffff',
		'ys_desabled_color_customizeser'            => 0, // テーマカスタマイザーの色設定を無効にする.
		'ys_wp_header_media_shortcode'              => '', // ヘッダーメディア用ショートコード.
		'ys_wp_header_media_full'                   => 0, // 画像・動画の全面表示.
		'ys_wp_header_media_full_type'              => 'dark', // 画像・動画の全面表示 表示タイプ.
		'ys_wp_header_media_full_opacity'           => 50, // 画像・動画の全面表示 ヘッダー不透明度.
		'ys_wp_header_media_all_page'               => 0, // カスタムヘッダーの全ページ表示.
		'ys_title_separate'                         => '', // タイトルの区切り文字.
		'ys_copyright_year'                         => '', // 発行年.
		'ys_option_excerpt_length'                  => 110, // 抜粋文字数.
		'ys_show_sidebar_mobile'                    => 0, // モバイル表示でサイドバーを出力しない.
		'ys_show_search_form_on_slide_menu'         => 0, // スライドメニューに検索フォームを出力する.
		'ys_enqueue_icon_font_type'                 => 'js', // アイコンフォント（Font Awesome）読み込み方式.
		'ys_enqueue_icon_font_kit_url'              => '', // Font Awesome Kit URL.
		'ys_post_layout'                            => '2col', // 表示レウアウト.
		'ys_show_post_thumbnail'                    => 1, // 個別ページでアイキャッチ画像を表示する.
		'ys_show_post_publish_date'                 => 1, // 個別ページで投稿日・更新日を表示する.
		'ys_show_post_category'                     => 1, // カテゴリー・タグ情報を表示する.
		'ys_show_post_follow_box'                   => 1, // ブログフォローボックスを表示する.
		'ys_show_post_author'                       => 1, // 著者情報を表示する.
		'ys_show_post_related'                      => 1, // 関連記事を出力する.
		'ys_show_post_paging'                       => 1, // 次の記事・前の記事を表示する.
		'ys_show_post_before_content_widget'        => 0, // 記事上ウィジェットを出力する.
		'ys_post_before_content_widget_priority'    => 10, // 記事上ウィジェットの優先順位.
		'ys_show_post_after_content_widget'         => 0, // 記事下ウィジェットを出力する.
		'ys_post_after_content_widget_priority'     => 10, // 記事下ウィジェットの優先順位.
		'ys_page_layout'                            => '2col', // 表示レウアウト.
		'ys_show_page_thumbnail'                    => 1, // 個別ページでアイキャッチ画像を表示する.
		'ys_show_page_publish_date'                 => 1, // 個別ページで投稿日時を表示する.
		'ys_show_page_follow_box'                   => 1, // ブログフォローボックスを表示する.
		'ys_show_page_author'                       => 1, // 著者情報を表示する.
		'ys_show_page_before_content_widget'        => 0, // 記事上ウィジェットを出力する.
		'ys_page_before_content_widget_priority'    => 10, // 記事上ウィジェットの優先順位.
		'ys_show_page_after_content_widget'         => 0, // 記事下ウィジェットを出力する.
		'ys_page_after_content_widget_priority'     => 10, // 記事下ウィジェットの優先順位.
		'ys_archive_layout'                         => '2col', // 表示レウアウト.
		'ys_archive_type'                           => 'list', // 一覧表示タイプ.
		'ys_show_archive_publish_date'              => 1, // 投稿日を表示する.
		'ys_show_archive_author'                    => 1, // 著者情報を表示する.
		'ys_design_one_col_thumbnail_type'          => 'normal', // アイキャッチ画像表示タイプ.
		'ys_design_one_col_content_type'            => 'normal', // コンテンツタイプ normal,wide.
		'ys_front_page_layout'                      => '1col', // 表示レイアウト.
		'ys_ogp_enable'                             => 1, // OGPメタタグを出力する.
		'ys_ogp_fb_app_id'                          => '', // Facebook app id.
		'ys_ogp_default_image'                      => '', // OGPデフォルト画像.
		'ys_twittercard_enable'                     => 1, // Twitterカードを出力する.
		'ys_twittercard_user'                       => '', // // Twitterカードのユーザー名.
		'ys_twittercard_type'                       => 'summary_large_image', // Twitterカード タイプ.
		'ys_sns_share_button_twitter'               => 1, // Twitter.
		'ys_sns_share_button_facebook'              => 1, // Facebook.
		'ys_sns_share_button_hatenabookmark'        => 1, // はてブ.
		'ys_sns_share_button_pocket'                => 1, // Pocket.
		'ys_sns_share_button_line'                  => 1, // LINE.
		'ys_sns_share_button_feedly'                => 1, // Feedly.
		'ys_sns_share_button_rss'                   => 1, // RSS.
		'ys_sns_share_col_pc'                       => 6, // PCでの列数.
		'ys_sns_share_col_tablet'                   => 3, // タブレットでの列数.
		'ys_sns_share_col_sp'                       => 3, // スマホでの列数.
		'ys_sns_share_tweet_via_account'            => '', // Twitter via アカウント.
		'ys_sns_share_tweet_related_account'        => '', // Twitter related アカウント.
		'ys_subscribe_url_twitter'                  => '', // Twitterフォローリンク.
		'ys_subscribe_url_facebook'                 => '', // Facebookページフォローリンク.
		'ys_subscribe_url_feedly'                   => '', // Feedlyフォローリンク.
		'ys_follow_url_twitter'                     => '', // TwitterフォローURL.
		'ys_follow_url_facebook'                    => '', // facebookフォローURL.
		'ys_follow_url_instagram'                   => '', // instagramフォローURL.
		'ys_follow_url_tumblr'                      => '', // tumblrフォローURL.
		'ys_follow_url_youtube'                     => '', // YouTubeフォローURL.
		'ys_follow_url_github'                      => '', // GitHubフォローURL.
		'ys_follow_url_pinterest'                   => '', // PinterestフォローURL.
		'ys_follow_url_linkedin'                    => '', // linkedinフォローURL.
		'ys_load_script_twitter'                    => 0, // Twitter埋め込み用js読み込み.
		'ys_load_script_facebook'                   => 0, // Facebook埋め込み用js読み込み.
		'ys_option_create_meta_description'         => 1, // メタデスクリプションを自動生成する.
		'ys_option_meta_description_length'         => 80, // メタデスクリプションに使用する文字数.
		'ys_archive_noindex_category'               => 0,  // カテゴリー一覧をnoindexにする.
		'ys_archive_noindex_tag'                    => 1,  // タグ一覧をnoindexにする.
		'ys_archive_noindex_author'                 => 1,  // 投稿者一覧をnoindexにする.
		'ys_archive_noindex_date'                   => 1,  // 日別一覧をnoindexにする.
		'ys_ga_tracking_id'                         => '', // Google Analytics トラッキングID.
		'ys_ga_tracking_type'                       => 'gtag', // Google Analytics トラッキングコードタイプ.
		'ys_ga_exclude_logged_in_user'              => 0, // ログイン中はアクセス数をカウントしない.
		'ys_option_structured_data_publisher_image' => '', // パブリッシャー画像.
		'ys_option_structured_data_publisher_name'  => '', // パブリッシャー名.
		'ys_query_cache_ranking'                    => 'none', // 「[ys]人気ランキングウィジェット」の結果キャッシュ.
		'ys_query_cache_recent_posts'               => 'none', // 「[ys]新着記事一覧」の結果キャッシュ.
		'ys_option_disable_wp_emoji'                => 1, // 絵文字を出力しない.
		'ys_option_disable_wp_oembed'               => 1, // oembedを出力しない.
		'ys_option_optimize_load_css'               => 0, // CSSの読み込みを最適化する.
		'ys_option_optimize_load_js'                => 0, // JavaScriptの読み込みを非同期化する.
		'ys_load_jquery_in_footer'                  => 0, // jQueryをフッターで読み込む.
		'ys_load_cdn_jquery_url'                    => '', // CDNにホストされているjQueryを読み込む（URLを設定）.
		'ys_not_load_jquery'                        => 0, // jQueryを読み込まない.
		'ys_advertisement_ads_label'                => 'スポンサーリンク', // 広告ラベル.
		'ys_advertisement_before_title'             => '', // 広告　タイトル上.
		'ys_advertisement_after_title'              => '', // 広告　タイトル下.
		'ys_advertisement_before_content'           => '', // 記事本文上（旧 タイトル下）.
		'ys_advertisement_replace_more'             => '', // 広告　moreタグ置換.
		'ys_advertisement_under_content_left'       => '', // 広告　記事下　左.
		'ys_advertisement_under_content_right'      => '', // 広告　記事下　右.
		'ys_advertisement_before_title_sp'          => '', // 広告　タイトル上 SP.
		'ys_advertisement_after_title_sp'           => '', // 広告　タイトル下 SP.
		'ys_advertisement_before_content_sp'        => '', // 記事本文上 SP（旧 タイトル下 SP）.
		'ys_advertisement_replace_more_sp'          => '', // 広告　moreタグ置換 SP.
		'ys_advertisement_under_content_sp'         => '', // 広告　記事下 SP.
		'ys_advertisement_infeed_pc'                => '', // インフィード広告 PC.
		'ys_advertisement_infeed_pc_step'           => 3, // インフィード広告 広告を表示する間隔 PC.
		'ys_advertisement_infeed_pc_limit'          => 3, // インフィード広告 表示する広告の最大数 PC.
		'ys_advertisement_infeed_sp'                => '', // インフィード広告 SP.
		'ys_advertisement_infeed_sp_step'           => 3, // インフィード広告 広告を表示する間隔 SP.
		'ys_advertisement_infeed_sp_limit'          => 3, // インフィード広告 表示する広告の最大数 SP.
		'ys_amp_enable'                             => 0, // AMPページを有効化するか.
		'ys_amp_enable_amp_plugin_integration'      => 0, // AMPプラグイン連携を有効化するか.
		'ys_ga_tracking_id_amp'                     => '', // AMPのGoogle Analyticsトラッキングコード.
		'ys_show_amp_before_content_widget'         => 0, // 記事上ウィジェットを出力する.
		'ys_amp_before_content_widget_priority'     => 10, // 記事上ウィジェットの優先順位.
		'ys_show_amp_after_content_widget'          => 0, // 記事下ウィジェットを出力する.
		'ys_amp_after_content_widget_priority'      => 10, // 記事下ウィジェットの優先順位.
		'ys_amp_advertisement_before_title'         => '', // 広告　タイトル上 SP.
		'ys_amp_advertisement_after_title'          => '', // 広告　タイトル下 SP.
		'ys_amp_advertisement_before_content'       => '', // 広告　記事本文上 SP（旧 タイトル下）.
		'ys_amp_advertisement_replace_more'         => '', // 広告　moreタグ置換.
		'ys_amp_advertisement_under_content'        => '', // 広告　記事下.
		'ys_amp_thumbnail_type'                     => 'full', // アイキャッチ画像表示タイプ.
		'ys_admin_enable_block_editor_style'        => 1, // Gutenberg用CSSを追加する.
		'ys_admin_enable_tiny_mce_style'            => 0, // ビジュアルエディタ用CSSを追加する.

	);
}

/**
 * 色設定の定義
 *
 * @param bool $all ユーザー定義追加.
 *
 * @return array
 * @deprecated
 */
function ys_get_editor_color_palette( $all = true ) {
	return YS_Color::get_color_palette( $all );
}
