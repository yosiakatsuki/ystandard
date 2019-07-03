<?php
/**
 * カスタマイザーで出力するCSS作成:色
 *
 * @package ystandard
 * @author  yosiakatsuki
 * @license GPL-2.0+
 */

/**
 * テーマカスタマイザーでの色指定 CSS取得
 *
 * @return string
 */
function ys_get_customizer_inline_css_color() {
	if ( ys_get_option( 'ys_desabled_color_customizeser' ) ) {
		return '';
	}
	/**
	 * 設定取得
	 */
	$html_bg         = ys_get_option( 'ys_color_site_bg' );
	$header_bg       = ys_get_option( 'ys_color_header_bg' );
	$header_font     = ys_get_option( 'ys_color_header_font' );
	$header_dscr     = ys_get_option( 'ys_color_header_dscr_font' );
	$mobile_nav_bg   = ys_get_option( 'ys_color_nav_bg_sp' );
	$mobile_nav_font = ys_get_option( 'ys_color_nav_font_sp' );
	$mobile_nav_btn  = ys_get_option( 'ys_color_nav_btn_sp' );
	$footer_bg       = ys_get_option( 'ys_color_footer_bg' );
	$footer_font     = ys_get_option( 'ys_color_footer_font' );

	$css = '';
	/**
	 * サイト背景色
	 */
	$css .= ys_customizer_create_inline_css(
		array(
			'body',
		),
		array(
			'background-color' => $html_bg,
		)
	);
	/**
	 * 背景色がデフォルト以外の場合
	 */
	if ( ys_get_option_default( 'ys_color_site_bg' ) !== $html_bg ) {
		/**
		 * 追加CSS
		 */
		$css_temp = '';
		if ( ! ys_is_full_width() ) {
			$css      .= ys_customizer_create_inline_css(
				array(
					'.content__main',
				),
				array(
					'padding' => '2rem 1rem',
					'margin'  => '0 -1rem',
				)
			);
			$css_temp .= ys_customizer_create_inline_css(
				array(
					'.content__main',
				),
				array(
					'padding' => '2rem',
					'margin'  => '0',
				)
			);
			$css_temp .= ys_customizer_create_inline_css(
				array(
					'.has-sidebar .content__main',
				),
				array(
					'margin-right' => '0',
				)
			);
		} else {
			$css .= ys_customizer_create_inline_css(
				array(
					'.full-width:not(.one-column-no-title) .site__content',
				),
				array(
					'margin-top' => '1rem',
				)
			);
		}
		/**
		 * パンくず調整
		 */
		$css .= ys_customizer_create_inline_css(
			array(
				'.breadcrumbs',
			),
			array(
				'padding' => '1rem 0 0',
			)
		);
		/**
		 * サイドバーあり
		 */
		if ( ys_is_active_sidebar_widget() ) {
			/**
			 * PC
			 */
			$css      .= ys_customizer_create_inline_css(
				array(
					'.sidebar-wrapper',
				),
				array(
					'background-color' => '#fff',
					'padding'          => '2rem 1rem',
					'margin'           => '0 -1rem',
				)
			);
			$css_temp .= ys_customizer_create_inline_css(
				array(
					'.sidebar-wrapper',
				),
				array(
					'margin' => '0',
				)
			);
		}
		/**
		 * PC
		 */
		if ( ! ys_is_mobile() ) {
			$css .= ys_customizer_add_media_query( $css_temp, 'lg' );
		}
	}
	/**
	 * ヘッダー 背景色
	 */
	$css .= ys_customizer_create_inline_css(
		array(
			'.site-header',
		),
		array(
			'background-color' => $header_bg,
		)
	);
	/**
	 * ヘッダー 背景色 PC
	 */
	if ( ! ys_is_mobile() ) {
		$css .= ys_customizer_add_media_query(
			ys_customizer_create_inline_css(
				array(
					'.h-nav.rwd li:hover ul',
				),
				array(
					'background-color' => $header_bg,
					'opacity'          => '.9',
				)
			),
			'lg'
		);
	}
	/**
	 * ヘッダー文字色
	 */
	$css .= ys_customizer_create_inline_css(
		array(
			'.header__title',
			'.header__title a',
		),
		array(
			'color' => $header_font,
		)
	);
	/**
	 * ヘッダーナビゲーション PC
	 */
	if ( ! ys_is_mobile() ) {
		$css .= ys_customizer_add_media_query(
			ys_customizer_create_inline_css(
				array(
					'.h-nav.rwd .h-nav__main a',
				),
				array(
					'color' => $header_font,
				)
			),
			'lg'
		);
	}

	/**
	 * ヘッダー概要文字色（テキストの場合のみ）
	 */
	$css .= ys_customizer_create_inline_css(
		array(
			'.header__dscr',
		),
		array(
			'color' => $header_dscr,
		)
	);

	/**
	 * モバイルナビゲーション 背景
	 */
	$css .= ys_customizer_add_media_query(
		ys_customizer_create_inline_css(
			array(
				'.h-nav__main',
			),
			array(
				'background-color' => $mobile_nav_bg,
			)
		),
		'lg',
		'max'
	);
	/**
	 * モバイルナビゲーション 文字
	 */
	$css .= ys_customizer_add_media_query(
		ys_customizer_create_inline_css(
			array(
				'.h-nav__main a',
				'.h-nav__search input',
				'.h-nav__search button',
			),
			array(
				'color' => $mobile_nav_font,
			)
		),
		'lg',
		'max'
	);
	$css .= ys_customizer_add_media_query(
		ys_customizer_create_inline_css(
			array(
				'.h-nav__search input',
				'.h-nav__search button',
			),
			array(
				'border-color' => $mobile_nav_font,
			)
		),
		'lg',
		'max'
	);
	$css .= ys_customizer_add_media_query(
		ys_customizer_create_inline_css(
			array(
				'.h-nav__main a',
				'.h-nav__search input',
				'.h-nav__search button',
			),
			array(
				'color' => $mobile_nav_font,
			)
		),
		'lg',
		'max'
	);
	/**
	 * モバイルナビゲーション 閉じるボタン
	 */
	$css .= ys_customizer_add_media_query(
		ys_customizer_create_inline_css(
			array(
				'#h-nav__toggle:checked~.h-nav__btn .hamburger span',
			),
			array(
				'background-color' => $mobile_nav_btn,
			)
		),
		'lg',
		'max'
	);
	/**
	 * フッターカラー
	 */
	$css .= ys_customizer_create_inline_css(
		array(
			'.site__footer',
		),
		array(
			'background-color' => $footer_bg,
		)
	);
	$css .= ys_customizer_create_inline_css(
		array(
			'.site__footer',
			'.site__footer a, .site__footer a:hover',
		),
		array(
			'color' => $footer_font,
		)
	);
	/**
	 * フッターSNSリンク
	 */
	$css .= ys_customizer_get_footer_sns_css();

	return apply_filters( 'ys_get_customizer_inline_css_color', $css );
}

/**
 * フッターSNSリンク色設定
 */
function ys_customizer_get_footer_sns_css() {
	$css = '';
	/**
	 * 不透明度の計算
	 */
	$footer_sns_opacity       = get_option( 'ys_color_footer_sns_bg_opacity', 30 );
	$footer_sns_opacity       = ( $footer_sns_opacity / 100 );
	$footer_sns_hover_opacity = ( $footer_sns_opacity + 0.2 );
	if ( 1 < ( $footer_sns_opacity + 0.2 ) ) {
		$footer_sns_hover_opacity = 1;
	}
	if ( 'light' === get_option( 'ys_color_footer_sns_bg_type', 'light' ) ) {
		$footer_sns_color       = 'rgba(255,255,255,' . $footer_sns_opacity . ')';
		$footer_sns_hover_color = 'rgba(255,255,255,' . $footer_sns_hover_opacity . ')';
	} else {
		$footer_sns_color       = 'rgba(0,0,0,' . $footer_sns_opacity . ')';
		$footer_sns_hover_color = 'rgba(0,0,0,' . $footer_sns_hover_opacity . ')';
	}
	$css .= ys_customizer_create_inline_css(
		array(
			'.footer-sns__link',
		),
		array(
			'background-color' => $footer_sns_color,
		)
	);
	$css .= ys_customizer_create_inline_css(
		array(
			'.footer-sns__link:hover',
		),
		array(
			'background-color' => $footer_sns_hover_color,
		)
	);

	return $css;
}

/**
 * カスタマイザー設定のCSSにメディアクエリを追加
 *
 * @param string $css        Styles.
 * @param string $breakpoint Breakpoint.
 * @param string $type       Breakpoint type(min or max).
 *
 * @return string
 */
function ys_customizer_add_media_query( $css, $breakpoint, $type = 'min' ) {
	/**
	 * ブレークポイント
	 */
	$breakpoints = array(
		'md' => 600,
		'lg' => 1025,
	);
	/**
	 * 切り替え位置取得
	 */
	if ( isset( $breakpoints[ $breakpoint ] ) ) {
		$breakpoint = $breakpoints[ $breakpoint ];
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