<?php
/**
 * TOPページ判断（HOMEの1ページ目 or front-page）
 */
if ( ! function_exists( 'ys_is_toppage') ) {
	function ys_is_toppage() {
		if( ( is_home() && ! is_paged() ) || is_front_page() ){
			return true;
		}
		return false;
	}
}

/**
 * AMPページに広告用scriptをロードするか
 */
function ys_is_load_amp_ad_script() {
	$result = false;
	if( '' !== ys_get_option( 'ys_amp_advertisement_under_title' ) ){
		$result = true;
	}
	if( '' !== ys_get_option( 'ys_amp_advertisement_replace_more' ) ){
		$result = true;
	}
	if( '' !== ys_get_option( 'ys_amp_advertisement_under_content' ) ){
		$result = true;
	}
	return apply_filters( 'ys_is_load_amp_ad_script', $result );
}

/**
 * WordPressのjQueryを停止するかどうか
 */
function ys_is_deregister_jquery() {
	$result = false;
	if( '' !== ys_get_option( 'ys_load_cdn_jquery_url' ) ){
		$result =  true;
	}
	if( ys_get_option( 'ys_not_load_jquery' ) ){
		$result =  true;
	}
	return apply_filters( 'ys_is_deregister_jquery', $result );
}

/**
 * CDNのjQueryを読み込むかどうか
 */
function ys_is_load_cdn_jquery() {
	$result = true;
	if( '' === ys_get_option( 'ys_load_cdn_jquery_url' ) ){
		$result =  false;
	}
	if( ys_get_option( 'ys_not_load_jquery' ) ){
		$result =  false;
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
	if( ys_get_option( 'ys_ga_exclude_logged_in_user' ) ) {
		if( is_user_logged_in() ){
			/**
			 * 編集権限を持っている場合のみ出力しない
			 */
			if( current_user_can( 'edit_posts' ) ) {
				$result = false;
			}
		}
	}
	$ga_id = ys_get_google_anarytics_tracking_id();
	$ga_id_amp = ys_get_amp_google_anarytics_tracking_id();
	if( '' == $ga_id_amp && '' != $ga_id ){
		$ga_id_amp = $ga_id;
	}
	if( ys_is_amp() ) {
		if( '' == $ga_id_amp ) {
			$result = false;
		}
	} else {
		if( '' == $ga_id ) {
			$result = false;
		}
	}
	return apply_filters( 'ys_is_enable_google_analytics', $result );
}
/**
 * サイドバーを表示するか
 */
function ys_is_show_sidebar() {
	$show_sidebar = true;
	if( ys_is_amp() ) {
		$show_sidebar = false;
	} elseif( ys_is_mobile() && 1 == ys_get_setting( 'ys_show_sidebar_mobile' ) ) {
		$show_sidebar = false;
	} elseif( ! is_active_sidebar( 'sidebar-right' ) && ! is_active_sidebar( 'sidebar-fixed' ) ) {
		$show_sidebar = false;
	}
	return apply_filters( 'ys_is_show_sidebar', $show_sidebar );
}
/**
 * 絵文字用 css / js を出力するか
 */
function ys_is_show_emoji() {
	$show_emoji = true;
	if( ys_get_option( 'ys_performance_tuning_disable_emoji' ) ) {
		$show_emoji = false;
	}
	return apply_filters( 'ys_is_show_emoji', $show_emoji );
}
/**
 * oembed css / js を出力するか
 */
function ys_is_show_oembed() {
	$show_emoji = true;
	if( ys_get_option( 'ys_performance_tuning_disable_oembed' ) ) {
		$show_emoji = false;
	}
	return apply_filters( 'ys_is_show_oembed', $show_emoji );
}

/**
 * アイキャッチ画像を表示するか(singlar)
 */
function ys_show_post_thumbnail() {
	$result = ( has_post_thumbnail() && 0 == ys_get_setting( 'ys_hide_post_thumbnail' ) );
	return apply_filters( 'ys_show_post_thumbnail', $result );
}

/**
 * 記事下ウィジェットを表示するか
 */
function ys_show_entry_footer_widget() {
	$result = ( is_active_sidebar( 'entry-footer' ) && ! ys_is_amp() );
	return apply_filters( 'ys_show_entry_footer_widget', $result );
}