<?php
/**
 * カスタマイザーで出力するCSS作成:カスタムヘッダー
 *
 * @package ystandard
 * @author  yosiakatsuki
 * @license GPL-2.0+
 */

/**
 * テーマカスタマイザーでのCSS設定 カスタムヘッダー
 *
 * @return string
 */
function ys_get_customizer_inline_css_custom_header() {
	if ( ! ys_is_active_custom_header() || ys_is_full_width_thumbnail() ) {
		return '';
	}
	$opacity    = ys_get_option( 'ys_wp_header_media_full_opacity' );
	$opacity    = $opacity / 100;
	$text_color = '#fff';
	$bg_color   = 'rgba(0,0,0,' . $opacity . ')';
	if ( 'light' == ys_get_option( 'ys_wp_header_media_full_type' ) ) {
		$text_color = '#222';
		$bg_color   = 'rgba(255,255,255,' . $opacity . ')';
	}
	$css = <<<EOD
.custom-header--full .site-header,
.custom-header--full .h-nav.rwd li:hover ul {
  background-color: ${bg_color};
}
.custom-header--full .h-nav.rwd li:hover:not(.has-child) {
  border-bottom-color: ${text_color};
}
.custom-header--full .site-header,
.custom-header--full .header__title a,
.custom-header--full .header__title a:hover,
.custom-header--full .header__dscr,
.custom-header--full .h-nav.rwd .h-nav__main a,
.custom-header--full .h-nav.rwd .h-nav__main a:hover {
  color: ${text_color};
}
EOD;
	
	return apply_filters( 'ys_get_customizer_inline_css_custom_header', $css );
}