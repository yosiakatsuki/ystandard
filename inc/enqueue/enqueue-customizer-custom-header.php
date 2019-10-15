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
	$css = '';
	/**
	 * ヘッダー重ねるタイプ
	 */
	if ( ys_get_option( 'ys_wp_header_media_full' ) ) {
		$opacity    = ys_get_option( 'ys_wp_header_media_full_opacity' );
		$opacity    = $opacity / 100;
		$text_color = '#fff';
		$bg_color   = 'rgba(0,0,0,' . $opacity . ')';
		if ( 'light' === ys_get_option( 'ys_wp_header_media_full_type' ) ) {
			$text_color = '#222';
			$bg_color   = 'rgba(255,255,255,' . $opacity . ')';
		}
		$css_media_full = <<<EOD
.custom-header--full .site-header,
.custom-header--full .h-nav.rwd li:hover ul {
  background-color: ${bg_color}; }
  
.custom-header--full .hamburger span {
  background-color: ${text_color}; }

.custom-header--full .h-nav.rwd li:hover:not(.menu-item-has-children) {
  border-bottom-color: ${text_color}; }

.custom-header--full .site-header,
.custom-header--full .header__title a,
.custom-header--full .header__title a:hover,
.custom-header--full .header__dscr,
.custom-header--full .h-nav.rwd .h-nav__main a,
.custom-header--full .h-nav.rwd .h-nav__main a:hover {
  color: ${text_color}; }
EOD;
		/**
		 * CSS結合
		 */
		$css .= $css_media_full;
	}

	/**
	 * カスタマイザープレビュー用
	 */
	if ( is_customize_preview() ) {
		$css_customize_preview = <<<EOD
.customize-partial-edit-shortcut-custom_header {
  top: 0;
  left: 0;
}
EOD;
		/**
		 * CSS結合
		 */
		$css .= $css_customize_preview;
	}

	return apply_filters( 'ys_get_customizer_inline_css_custom_header', $css );
}