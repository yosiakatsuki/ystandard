<?php


/**
 * TOPページ判断（HOMEの1ページ目 or front-page）
 *
 * @return bool
 */
function ys_is_top_page() {
	return \ystandard\Template::is_top_page();
}

/**
 * ユーザーエージェントのチェック
 *
 * @param array $ua 対象ユーザーエージェントのリスト.
 *
 * @return boolean
 */
function ys_check_user_agent( $ua ) {
	if ( ! isset( $_SERVER['HTTP_USER_AGENT'] ) ) {
		return false;
	}
	$pattern = '/' . implode( '|', $ua ) . '/i';

	return preg_match( $pattern, $_SERVER['HTTP_USER_AGENT'] );
}

/**
 * モバイル判定
 *
 * @return bool
 */
function ys_is_mobile() {

	$ua = [
		'^(?!.*iPad).*iPhone',
		'iPod',
		'Android.*Mobile',
		'Mobile.*Firefox',
		'Windows.*Phone',
		'blackberry',
		'dream',
		'CUPCAKE',
		'webOS',
		'incognito',
		'webmate',
	];

	$ua = apply_filters( 'ys_is_mobile_ua_list', $ua );

	return ys_check_user_agent( $ua );
}

/**
 * IE判定
 *
 * @return bool
 */
function ys_is_ie() {
	$ua = [
		'Trident',
		'MSIE',
	];
	$ua = apply_filters( 'ys_is_ie_ua_list', $ua );

	return ys_check_user_agent( $ua );
}

/**
 * Edge判定
 *
 * @return bool
 */
function ys_is_edge() {
	$ua = [
		'Edge',
	];
	$ua = apply_filters( 'ys_is_edge_ua_list', $ua );

	return ys_check_user_agent( $ua );
}

/**
 * AMP判定
 *
 * @return bool
 */
function ys_is_amp() {
	return \ystandard\AMP::is_amp();
}

/**
 * ワンカラムか
 *
 * @return bool
 */
function ys_is_one_column() {
	return \ystandard\Template::is_one_column();
}

/**
 * フル幅判定
 *
 * @return bool
 */
function ys_is_full_width() {
	return ystandard\Template::is_full_width();
}

/**
 * タイトル無しテンプレート判定
 */
function ys_is_no_title_template() {

	return ystandard\Template::is_no_title_template();
}

/**
 * アイキャッチ表示タイプがフル幅か
 *
 * @return bool
 */
function ys_is_full_width_thumbnail() {
	$result = false;
	/**
	 * 投稿ページサムネイルタイプ確認
	 */
	if ( is_singular() ) {
		if ( ys_is_amp() ) {
			if ( 'full' === ys_get_option( 'ys_amp_thumbnail_type', 'full' ) ) {
				$result = true;
			}
		} else {
			if ( 'full' === ys_get_option( 'ys_design_one_col_thumbnail_type', 'normal' ) ) {
				$result = true;
			}
		}
	}

	/**
	 * レイアウトオプション確認
	 */
	if ( is_single() && '1col' !== ys_get_option( 'ys_post_layout', '2col' ) ) {
		$result = false;
	}
	if ( is_page() && '1col' !== ys_get_option( 'ys_page_layout', '2col' ) ) {
		$result = false;
	}

	/**
	 * カスタムヘッダー確認
	 */
	if ( \ystandard\Template::is_top_page() && \ystandard\Custom_Header::is_active_custom_header() ) {
		$result = false;
	}

	return apply_filters( 'ys_is_full_width_thumbnail', $result );
}

/**
 * サイドバーを表示するか
 */
function ys_is_active_sidebar_widget() {
	$show_sidebar = true;
	if ( ys_is_amp() ) {
		$show_sidebar = false;
	}
	if ( ys_is_mobile() && ys_get_option_by_bool( 'ys_show_sidebar_mobile', false ) ) {
		$show_sidebar = false;
	}
	if ( ! is_active_sidebar( 'sidebar-widget' ) && ! is_active_sidebar( 'sidebar-fixed' ) ) {
		$show_sidebar = false;
	}
	if ( ys_is_one_column() ) {
		$show_sidebar = false;
	}

	return apply_filters( 'ys_is_active_sidebar_widget', $show_sidebar );
}

/**
 * 投稿ヘッダー情報を隠すか
 */
function ys_is_hide_post_header() {
	$result = false;
	if ( ys_is_no_title_template() ) {
		$result = true;
	}

	return apply_filters( 'ys_is_hide_post_header', $result );
}

/**
 * アイキャッチ画像を表示するか
 *
 * @param int $post_id 投稿ID.
 *
 * @return bool
 */
function ys_is_active_post_thumbnail( $post_id = null ) {
	return ystandard\Content::is_active_post_thumbnail( $post_id );
}


/**
 * 投稿日・更新日を表示するかどうか
 *
 * @return bool
 */
function ys_is_active_publish_date() {
	$result = true;
	if ( is_page() ) {
		if ( ! ys_get_option_by_bool( 'ys_show_page_publish_date', true ) ) {
			$result = false;
		}
	}
	if ( is_single() ) {
		if ( ! ys_get_option_by_bool( 'ys_show_post_publish_date', true ) ) {
			$result = false;
		}
	}
	if ( is_archive() || is_search() || is_home() ) {
		if ( ! ys_get_option_by_bool( 'ys_show_archive_publish_date', true ) ) {
			$result = false;
		}
	}
	if ( is_singular() ) {
		if ( '1' === ys_get_post_meta( 'ys_hide_publish_date' ) ) {
			$result = false;
		}
	}

	return apply_filters( 'ys_is_active_publish_date', $result );
}

/**
 * 関連記事を表示するか
 */
function ys_is_active_related_post() {
	$result = true;
	if ( is_single() ) {
		if ( ! ys_get_option_by_bool( 'ys_show_post_related', true ) ) {
			$result = false;
		}
		if ( Utility::to_bool( ys_get_post_meta( 'ys_hide_related' ) ) ) {
			$result = false;
		}
	}
	if ( ys_is_amp() ) {
		$result = false;
	}

	return apply_filters( 'ys_is_active_related_post', $result );
}


/**
 * スライドメニュー内に検索フォームを表示するか
 */
function ys_is_active_slide_menu_search_form() {
	$result = false;
	if ( ys_is_mobile() && ys_get_option_by_bool( 'ys_show_search_form_on_slide_menu', false ) ) {
		$result = true;
	}
	if ( ys_is_amp() ) {
		$result = false;
	}
	if ( is_customize_preview() ) {
		$result = true;
	}

	return apply_filters( 'ys_is_active_slide_menu_search_form', $result );
}


/**
 * キャッシュ設定が有効か判定
 */
function ys_is_enable_cache_setting() {
	if ( 'none' !== ys_get_option( 'ys_query_cache_recent_posts', 'none' ) ) {
		return true;
	}
	if ( 'none' !== ys_get_option( 'ys_query_cache_related_posts', 'none' ) ) {
		return true;
	}

	return false;
}



/**
 * アーカイブ明細クラス出力
 */
function ys_the_archive_item_class() {
	$classes = ys_get_archive_item_class();
	echo implode( ' ', $classes );
}

/**
 * アーカイブテンプレートタイプ取得
 */
function ys_get_archive_template_type() {
	return ys_get_option( 'ys_archive_type', 'list' );
}

/**
 * Front-pageでロードするテンプレート
 */
function ys_get_front_page_template() {
	$type = get_option( 'show_on_front' );
	if ( 'page' === $type ) {
		$template      = 'page';
		$page_template = get_page_template_slug();

		if ( $page_template ) {
			$template = str_replace( '.php', '', $page_template );
		}
	} else {
		$template = 'home';
	}

	return $template;
}


/**
 * ヘッダーロゴ取得
 */
function ys_get_header_logo() {
	if ( has_custom_logo() && ! ys_get_option_by_bool( 'ys_logo_hidden', false ) ) {
		$logo = get_custom_logo();
	} else {
		$logo = sprintf(
			'<a href="%s" class="custom-logo-link" rel="home">%s</a>',
			esc_url( home_url( '/' ) ),
			get_bloginfo( 'name' )
		);
	}

	return apply_filters( 'ys_get_header_logo', $logo );
}

/**
 * サイトキャッチフレーズを取得
 */
function ys_the_blog_description() {
	if ( ys_get_option_by_bool( 'ys_wp_hidden_blogdescription', false ) ) {
		return;
	}
	echo sprintf(
		'<p class="site-description header__dscr text-sub">%s</p>',
		apply_filters( 'ys_the_blog_description', get_bloginfo( 'description', 'display' ) )
	);
}
