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

	if ( ys_get_option( 'ys_desabled_color_customizeser' ) ) {
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
