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
	 * ブレークポイント
	 *
	 * @var array
	 */
	private $breakpoints = array(
		'md' => 600,
		'lg' => 1025,
	);

	/**
	 * インラインCSSの取得
	 */
	public function get_inline_css() {
		$ys_scripts = ys_scripts();
		$styles     = array();
		/**
		 * フォント
		 */
		$styles[] = $this->get_font_css();
		/**
		 * サイト背景色
		 */
		$styles[] = $this->get_site_css();
		/**
		 * ヘッダーロゴ
		 */
		$styles[] = $this->get_header_logo_css();
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
		/**
		 * カスタマイズプレビュー
		 */
		$styles[] = $this->get_customize_preview();

		$inline_css = implode( '', $styles );

		return $ys_scripts->minify( apply_filters( 'ys_get_inline_css', $inline_css ) );
	}

	/**
	 * フォントCSSを取得
	 *
	 * @param string|array $selector セレクタ.
	 *
	 * @return string
	 */
	public static function get_font_css( $selector = 'body' ) {
		$font_family = 'sans-serif';
		$font        = array(
			'meihirago' => '"Helvetica neue", "Segoe UI", "Hiragino Sans", "Hiragino Kaku Gothic ProN", "Meiryo", sans-serif',
			'yugo'      => 'Avenir, "Segoe UI", "游ゴシック体", YuGothic, "游ゴシック Medium", "Yu Gothic Medium", sans-serif',
			'serif'     => 'serif',
		);

		$option = YS_Option::get_option( 'ys_design_font_type', 'yugo' );
		if ( isset( $font[ $option ] ) ) {
			$font_family = $font[ $option ];
		}

		if ( is_array( $selector ) ) {
			$selector = implode( ',', $selector );
		}

		return sprintf(
			'%s{font-family:%s;}',
			$selector,
			apply_filters( 'ys_inline_font_family', $font_family, $option )
		);
	}

	/**
	 * ブロックエディターフォントサイズ指定CSS
	 *
	 * @param string $prefix  プレフィックス.
	 * @param int    $default デフォルトフォントサイズ.
	 *
	 * @return string
	 */
	public static function get_editor_font_size_css( $prefix = '', $default = 16 ) {
		$default = apply_filters( 'ys_default_editor_font_size', $default );
		$size    = ys_get_editor_font_sizes();
		$css     = '';
		$prefix  = empty( $prefix ) ? '' : $prefix . ' ';
		foreach ( $size as $value ) {
			$fz   = ( $value['size'] / $default );
			$slug = $value['slug'];
			/**
			 * CSS作成
			 */
			$css .= $prefix . '.has-' . $slug . '-font-size{font-size:' . $fz . 'em;}';
		}

		return $css;
	}

	/**
	 * ブロックエディター色設定
	 *
	 * @param string $prefix プレフィックス.
	 *
	 * @return string
	 */
	public static function get_editor_color_palette( $prefix = '' ) {
		$color  = YS_Color::get_color_palette();
		$css    = '';
		$prefix = empty( $prefix ) ? '' : $prefix . ' ';
		foreach ( $color as $value ) {
			/**
			 * Background-color
			 */
			$css .= $prefix . '.has-' . $value['slug'] . '-background-color,
			.has-background.has-' . $value['slug'] . '-background-color{
				background-color:' . $value['color'] . ';
				border-color:' . $value['color'] . ';
			}';
			/**
			 * Text Color
			 */
			$css .= $prefix . '.has-' . $value['slug'] . '-color,
			.has-text-color.has-' . $value['slug'] . '-color,
			.has-' . $value['slug'] . '-color:hover,
			.has-text-color.has-' . $value['slug'] . '-color:hover{
				color:' . $value['color'] . ';
			}';
			/**
			 * ブロック（アウトライン）
			 */
			$css .= $prefix . '
			.is-style-outline .wp-block-button__link.has-' . $value['slug'] . '-color{
				border-color:' . $value['color'] . ';
				color:' . $value['color'] . ';
			}';
			/**
			 * ブロック（アウトライン）hover
			 */
			$css .= $prefix . '
			.is-style-outline .wp-block-button__link:hover.has-' . $value['slug'] . '-color{
				background-color:' . $value['color'] . ';
				border-color:' . $value['color'] . ';
			}';
		}

		return $css;
	}

	/**
	 * サイト全体CSS
	 *
	 * @return string
	 */
	public function get_site_css() {
		if ( ys_get_option_by_bool( 'ys_desabled_color_customizeser', false ) ) {
			return '';
		}
		$html_bg = YS_Color::get_site_bg();
		$styles  = array();
		/**
		 * サイト背景色
		 */
		$styles[] = "
		body {
			background-color:${html_bg};
		}";
		/**
		 * 背景色がデフォルト以外の場合
		 */
		if ( ys_has_site_background() ) {
			if ( is_singular() ) {
				if ( ! ys_is_full_width() ) {
					$styles[] = '
					.has-bg-color:not(.one-column-no-title) .site__content {
						margin-top:2rem;
					}
					.has-bg-color:not(.one-column-no-title) .breadcrumbs + .site__content {
						margin-top:0;
					}';
					/**
					 * フル幅以外
					 */
					$styles[] = '
					.has-bg-color .content__main {
						padding:2rem 1rem;
						margin:0 -1rem;
					}
					.has-bg-color.one-column-no-title .content__main {
						padding-top: 2rem;
					}';
					if ( ! ys_is_amp() ) {
						$styles[] = $this->add_media_query(
							'.has-bg-color.has-sidebar .content__wrap {
								-webkit-box-flex: 0;
							    -ms-flex: 0 0 calc(100% - 368px - 2rem);
							    flex: 0 0 calc(100% - 368px - 2rem);
							    max-width: calc(100% - 368px - 2rem);
							}
							.has-bg-color .content__main {
								padding:2rem;
								margin:0;
							}',
							'lg'
						);
					}
				} else {
					/**
					 * フル幅
					 */
					$styles[] = '
					.has-bg-color.full-width:not(.one-column-no-title) .site__content {
						padding-top:1rem;
						margin-bottom:2rem;
					}';
				}
				/**
				 * パンくず
				 */
				$styles[] = '
				.has-bg-color .site__content + .breadcrumbs {
					margin-top:-1rem;
				}';
			} else {
				$styles[] = '
				.has-bg-color .site__content {
					margin-top:2rem;
				}
				.has-bg-color .breadcrumbs + .site__content {
					margin-top:1rem;
				}';
				/**
				 * アーカイブなど
				 */
				$styles[] = '
				.has-bg-color .content__main{
					background-color:transparent;
				}';

				if ( 'list' === ys_get_option( 'ys_archive_type', 'list' ) ) {
					$styles[] = '
					.has-bg-color .archive__item.-list {
						background-color:#fff;
						margin-bottom:1rem;
					}';
					$styles[] = $this->add_media_query(
						'.has-bg-color .archive__item.-list {
							margin-bottom:2rem;
						}',
						'lg'
					);
				}
			}
			/**
			 * サイドバー
			 */
			if ( ys_is_active_sidebar_widget() ) {
				$styles[] = '
				.has-bg-color .sidebar-wrapper {
					margin:0 -1rem;
				}
				.has-bg-color .sidebar__widget > div,
				.has-bg-color .sidebar__fixed > div {
					padding:2rem 1rem;
					margin-bottom: 2rem;
					background-color:#fff;
				}';
				if ( ! ys_is_amp() ) {
					$styles[] = $this->add_media_query(
						'.has-bg-color .sidebar-wrapper {margin:0;}',
						'lg'
					);
				}
			}
		}

		return implode( '', $styles );
	}

	/**
	 * ヘッダーロゴCSS
	 *
	 * @return string
	 */
	public function get_header_logo_css() {

		$styles = array();

		if ( 0 < ys_get_option_by_int( 'ys_logo_width_sp', 0 ) ) {
			$styles[] = sprintf(
				'.header__title img{width:%spx;}',
				ys_get_option_by_int( 'ys_logo_width_sp', 0 )
			);
		}
		if ( 0 < ys_get_option_by_int( 'ys_logo_width_pc', 0 ) ) {
			$styles[] = $this->add_media_query(
				sprintf(
					'.header__title img{width:%spx;}',
					ys_get_option_by_int( 'ys_logo_width_pc', 0 )
				),
				'md'
			);
		}

		return implode( '', $styles );
	}

	/**
	 * ヘッダーCSS
	 *
	 * @return string
	 */
	public function get_header_css() {
		$styles      = array();
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
						  padding-top:calc(32px + ${pt_pc});
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

			if ( is_customize_preview() ) {
				$styles[] = '
				.header-height-info {position: absolute;top:0;left:0;padding:.25em 1em;background-color:rgba(0,0,0,.7);font-size:.7rem;color:#fff;z-index:99;
				}';
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
		$styles               = array();
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
		$styles      = array();
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
		$css = array();
		/**
		 * ヘッダー重ねるタイプ
		 */
		if ( ys_get_option_by_bool( 'ys_wp_header_media_full', false ) ) {
			$opacity    = ys_get_option_by_int( 'ys_wp_header_media_full_opacity', 50 );
			$opacity    = $opacity / 100;
			$text_color = '#fff';
			$bg_color   = 'rgba(0,0,0,' . $opacity . ')';
			if ( 'light' === ys_get_option( 'ys_wp_header_media_full_type', 'dark' ) ) {
				$text_color = '#222';
				$bg_color   = 'rgba(255,255,255,' . $opacity . ')';
			}
			$css[] = $this->add_media_query(
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

	/**
	 * カスタマイズプレビュー用
	 *
	 * @return string
	 */
	public function get_customize_preview() {
		$css = array();

		if ( ys_get_option_by_bool( 'ys_show_sidebar_mobile', false ) ) {
			$css[] = $this->add_media_query(
				'.is-customize-preview .sidebar {display:none;}',
				'md',
				'max'
			);
		}
		if ( ! ys_get_option_by_bool( 'ys_show_search_form_on_slide_menu', false ) ) {
			$css[] = $this->add_media_query(
				'.is-customize-preview .h-nav__search {display:none;}',
				'lg',
				'max'
			);
		}

		return implode( '', $css );
	}

	/**
	 * メディアクエリを追加
	 *
	 * @param string $css        Styles.
	 * @param string $breakpoint Breakpoint.
	 * @param string $type       Type.
	 *
	 * @return string
	 */
	public function add_media_query( $css, $breakpoint, $type = 'min' ) {

		/**
		 * 切り替え位置取得
		 */
		if ( isset( $this->breakpoints[ $breakpoint ] ) ) {
			$breakpoint = $this->breakpoints[ $breakpoint ];
			if ( 'max' === $type ) {
				$breakpoint = $breakpoint - 0.1;
			}
		}
		/**
		 * 以上・以下判断
		 */
		if ( 'min' !== $type && 'max' !== $type ) {
			return $css;
		}

		return sprintf(
			'@media screen and (%s-width: %spx) {%s}',
			$type,
			$breakpoint,
			$css
		);
	}
}
