<?php

/**
 * AMPページに広告を表示するか
 */
function ys_is_load_amp_ad_js() {
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
	return apply_filters( 'ys_is_load_amp_ad_js', $result );
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