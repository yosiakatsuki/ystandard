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
	if ( ! ys_is_active_custom_header() ) {
		return '';
	}

	$css     = <<<EOD

.wp-custom-header {
	position: relative;
}

.wp-custom-header img {
	display: block;
	height: auto;
	width: 100%;
	margin: 0 auto;
}

.ys-custom-header--video .wp-custom-header {
	width: 100%;
	padding-top: 56.25%;
}

.ys-custom-header--video .wp-custom-header video,
.ys-custom-header--video .wp-custom-header iframe {
	position: absolute;
	top: 0;
	left: 0;
	width: 100%;
	height: 100%;
}

.wp-custom-header-video-button {
	display: block;
	position: absolute;
	left: .5em;
	bottom: .5em;
	padding: .5em;
	border: 1px solid rgba(255, 255, 255, 0.3);
	background-color: rgba(0, 0, 0, 0.3);
	font-size: .8em;
	line-height: 1;
	color: rgba(255, 255, 255, 0.3);
}

@media screen and (min-width: 960px) {

.cunstom-header--full .site-header {
	width: 100%;
	position: absolute;
	top: 0;
	left: 0;
	z-index: 11;
	background-color: '#bg_color#';
	color: '#text_color#';
}

.cunstom-header--full .color__site-header,
.cunstom-header--full .color__site-title,
.cunstom-header--full .color__site-title:hover,
.cunstom-header--full .color__site-dscr {
	color: '#text_color#';
}

.cunstom-header--full .color__nav-font--pc {
	color: '#text_color#';
}

.cunstom-header--full .color__nav-bg--pc,
.cunstom-header--full .global-nav__sub-menu li {
	background-color: transparent;
}

.cunstom-header--full .global-nav__item:not(.menu-item-has-children):hover {
	border-bottom-color: '#text_color#';
}

.cunstom-header--full.admin-bar .site-header {
	top: 32px;
}

}



EOD;
	$opacity = ys_get_option( 'ys_wp_header_media_full_opacity' );
	$opacity = $opacity / 100;
	if ( 'light' == ys_get_option( 'ys_wp_header_media_menu_on_media_type' ) ) {
		$css = str_replace( '\'#text_color#\'', '#222', $css );
		$css = str_replace( '\'#bg_color#\'', 'rgba(255,255,255,' . $opacity . ')', $css );
	} else {
		$css = str_replace( '\'#text_color#\'', '#fff', $css );
		$css = str_replace( '\'#bg_color#\'', 'rgba(0,0,0,' . $opacity . ')', $css );
	}

	return apply_filters( 'ys_get_customizer_inline_css_custom_header', $css );
}