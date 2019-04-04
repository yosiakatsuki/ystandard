<?php
/**
 * カスタマイザーで出力するCSS作成:色
 *
 * @package ystandard
 * @author  yosiakatsuki
 * @license GPL-2.0+
 */

/**
 * 色のデフォルト値一覧取得
 */
function ys_customizer_get_defaults() {
	return array(
		'ys_color_site_bg'          => '#ffffff',
		'ys_color_site_font'        => '#222222',
		'ys_color_site_font_sub'    => '#757575',
		'ys_color_header_bg'        => '#ffffff',
		'ys_color_header_font'      => '#222222',
		'ys_color_header_dscr_font' => '#939393',
		'ys_color_nav_bg_pc'        => '#ffffff',
		'ys_color_nav_font_pc'      => '#939393',
		'ys_color_nav_bg_sp'        => '#292b2c',
		'ys_color_nav_btn_sp'       => '#292b2c',
		'ys_color_nav_font_sp'      => '#ffffff',
		'ys_color_footer_bg'        => '#292b2c',
		'ys_color_footer_font'      => '#ffffff',
	);
}

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
	$html_bg       = ys_customizer_get_color_option( 'ys_color_site_bg' );
	$html_font     = ys_customizer_get_color_option( 'ys_color_site_font' );
	$html_font_sub = ys_customizer_get_color_option( 'ys_color_site_font_sub' );
	$header_bg     = ys_customizer_get_color_option( 'ys_color_header_bg' );
	$header_font   = ys_customizer_get_color_option( 'ys_color_header_font' );
	$footer_bg     = ys_customizer_get_color_option( 'ys_color_footer_bg' );
	$footer_font   = ys_customizer_get_color_option( 'ys_color_footer_font' );

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
			'color'            => $html_font,
		)
	);
	/**
	 * 背景色がデフォルト以外の場合
	 */
	if ( ys_customizer_get_default_color( 'ys_color_site_bg' ) !== $html_bg ) {
		$css_temp = '';
		/**
		 * 追加CSS
		 */
		$css_temp .= ys_customizer_create_inline_css(
			array(
				'.content__wrap',
			),
			array(
				'background-color' => '#fff',
				'padding'          => '1rem 2rem',
			)
		);
		/**
		 * PC only
		 */
		$css .= ys_customizer_add_media_query( $css_temp, 'lg' );
	}

	/**
	 * 文字色を使っている部分
	 */
	$css .= ys_customizer_create_inline_css(
		array(
			'.color__font-main',
		),
		array(
			'color' => $html_font,
		)
	);
	/**
	 * 文字色を使っている部分
	 */
	$css .= ys_customizer_create_inline_css(
		array(
			'.color__bg-main',
		),
		array(
			'background-color' => $html_font,
		)
	);
	/**
	 * 薄文字部分
	 */
	$css .= ys_customizer_create_inline_css(
		array(
			'.text-sub',
			'.text-sub a',
			'a.text-sub',
			'.text-sub a:hover',
			'a.text-sub:hover',
		),
		array(
			'color' => $html_font_sub,
		)
	);

	/**
	 * プレースホルダ
	 */
	$css .= ys_customizer_create_inline_css(
		array(
			'input::-webkit-input-placeholder',
		),
		array(
			'color' => $html_font_sub,
		)
	);
	$css .= ys_customizer_create_inline_css(
		array(
			'input:-ms-input-placeholder',
		),
		array(
			'color' => $html_font_sub,
		)
	);
	$css .= ys_customizer_create_inline_css(
		array(
			'input::-moz-placeholder',
		),
		array(
			'color' => $html_font_sub,
		)
	);

	$css .= ys_customizer_create_inline_css(
		array(
			'.search-field',
		),
		array(
			'border-color' => $html_font_sub,
		)
	);
	/**
	 * ***********************************
	 * ヘッダーカラー
	 * ***********************************
	 */
	/**
	 * 背景色
	 */
	$css .= ys_customizer_create_inline_css(
		array(
			'.color__site-header',
		),
		array(
			'background-color' => $header_bg,
		)
	);
	/**
	 * 文字色（テキストの場合のみ）
	 */
	$css .= ys_customizer_create_inline_css(
		array(
			'.color__site-header',
			'.color__site-title, .color__site-title:hover',
		),
		array(
			'color' => $header_font,
		)
	);
	/**
	 * 概要文字色（テキストの場合のみ）
	 */
	$css .= ys_customizer_create_inline_css(
		array(
			'.color__site-dscr',
		),
		array(
			'color' => ys_customizer_get_color_option( 'ys_color_header_dscr_font' ),
		)
	);

	/**
	 * ナビゲーションカラー
	 */
	/**
	 * SP Only
	 */
	$css .= '@media screen and (max-width: 959px) {';
	/**
	 * 背景
	 */
	$css .= ys_customizer_create_inline_css(
		array(
			'.color__nav-bg--sp',
		),
		array(
			'background-color' => ys_customizer_get_color_option( 'ys_color_nav_bg_sp' ),
		)
	);
	/**
	 * 文字
	 */
	$css .= ys_customizer_create_inline_css(
		array(
			'.color__nav-font--sp',
		),
		array(
			'color' => ys_customizer_get_color_option( 'ys_color_nav_font_sp' ),
		)
	);
	/**
	 * ボタン
	 */
	$css .= ys_customizer_create_inline_css(
		array(
			'.global-nav__btn span',
		),
		array(
			'background-color' => ys_customizer_get_color_option( 'ys_color_nav_btn_sp' ),
		)
	);
	/**
	 * ボタン（OPEN）
	 */
	$css .= ys_customizer_create_inline_css(
		array(
			'#header__nav-toggle:checked~.global-nav__btn span',
		),
		array(
			'background-color' => ys_customizer_get_color_option( 'ys_color_nav_font_sp' ),
		)
	);
	$css .= '}';

	/**
	 * PC Only
	 */
	$css .= '@media screen and (min-width: 960px) {';
	/**
	 * 背景
	 */
//	$css .= ys_customizer_create_inline_css(
//		array(
//			'.color__nav-bg--pc',
//			'.global-nav__sub-menu li',
//		),
//		array(
//			'background-color' => ys_customizer_get_color_option( 'ys_color_nav_bg_pc' ),
//		)
//	);
	/**
	 * 文字
	 */
	$css .= ys_customizer_create_inline_css(
		array(
			'.color__nav-font--pc',
		),
		array(
			'color' => ys_customizer_get_color_option( 'ys_color_nav_font_pc' ),
		)
	);
	/**
	 * 文字下線
	 */
	$css .= ys_customizer_create_inline_css(
		array(
			'.global-nav__item:not(.menu-item-has-children):hover',
		),
		array(
			'border-bottom' => '1px solid ' . ys_customizer_get_color_option( 'ys_color_nav_font_pc' ),
		)
	);
	$css .= '}';
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
 * カスタマイザーの色設定取得
 *
 * @param  string $name 設定名.
 *
 * @return string
 */
function ys_customizer_get_color_option( $name ) {
	return get_option(
		$name,
		ys_customizer_get_default_color( $name )
	);
}

/**
 * 色デフォルト値取得
 *
 * @param  string $setting_name 色のデフォルト値取得.
 *
 * @return string
 */
function ys_customizer_get_default_color( $setting_name ) {
	$default_colors = ys_customizer_get_defaults();

	return $default_colors[ $setting_name ];
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
		'md' => '600px',
		'lg' => '1025px',
	);
	/**
	 * 切り替え位置取得
	 */
	if ( isset( $breakpoints[ $breakpoint ] ) ) {
		$breakpoint = $breakpoints[ $breakpoint ];
	}
	/**
	 * 以上・以下判断
	 */
	if ( 'min' != $type && 'max' != $type ) {
		return $css;
	}

	return sprintf(
		'@media screen and (%s-width: %s) {%s}',
		$type,
		$breakpoint,
		$css
	);
}