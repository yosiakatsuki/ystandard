<?php

/**
 * AMPページに広告を表示するか
 */
function ys_is_show_advertisement() {
	if( '' !== ys_get_option( 'ys_amp_advertisement_under_title' ) ){
		return true;
	}
	if( '' !== ys_get_option( 'ys_amp_advertisement_replace_more' ) ){
		return true;
	}
	if( '' !== ys_get_option( 'ys_amp_advertisement_under_content' ) ){
		return true;
	}
	return false;
}