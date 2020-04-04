<?php
/**
 * インラインCSS作成クラス
 *
 * @package ystandard
 * @author  yosiakatsuki
 * @license GPL-2.0+
 */

/**
 * インラインCSS作成クラス
 */
class YS_Inline_Css {

	/**
	 * インラインCSSの取得
	 */
	public function get_inline_css() {
		$styles = [];
		/**
		 * フッター
		 */
		$styles[] = $this->get_footer_css();

		/**
		 * カスタムヘッダー
		 */
		$styles[] = $this->get_custom_header_css();

		/**
		 * モバイルフッター
		 */
		$styles[] = $this->get_mobile_footer_css();

		$inline_css = implode( '', $styles );
	}

	/**
	 * カスタムヘッダーCSS
	 *
	 * @return string
	 */
	public function get_custom_header_css() {
		$css = [];

		return implode( '', $css );
	}

	/**
	 * モバイルフッター用CSS
	 *
	 * @return string
	 */
	public function get_mobile_footer_css() {
		if ( ! has_nav_menu( 'mobile-footer' ) ) {
			return '';
		}

		return '
		.footer-mobile-nav {
		  width: 100%;
		  position: fixed;
		  bottom: 0;
		  left: 0;
		  background-color: rgba(255, 255, 255, 0.95);
		  border-top: 1px solid #EEEEEE;
		  text-align: center;
		  z-index: 8; }
		  .footer-mobile-nav ul {
		    padding: .75rem 0 .5rem; }
		  .footer-mobile-nav a {
		    display: block;
		    color: inherit;
		    text-decoration: none; }
		  .footer-mobile-nav svg, .footer-mobile-nav i {
		    font-size: 1.5em; }

		.footer-mobile-nav__dscr {
		  display: block;
		  font-size: .7em;
		  line-height: 1.2; }

		.has-mobile-footer .site__footer {
		  padding-bottom: 5rem; }

		@media screen and (min-width: 1025px) {
		    .footer-mobile-nav {
		      display: none; }
		    .has-mobile-footer .site__footer {
		      padding-bottom: 1rem; } }
		';
	}

}
