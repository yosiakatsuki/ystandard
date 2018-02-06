<?php
/**
 * advertisement
 */

/**
 * 広告コードのhtml整形
 */
if( ! function_exists( 'ys_get_ad_block_html' ) ) {
	function ys_get_ad_block_html( $ad ) {
		$html = '';
		if( '' !== $ad && ! is_feed() ) {
			$label_text = apply_filters( 'ys_ad_label_text', 'スポンサーリンク' );
			$html = sprintf(
								'<aside class="ad__container">
									<div class="ad__label">%s</div>
									<div class="ad__content">%s</div>
								</aside>',
								$label_text,
								$ad
							);
		}
		return apply_filters( 'ys_get_ad_block_html', $html );
	}
}
/**
 * 記事上広告の取得
 */
if( ! function_exists( 'ys_get_ad_entry_header' ) ) {
	function ys_get_ad_entry_header() {
		$key = 'ys_advertisement_under_title';
		if( ys_is_mobile() ){
			$key = 'ys_advertisement_under_title_sp';
		}
		if( ys_is_amp() ){
			$key = 'ys_amp_advertisement_under_title';
		}
		$ad = '';
		$ad = ys_get_option( $key );
		return apply_filters( 'ys_get_ad_entry_header', ys_get_ad_block_html( $ad ) );
	}
}
function ys_the_ad_entry_header() {
	if( ys_is_active_advertisement() ) {
		echo ys_get_ad_entry_header();
	}
}
/**
 * moreタグ広告の取得
 */
if( ! function_exists( 'ys_get_ad_more_tag' ) ) {
	function ys_get_ad_more_tag() {
		$key = 'ys_advertisement_replace_more';
		if( ys_is_mobile() ){
			$key = 'ys_advertisement_replace_more_sp';
		}
		if( ys_is_amp() ){
			$key = 'ys_amp_advertisement_replace_more';
		}
		$ad = '';
		$ad = ys_get_option( $key );
		return apply_filters( 'ys_get_ad_more_tag', ys_get_ad_block_html( $ad ) );
	}
}
function ys_the_ad_more_tag() {
	if( ys_is_active_advertisement() ) {
		echo ys_get_ad_more_tag();
	}
}
/**
 * 記事下広告の取得
 */
if( ! function_exists( 'ys_get_ad_entry_footer' ) ) {
	function ys_get_ad_entry_footer() {

		$key_left = 'ys_advertisement_under_content_left';
		$key_right = 'ys_advertisement_under_content_right';

		if( ys_is_mobile() ){
			$key_left = 'ys_advertisement_under_content_sp';
			$key_right = '';
		}
		if( ys_is_amp() ){
			$key_left = 'ys_amp_advertisement_under_content';
			$key_right = '';
		}

		$ad = '';
		$ad_left = ys_get_option( $key_left );
		$ad_right = '';
		if( '' !== $key_right ){
			$ad_right = ys_get_option( $key_right );
		}
		if( '' !== $ad_left && '' !== $ad_right ){
			$ad = sprintf(
							'<div class="ad__double flex-wrap--tb flex--a-top">
								<div class="ad__left">%s</div>
								<div class="ad__right">%s</div>
							</div>',
							$ad_left,
							$ad_right
						);
		} else {
			if( '' !== $ad_right ){
				$ad = $ad_right;
			}
			if( '' !== $ad_left ){
				$ad = $ad_left;
			}
		}
		return apply_filters( 'ys_get_ad_entry_footer', ys_get_ad_block_html( $ad ) );
	}
}
function ys_the_ad_entry_footer() {
	if( ys_is_active_advertisement() ) {
		echo ys_get_ad_entry_footer();
	}
}