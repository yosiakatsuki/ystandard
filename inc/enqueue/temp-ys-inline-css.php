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
		 * ヘッダー
		 */
		$styles[] = $this->get_header_css();
		/**
		 * ナビゲーション
		 */
		$styles[] = $this->get_nav_css();
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
	 * ヘッダーCSS
	 *
	 * @return string
	 */
	public function get_header_css() {
		$styles      = [];
		$header_bg   = ys_get_option( 'ys_color_header_bg', '#ffffff' );
		$header_font = ys_get_option( 'ys_color_header_font', '#222222' );
		$header_dscr = ys_get_option( 'ys_color_header_dscr_font', '#757575' );

		$styles[] = ".site-header {background-color:${header_bg};}";
		if ( ! ys_is_amp() ) {
			$styles[] = $this->add_media_query(
				".h-nav.rwd li:hover ul {background-color:${header_bg};opacity:.9;}",
				'lg'
			);
		}
		$styles[] = ".header__title,.header__title a {color:${header_font};}";
		$styles[] = ".header__title,.header__title a {color:${header_font};}";
		$styles[] = ".header__dscr {color:${header_dscr};}";

		/**
		 * 固定ヘッダー.
		 */
		if ( ys_get_option_by_bool( 'ys_header_fixed', false ) ) {
			$height_pc     = ys_get_option( 'ys_header_fixed_height_pc', 0 );
			$height_tablet = ys_get_option( 'ys_header_fixed_height_tablet', 0 );
			$height_mobile = ys_get_option( 'ys_header_fixed_height_mobile', 0 );
			$pt_pc         = $height_pc;
			$pt_tablet     = $height_tablet;
			$pt_mobile     = $height_mobile;
			if ( 0 === (int) $pt_tablet ) {
				$pt_tablet = $height_pc;
				if ( 0 < (int) $height_mobile ) {
					$pt_tablet = $height_mobile;
				}
			}
			if ( 0 === (int) $pt_mobile ) {
				$pt_mobile = $height_pc;
				if ( 0 < (int) $height_tablet ) {
					$pt_mobile = $height_tablet;
				}
			}
			/**
			 * CSS
			 */
			$styles[] = '.has-fixed-header .site-header {
			  width: 100%;
			  position: fixed;
			  top: 0;
			  left: 0;
			  box-shadow: 0 1px 1px rgba(189, 195, 199, .2);
			  z-index: 9;
			}';
			/**
			 * Bodyのpadding調整
			 */
			if ( ! ys_is_has_header_media_full() ) {
				// フルヘッダーなし.
				$styles[] = ".has-fixed-header .header__row {
				  min-height:${height_mobile}px;
				}
				body.has-fixed-header {
				  padding-top:${pt_mobile}px;
				}";
				$styles[] = $this->add_media_query(
					".has-fixed-header .header__row {
					  min-height:${height_tablet}px;
					}
					body.has-fixed-header {
					  padding-top:${pt_tablet}px;
					}",
					'md'
				);
				$styles[] = $this->add_media_query(
					".has-fixed-header .header__row {
					  min-height:${height_pc}px;
					}
					body.has-fixed-header {
					  padding-top:${pt_pc}px;
					}",
					'lg'
				);
				$styles[] = $this->add_media_query(
					".has-sidebar.has-fixed-header .sidebar__fixed {top: calc(1rem + ${height_pc}px);}",
					'lg'
				);
				// アドミンバーあり.
				if ( is_admin_bar_showing() ) {
					$styles[] = "
					@media screen and (min-width: 601px) {
					.admin-bar.has-fixed-header .site-header {
					  top: 46px;
					}}
					@media screen and (min-width: 783px) {
					.admin-bar.has-fixed-header .site-header {
					  top: 32px;
					}}
					body.admin-bar.has-fixed-header {
					  padding-top:calc(${pt_mobile}px - 46px);
					}
					@media screen and (min-width: 783px) {
					body.admin-bar.has-fixed-header {
					  padding-top:calc(32px + ${pt_mobile}px);
					}}
					@media screen and (min-width: 601px) {
					body.admin-bar.has-fixed-header {
					  padding-top:calc(${pt_tablet}px);
					}}";
					$styles[] = $this->add_media_query(
						"body.admin-bar.has-fixed-header {
						  padding-top:calc(${pt_pc}px);
						}",
						'lg'
					);
				}
			} else {
				// フルヘッダーあり.
				$styles[] = $this->add_media_query(
					".has-fixed-header .header__row {
					  min-height:${height_mobile}px;
					}
					body.has-fixed-header {
					  padding-top:${pt_mobile}px;
					}",
					'md',
					'max'
				);
			}

		}

		return implode( '', $styles );
	}

	/**
	 * ナビゲーションCSS
	 *
	 * @return string
	 */
	public function get_nav_css() {
		$styles               = [];
		$header_font          = ys_get_option( 'ys_color_header_font', '#222222' );
		$mobile_nav_bg        = ys_get_option( 'ys_color_nav_bg_sp', '#000000' );
		$mobile_nav_font      = ys_get_option( 'ys_color_nav_font_sp', '#ffffff' );
		$mobile_nav_btn_open  = ys_get_option( 'ys_color_nav_btn_sp_open', '#222222' );
		$mobile_nav_btn_close = ys_get_option( 'ys_color_nav_btn_sp', '#ffffff' );

		if ( ! ys_is_amp() ) {
			$styles[] = $this->add_media_query(
				".h-nav.rwd .h-nav__main a {color:${header_font};}",
				'lg'
			);
			$styles[] = $this->add_media_query(
				".h-nav.rwd li:hover:not(.menu-item-has-children) a{border-color:${header_font};}",
				'lg'
			);
		}
		/**
		 * モバイルナビゲーション
		 */
		$styles[] = $this->add_media_query(
			".h-nav__main {background-color:${mobile_nav_bg};}",
			'lg',
			'max'
		);
		$styles[] = $this->add_media_query(
			".h-nav__main a,.h-nav__search input,.h-nav__search button {
				color:${mobile_nav_font};
			}",
			'lg',
			'max'
		);
		$styles[] = $this->add_media_query(
			".h-nav__search input,.h-nav__search button {
				border-color:${mobile_nav_font};
			}",
			'lg',
			'max'
		);

		$styles[] = ".hamburger span {background-color:${mobile_nav_btn_open};}";
		$styles[] = $this->add_media_query(
			"#h-nav__toggle:checked~.h-nav__btn .hamburger span {
				background-color:${mobile_nav_btn_close};
			}",
			'lg',
			'max'
		);

		return implode( '', $styles );
	}

	/**
	 * フッターCSS
	 *
	 * @return string
	 */
	public function get_footer_css() {
		$styles      = [];
		$footer_bg   = ys_get_option( 'ys_color_footer_bg', '#222222' );
		$footer_font = ys_get_option( 'ys_color_footer_font', '#ffffff' );
		/**
		 * フッター
		 */
		$styles[] = ".site__footer {background-color:${footer_bg};}";
		$styles[] = ".site__footer,.site__footer a, .site__footer a:hover {
			color:${footer_font};
		}";
		/**
		 * フッターSNSリンク
		 */
		$opacity       = ys_get_option( 'ys_color_footer_sns_bg_opacity', 30 );
		$opacity       = ( $opacity / 100 );
		$hover_opacity = ( $opacity + 0.2 );
		if ( 1 < ( $opacity + 0.2 ) ) {
			$hover_opacity = 1;
		}
		if ( 'light' === ys_get_option( 'ys_color_footer_sns_bg_type', 'light' ) ) {
			$color       = 'rgba(255,255,255,' . $opacity . ')';
			$hover_color = 'rgba(255,255,255,' . $hover_opacity . ')';
		} else {
			$color       = 'rgba(0,0,0,' . $opacity . ')';
			$hover_color = 'rgba(0,0,0,' . $hover_opacity . ')';
		}

		$styles[] = ".footer-sns__link {background-color:${color};}";
		$styles[] = ".footer-sns__link:hover {background-color:${hover_color};}";

		return implode( '', $styles );
	}

	/**
	 * カスタムヘッダーCSS
	 *
	 * @return string
	 */
	public function get_custom_header_css() {
		$css = [];
		/**
		 * ヘッダー重ねるタイプ
		 */
		if ( ys_get_option_by_bool( 'ys_wp_header_media_full', false ) ) {
			$opacity    = ys_get_option_by_int( 'ys_wp_header_media_full_opacity', 50 );
			$opacity    = $opacity / 100;
			$text_color = Color::get_custom_header_stack_text_color();
			$bg_color   = Color::get_custom_header_stack_bg_color( $opacity );
			$css[]      = $this->add_media_query(
				".custom-header--full .site-header,
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
				",
				'md'
			);
		}

		/**
		 * カスタマイザープレビュー用
		 */
		if ( is_customize_preview() ) {
			$css[] = '
			.customize-partial-edit-shortcut-custom_header {
			  top: 0;
			  left: 0;
			}';
		}

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
