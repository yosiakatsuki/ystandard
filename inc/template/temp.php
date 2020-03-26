<?php

/**
 * カスタムヘッダータイプ
 *
 * @return string
 */
function ys_get_custom_header_type() {
	$type = 'image';
	if ( is_header_video_active() && has_header_video() ) {
		$type = 'video';
	}
	/**
	 * 詳細ページではフルサムネイル表示か確認
	 */
	if ( ys_is_full_width_thumbnail() && ! \ystandard\Custom_Header::is_active_custom_header() ) {
		$type = 'full-thumb';
	}

	return apply_filters( 'ys_get_custom_header_type', $type );
}

/**
 * カスタムヘッダーの出力
 */
function ys_the_custom_header_markup() {
	if ( ys_is_full_width_thumbnail() && ! \ystandard\Custom_Header::is_active_custom_header() ) {
		/**
		 * 個別ページの画像表示
		 */
		printf(
			'<div class="header__full-thumbnail">%s</div>',
			get_the_post_thumbnail()
		);
	} else {
		/**
		 * ショートコード入力があればそちらを優先
		 */
		$media_shortcode = ys_get_option( 'ys_wp_header_media_shortcode', '' );
		if ( $media_shortcode ) {
			echo do_shortcode( $media_shortcode );
		} else {
			the_custom_header_markup();
		}
	}

}

/**
 * Google Analytics idの取得
 */
function ys_get_google_anarytics_tracking_id() {
	return apply_filters(
		'ys_get_google_anarytics_tracking_id',
		trim( ys_get_option( 'ys_ga_tracking_id', '' ) )
	);
}

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

	/**
	 * [^(?!.*iPad).*iPhone] : iPadとiPhoneが混ざるUAがあるらしい
	 */
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
	return ystandard\AMP::is_amp();
}

/**
 * ワンカラムか
 *
 * @return bool
 */
function ys_is_one_column() {
	return ystandard\Template::is_one_column();
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
 * WordPressのjQueryを停止するかどうか
 */
function ys_is_deregister_jquery() {
	$result = false;
	if ( '' !== ys_get_option( 'ys_load_cdn_jquery_url', '' ) ) {
		$result = true;
	}
	if ( ys_get_option_by_bool( 'ys_not_load_jquery', false ) ) {
		$result = true;
	}
	if ( ys_get_option_by_bool( 'ys_load_jquery_in_footer', false ) ) {
		$result = true;
	}

	return apply_filters( 'ys_is_deregister_jquery', $result );
}

/**
 * WordPressのjQueryを無効化するかどうか
 */
function ys_is_disable_jquery() {
	$result = false;
	if ( ys_get_option_by_bool( 'ys_not_load_jquery', false ) ) {
		$result = true;
	}

	return apply_filters( 'ys_is_disable_jquery', $result );
}

/**
 * WordPressのjQueryをフッターで読み込むか
 */
function ys_is_load_jquery_in_footer() {
	$result = false;
	if ( ys_get_option_by_bool( 'ys_load_jquery_in_footer', false ) ) {
		$result = true;
	}

	return apply_filters( 'ys_is_load_jquery_in_footer', $result );
}

/**
 * CDNのjQueryを読み込むかどうか
 */
function ys_is_load_cdn_jquery() {
	$result = true;
	if ( '' === ys_get_option( 'ys_load_cdn_jquery_url', '' ) ) {
		$result = false;
	}
	if ( ys_get_option_by_bool( 'ys_not_load_jquery', false ) ) {
		$result = false;
	}

	return apply_filters( 'ys_is_load_cdn_jquery', $result );
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
 * 記事先頭シェアボタンを表示するか
 */
function ys_is_active_sns_share_on_header() {
	$result = true;
	if ( is_singular() ) {
		if ( '1' === ys_get_post_meta( 'ys_hide_share' ) ) {
			$result = false;
		}
	}
	if ( ! ys_get_option_by_bool( 'ys_sns_share_on_entry_header', true ) ) {
		$result = false;
	}

	return apply_filters( 'ys_is_active_sns_share_on_header', $result );
}

/**
 * 記事下シェアボタンを表示するか
 */
function ys_is_active_sns_share_on_footer() {
	$result = true;
	if ( is_singular() ) {
		if ( '1' === ys_get_post_meta( 'ys_hide_share' ) ) {
			$result = false;
		}
	}
	if ( ! ys_get_option_by_bool( 'ys_sns_share_on_below_entry', true ) ) {
		$result = false;
	}

	return apply_filters( 'ys_is_active_sns_share_on_footer', $result );
}


/**
 * 記事上ウィジェットを表示するか
 */
function ys_is_active_before_content_widget() {
	$result = false;
	if ( is_active_sidebar( 'before-content' ) ) {
		if ( ys_is_amp() ) {
			if ( ys_get_option_by_bool( 'ys_show_amp_before_content_widget', false ) ) {
				$result = true;
			}
		} else {
			if ( is_single() && ys_get_option_by_bool( 'ys_show_post_before_content_widget', false ) ) {
				$result = true;
			}
			if ( is_page() && ys_get_option_by_bool( 'ys_show_page_before_content_widget', false ) ) {
				$result = true;
			}
		}
	}

	return apply_filters( 'ys_is_active_before_content_widget', $result );
}

/**
 * 記事下ウィジェットを表示するか
 */
function ys_is_active_after_content_widget() {
	$result = false;
	if ( is_active_sidebar( 'after-content' ) ) {
		if ( ys_is_amp() ) {
			if ( ys_get_option_by_bool( 'ys_show_amp_after_content_widget', false ) ) {
				$result = true;
			}
		} else {
			if ( is_single() && ys_get_option_by_bool( 'ys_show_post_after_content_widget', false ) ) {
				$result = true;
			}
			if ( is_page() && ys_get_option_by_bool( 'ys_show_page_after_content_widget', false ) ) {
				$result = true;
			}
		}
	}

	return apply_filters( 'ys_is_active_after_content_widget', $result );
}


/**
 * フォローBOXを表示するか
 */
function ys_is_active_follow_box() {
	$result = true;
	if ( is_singular() ) {
		if ( '1' === ys_get_post_meta( 'ys_hide_follow' ) ) {
			$result = false;
		}
	}
	if ( is_single() && ! ys_get_option_by_bool( 'ys_show_post_follow_box', true ) ) {
		$result = false;
	}
	if ( is_page() && ! ys_get_option_by_bool( 'ys_show_page_follow_box', true ) ) {
		$result = false;
	}
	if ( ys_is_amp() ) {
		$result = false;
	}

	return apply_filters( 'ys_is_active_follow_box', $result );
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
		if ( ys_to_bool( ys_get_post_meta( 'ys_hide_related' ) ) ) {
			$result = false;
		}
	}
	if ( ys_is_amp() ) {
		$result = false;
	}

	return apply_filters( 'ys_is_active_related_post', $result );
}

/**
 * 前の記事・次の記事を表示するか
 */
function ys_is_active_post_paging() {
	$result = true;
	if ( is_single() ) {
		if ( ! ys_get_option_by_bool( 'ys_show_post_paging', true ) ) {
			$result = false;
		}
		if ( '1' === ys_get_post_meta( 'ys_hide_paging' ) ) {
			$result = false;
		}
	}
	if ( ys_is_amp() ) {
		$result = false;
	}

	return apply_filters( 'ys_is_active_post_paging', $result );
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
	if ( 'none' !== ys_get_option( 'ys_query_cache_ranking', 'none' ) ) {
		return true;
	}

	return false;
}
