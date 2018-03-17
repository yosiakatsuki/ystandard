<?php
/**
 * 条件判断用関数群
 *
 * @package ystandard
 * @author yosiakatsuki
 * @license GPL-2.0+
 */

if ( ! function_exists( 'ys_is_top_page' ) ) {
	/**
	 * TOPページ判断（HOMEの1ページ目 or front-page）
	 *
	 * @return bool
	 */
	function ys_is_top_page() {
		if ( 'page' === get_option( 'show_on_front' ) ) {
			if ( is_front_page() ) {
				return true;
			}
		} else {
			if ( is_home() && ! is_paged() ) {
				return true;
			}
		}
		return false;
	}
}
/**
 * ユーザーエージェントのチェック
 *
 * @param array $ua 対象ユーザーエージェントのリスト.
 */
function ys_check_user_agent( $ua ) {
	if ( ! isset( $_SERVER['HTTP_USER_AGENT'] ) ) {
		return false;
	}
	$pattern = '/' . implode( '|', $ua ) . '/i';
	return preg_match( $pattern, $_SERVER['HTTP_USER_AGENT'] );
}

if ( ! function_exists( 'ys_is_mobile' ) ) {
	/**
	 * モバイル判定
	 *
	 * @return bool
	 */
	function ys_is_mobile() {
		/**
		 * [^(?!.*iPad).*iPhone] : iPadとiPhoneが混ざるUAがあるらしい
		 */
		$ua = array(
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
		);
		return ys_check_user_agent( $ua );
	}
}

if ( ! function_exists( 'ys_is_ie' ) ) {
	/**
	 * IE判定
	 *
	 * @return bool
	 */
	function ys_is_ie() {
		$ua = array(
			'Trident',
			'MSIE',
		);
		return ys_check_user_agent( $ua );
	}
}

if ( ! function_exists( 'ys_is_edge' ) ) {
	/**
	 * Edge判定
	 *
	 * @return bool
	 */
	function ys_is_edge() {
		$ua = array(
			'Edge',
		);
		return ys_check_user_agent( $ua );
	}
}

if ( ! function_exists( 'ys_is_amp' ) ) {
	/**
	 * AMP判定
	 *
	 * @return bool
	 */
	function ys_is_amp() {
		global $ys_amp;
		if ( null !== $ys_amp ) {
			return $ys_amp;
		}
		$param_amp = '';
		if ( isset( $_GET['amp'] ) ) {
			$param_amp = $_GET['amp'];
		}
		if ( '1' === $param_amp && ys_is_amp_enable() ) {
			$ys_amp = true;
		} else {
			$ys_amp = false;
		}
		return apply_filters( 'ys_is_amp', $ys_amp );
	}
}

if ( ! function_exists( 'ys_is_amp_enable' ) ) {
	/**
	 * AMPページにできるか判断
	 *
	 * @return bool
	 */
	function ys_is_amp_enable() {
		global $post;
		$result = true;
		if ( 0 == ys_get_option( 'ys_amp_enable' ) ) {
			return apply_filters( 'ys_is_amp_enable', false );
		}
		if ( ! is_single() ) {
			return apply_filters( 'ys_is_amp_enable', false );
		}
		/**
		 * 投稿ごとのAMPページ生成判断
		 */
		if ( '1' === ys_get_post_meta( 'ys_post_meta_amp_desable', $post->ID ) ) {
			$result = false;
		}
		return apply_filters( 'ys_is_amp_enable', $result );
	}
}

if ( ! function_exists( 'ys_is_one_column' ) ) {
	/**
	 * ワンカラムテンプレートか
	 *
	 * @return bool
	 */
	function ys_is_one_column() {
		$one_colmun = false;
		if ( is_page_template( 'page-template/template-one-column.php' ) ) {
			$one_colmun = true;
		}
		if ( ! is_active_sidebar( 'sidebar-widget' ) && ! is_active_sidebar( 'sidebar-fixed' ) ) {
			$one_colmun = true;
		}
		return apply_filters( 'ys_is_one_column', $one_colmun );
	}
}

/**
 * WordPressのjQueryを停止するかどうか
 */
function ys_is_deregister_jquery() {
	$result = false;
	if ( '' !== ys_get_option( 'ys_load_cdn_jquery_url' ) ) {
		$result = true;
	}
	if ( ys_get_option( 'ys_not_load_jquery' ) ) {
		$result = true;
	}
	return apply_filters( 'ys_is_deregister_jquery', $result );
}

/**
 * CDNのjQueryを読み込むかどうか
 */
function ys_is_load_cdn_jquery() {
	$result = true;
	if ( '' === ys_get_option( 'ys_load_cdn_jquery_url' ) ) {
		$result = false;
	}
	if ( ys_get_option( 'ys_not_load_jquery' ) ) {
		$result = false;
	}
	return apply_filters( 'ys_is_load_cdn_jquery', $result );
}

/**
 * Google Analyticsのタグを出力するか
 */
function ys_is_enable_google_analytics() {
	$result = true;
	/**
	 * ログイン中にGA出力しない場合
	 */
	if ( ys_get_option( 'ys_ga_exclude_logged_in_user' ) ) {
		if ( is_user_logged_in() ) {
			/**
			 * 編集権限を持っている場合のみ出力しない
			 */
			if ( current_user_can( 'edit_posts' ) ) {
				$result = false;
			}
		}
	}
	$ga_id     = ys_get_google_anarytics_tracking_id();
	$ga_id_amp = ys_get_amp_google_anarytics_tracking_id();
	if ( '' == $ga_id_amp && '' != $ga_id ) {
		$ga_id_amp = $ga_id;
	}
	if ( ys_is_amp() ) {
		if ( '' == $ga_id_amp ) {
			$result = false;
		}
	} else {
		if ( '' == $ga_id ) {
			$result = false;
		}
	}
	return apply_filters( 'ys_is_enable_google_analytics', $result );
}
/**
 * サイドバーを表示するか
 */
function ys_is_active_sidebar_widget() {
	$show_sidebar = true;
	if ( ys_is_amp() ) {
		$show_sidebar = false;
	} elseif ( ys_is_mobile() && 1 == ys_get_setting( 'ys_show_sidebar_mobile' ) ) {
		$show_sidebar = false;
	} elseif ( ! is_active_sidebar( 'sidebar-widget' ) && ! is_active_sidebar( 'sidebar-fixed' ) ) {
		$show_sidebar = false;
	}
	return apply_filters( 'ys_is_active_sidebar_widget', $show_sidebar );
}
/**
 * 絵文字用 css / js を出力するか
 */
function ys_is_active_emoji() {
	$show_emoji = true;
	if ( ys_get_option( 'ys_option_disable_wp_emoji' ) ) {
		$show_emoji = false;
	}
	return apply_filters( 'ys_is_active_emoji', $show_emoji );
}
/**
 * Oembed css / js を出力するか
 */
function ys_is_active_oembed() {
	$show_emoji = true;
	if ( ys_get_option( 'ys_option_disable_wp_oembed' ) ) {
		$show_emoji = false;
	}
	return apply_filters( 'ys_is_active_oembed', $show_emoji );
}

/**
 * アイキャッチ画像を表示するか(singlar)
 *
 * @param int $post_id 投稿ID.
 */
function ys_is_active_post_thumbnail( $post_id = null ) {
	$result = true;
	if ( ! has_post_thumbnail( $post_id ) ) {
		$result = false;
	}
	/**
	 * 投稿ページ
	 */
	if ( is_single() ) {
		if ( ! ys_get_option( 'ys_show_post_thumbnail' ) ) {
			$result = false;
		}
	}
	/**
	 * 固定ページ
	 */
	if ( is_page() ) {
		if ( ! ys_get_option( 'ys_show_page_thumbnail' ) ) {
			$result = false;
		}
	}
	return apply_filters( 'ys_is_active_post_thumbnail', $result );
}

/**
 * 記事先頭シェアボタンを表示するか
 */
function ys_is_active_entry_header_share() {
	$result = true;
	if ( is_singular() ) {
		if ( '1' === ys_get_post_meta( 'ys_hide_share' ) ) {
			$result = false;
		}
	}
	if ( ! ys_get_option( 'ys_sns_share_on_entry_header' ) ) {
		$result = false;
	}
	return apply_filters( 'ys_is_active_entry_header_share', $result );
}
/**
 * 記事下シェアボタンを表示するか
 */
function ys_is_active_entry_footer_share() {
	$result = true;
	if ( is_singular() ) {
		if ( '1' === ys_get_post_meta( 'ys_hide_share' ) ) {
			$result = false;
		}
	}
	if ( ! ys_get_option( 'ys_sns_share_on_below_entry' ) ) {
		$result = false;
	}
	return apply_filters( 'ys_is_active_entry_footer_share', $result );
}


/**
 * 記事下ウィジェットを表示するか
 */
function ys_is_active_entry_footer_widget() {
	$result = ( is_active_sidebar( 'entry-footer' ) && ! ys_is_amp() );
	return apply_filters( 'ys_is_active_entry_footer_widget', $result );
}

/**
 * 著者情報表示するか
 */
function ys_is_display_author_data() {
	$result = true;
	if ( is_singular() ) {
		/**
		 * 投稿個別設定
		 */
		if ( '1' === ys_get_post_meta( 'ys_hide_author' ) ) {
			$result = false;
		}
		/**
		 * 投稿ページ
		 */
		if ( is_single() && ! ys_get_option( 'ys_show_post_author' ) ) {
			$result = false;
		}
		/**
		 * 固定ページ
		 */
		if ( is_page() && ! ys_get_option( 'ys_show_page_author' ) ) {
			$result = false;
		}
	} else {
		/**
		 * 記事一覧系
		 */
		if ( ! ys_get_option( 'ys_show_archive_author' ) ) {
			$result = false;
		}
	}
	return apply_filters( 'ys_is_display_author_data', $result );
}
/**
 * フッターウィジェットが有効か
 */
function ys_is_active_footer_widgets() {
	$result = true;
	if ( ys_is_amp() ) {
		$result = false;
	}
	if ( ! is_active_sidebar( 'footer-left' ) && ! is_active_sidebar( 'footer-center' ) && ! is_active_sidebar( 'footer-right' ) ) {
		$result = false;
	}
	return apply_filters( 'ys_is_active_footer_widgets', $result );
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
	if ( is_single() && ! ys_get_option( 'ys_show_post_follow_box' ) ) {
		$result = false;
	}
	if ( is_page() && ! ys_get_option( 'ys_show_page_follow_box' ) ) {
		$result = false;
	}
	if ( ys_is_amp() ) {
		$result = false;
	}
	return apply_filters( 'ys_is_active_follow_box', $result );
}

/**
 * 広告を表示するか
 */
function ys_is_active_advertisement() {
	$result = true;
	if ( is_singular() ) {
		if ( '1' === ys_get_post_meta( 'ys_hide_ad' ) ) {
			$result = false;
		}
	}
	return apply_filters( 'ys_is_active_advertisement', $result );
}
/**
 * 関連記事を表示するか
 */
function ys_is_active_related_post() {
	$result = true;
	if ( is_single() ) {
		if ( ! ys_get_option( 'ys_show_post_related' ) ) {
			$result = false;
		}
		if ( '1' === ys_get_post_meta( 'ys_hide_related' ) ) {
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
		if ( ! ys_get_option( 'ys_show_post_paging' ) ) {
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
 * 管理画面の投稿タイプ判断用
 *
 * @param  string $type post type.
 * @return boolean
 */
function ys_is_post_type_on_admin( $type ) {
	global $post_type;
	return ( $type == $post_type );
}

/**
 * CSS読み込みを最適化するか
 *
 * @return bool
 */
function ys_is_optimize_load_css() {
	return ys_get_option( 'ys_option_optimize_load_css' );
}

/**
 * スライドメニュー内に検索フォームを表示するか
 */
function ys_is_active_slide_menu_search_form() {
	$result = false;
	if ( wp_is_mobile() && ys_get_option( 'ys_show_search_form_on_slide_menu' ) ) {
		$result = true;
	}
	return apply_filters( 'ys_is_active_slide_menu_search_form', $result );
}