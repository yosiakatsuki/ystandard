<?php
/**
 *
 *	色変更
 *
 */
function ys_customizer_color( $wp_customize ){
	/**
	 * パネルの追加
	 */
	$wp_customize->add_panel(
										'ys_customizer_panel_color',
										array(
											'priority'       => 40,
											'title'          => '色'
										)
									);

	/**
	 * サイト全体
	 */
	ys_customizer_add_site_color( $wp_customize );

	/**
	 * ヘッダー
	 */
	ys_customizer_add_header_color( $wp_customize );

	/**
	 * ナビゲーション
	 */
	ys_customizer_add_global_nav_color( $wp_customize );

	/**
	 * フッター
	 */
	 ys_customizer_add_footer_color( $wp_customize );
}

/**
 * サイト全体の色
 */
function ys_customizer_add_site_color( $wp_customize ){
	/**
	 *	サイト全体色
	 */
	$wp_customize->add_section(
										'ys_color_site',
										array(
											'title'    => 'サイト全体',
											'panel'    => 'ys_customizer_panel_color',
											'priority' => 1,
										)
									);
	/**
	 * サイト背景色
	 */
	ys_customizer_add_setting_color(
		$wp_customize,
		array(
			'section' => 'ys_color_site',
			'id'      => 'ys_color_site_bg',
			'default' => ys_customizer_get_default_color( 'ys_color_site_bg' ),
			'label'   => 'サイト背景色'
		)
	);

	/**
	 * サイト文字色(メイン)
	 */
	ys_customizer_add_setting_color(
		$wp_customize,
		array(
			'section' => 'ys_color_site',
			'id'      => 'ys_color_site_font',
			'default' => ys_customizer_get_default_color('ys_color_site_font'),
			'label'   => 'サイト文字色（メイン）'
		)
	);
	/**
	 * サイト文字色（グレー）
	 */
	ys_customizer_add_setting_color(
		$wp_customize,
		array(
			'section' => 'ys_color_site',
			'id'      => 'ys_color_site_font_sub',
			'default' => ys_customizer_get_default_color('ys_color_site_font_sub'),
			'label'   => 'サイト文字色（グレー）'
		)
	);
}

/**
 *	ヘッダー色
 */
function ys_customizer_add_header_color( $wp_customize ){
		/**
		 *	ヘッダー色
		 */
		$wp_customize->add_section(
											'ys_color_header',
											array(
												'title'    => 'ヘッダー',
												'panel'    => 'ys_customizer_panel_color',
												'priority' => 2,
											)
										);
		// ヘッダー背景色
		ys_customizer_add_setting_color(
			$wp_customize,
			array(
				'section' => 'ys_color_header',
				'id'      => 'ys_color_header_bg',
				'default' => ys_customizer_get_default_color( 'ys_color_header_bg' ),
				'label'   => 'ヘッダー背景色'
			)
		);

		// ヘッダー文字色
		ys_customizer_add_setting_color(
			$wp_customize,
			array(
				'section' => 'ys_color_header',
				'id'      => 'ys_color_header_font',
				'default' => ys_customizer_get_default_color( 'ys_color_header_font' ),
				'label'   => 'ヘッダー文字色'
			)
		);
}

/**
 *	ナビゲーション色
 */
function ys_customizer_add_global_nav_color( $wp_customize ){
		/**
		 *	ナビゲーション色
		 */
		$wp_customize->add_section(
											'ys_color_nav',
											array(
												'title'    => 'グローバルナビゲーション',
												'panel'    => 'ys_customizer_panel_color',
												'priority' => 3,
											)
										);

		/**
		 * ナビゲーション背景色（PC）
		 */
		ys_customizer_add_setting_color(
			$wp_customize,
			array(
				'section' => 'ys_color_nav',
				'id'      => 'ys_color_nav_bg_pc',
				'default' => ys_customizer_get_default_color( 'ys_color_nav_bg_pc' ),
				'label'   => '[PC]ナビゲーション背景色'
			)
		);
		/**
		 * ナビゲーション文字色（PC）
		 */
		ys_customizer_add_setting_color(
			$wp_customize,
			array(
				'section' => 'ys_color_nav',
				'id'      => 'ys_color_nav_font_pc',
				'default' => ys_customizer_get_default_color( 'ys_color_nav_font_pc' ),
				'label'   => '[PC]ナビゲーション文字色'
			)
		);

		/**
		 * ナビゲーション背景色（SP）
		 */
		ys_customizer_add_setting_color(
			$wp_customize,
			array(
				'section' => 'ys_color_nav',
				'id'      => 'ys_color_nav_bg_sp',
				'default' => ys_customizer_get_default_color( 'ys_color_nav_bg_sp' ),
				'label'   => '[SP]ナビゲーション背景色'
			)
		);


		/**
		 * ナビゲーション文字色（SP）
		 */
		ys_customizer_add_setting_color(
			$wp_customize,
			array(
				'section' => 'ys_color_nav',
				'id'      => 'ys_color_nav_font_sp',
				'default' => ys_customizer_get_default_color( 'ys_color_nav_font_sp' ),
				'label'   => '[SP]ナビゲーション文字色'
			)
		);
}

/**
 *	フッター
 */
function ys_customizer_add_footer_color( $wp_customize ){
		/**
		 *	フッター色
		 */
		$wp_customize->add_section(
											'ys_color_footer',
											array(
												'title'    => 'フッター',
												'panel'    => 'ys_customizer_panel_color',
												'priority' => 4,
											)
										);

		/**
		 * フッター背景色
		 */
		ys_customizer_add_setting_color(
			$wp_customize,
			array(
				'section' => 'ys_color_footer',
				'id'      => 'ys_color_footer_bg',
				'default' => ys_customizer_get_default_color( 'ys_color_footer_bg' ),
				'label'   => 'フッター背景色'
			)
		);

		/**
		 * フッターSNSアイコン背景色タイプ
		 */
		ys_customizer_add_setting_radio(
			$wp_customize,
			array(
				'section' => 'ys_color_footer',
				'id'      => 'ys_color_footer_sns_bg_type',
				'default' => 'light',
				'label'   => 'フッターSNSアイコン背景色',
				'choices' => array(
					'light' => 'ライト',
					'dark'  => 'ダーク'
				)
			)
		);

		/**
		 * フッターSNSアイコン背景色不透明度
		 */
		ys_customizer_add_setting_number(
			$wp_customize,
			array(
				'section'     => 'ys_color_footer',
				'id'          => 'ys_color_footer_sns_bg_opacity',
				'default'     => 30,
				'label'       => 'フッターSNSアイコン背景色の不透明度',
				'description' => '0~100の間で入力して下さい',
				'input_attrs' => array(
													'min'  => 0,
													'max'  => 100,
													'size' => 20
												)
			)
		);

		/**
		 * フッター文字色
		 */
		ys_customizer_add_setting_color(
			$wp_customize,
			array(
				'section' => 'ys_color_footer',
				'id'      => 'ys_color_footer_font',
				'default' => ys_customizer_get_default_color( 'ys_color_footer_font' ),
				'label'   => 'フッター文字色'
			)
		);
}





/**
 *
 *	テーマカスタマイザーでの色指定
 *
 */
function ys_customizer_inline_css() {

	if( ys_get_option('ys_desabled_color_customizeser') ){
		return '';
	}

	/**
	 *	設定取得
	 */
	$html_bg = ys_customizer_get_color_option( 'ys_color_site_bg' );
	$html_font = ys_customizer_get_color_option( 'ys_color_site_font' );
	$html_font_sub = ys_customizer_get_color_option( 'ys_color_site_font_sub' );
	$header_bg = ys_customizer_get_color_option( 'ys_color_header_bg' );
	$header_font = ys_customizer_get_color_option( 'ys_color_header_font' );
	$footer_bg = ys_customizer_get_color_option( 'ys_color_footer_bg' );
	$footer_font = ys_customizer_get_color_option( 'ys_color_footer_font' );

	$css = '';
	/**
	 * サイト背景色
	 */
	$css .= ys_customizer_create_inline_css(
						array(
							'body'
						),
						array(
							'background-color' => $html_bg,
							'color' => $html_font
						)
					);

	/**
	 * 文字色を使っている部分
	 */
	$css .= ys_customizer_create_inline_css(
						array(
							'.color__font-main'
						),
						array(
							'color' => $html_font
						)
					);
	/**
	 * 文字色を使っている部分
	 */
	$css .= ys_customizer_create_inline_css(
						array(
							'.pagination-list .next, .pagination-list .previous',
							'.page-links .page-text',
							'.comment-form input[type=submit]',
							'.pagination-list li .current',
							'.page-links .page-text',
							'.comment-form input[type=submit]'
						),
						array(
							'background-color' => $html_font
						)
					);


	/**
	 * 薄文字部分
	 */
	$css .= ys_customizer_create_inline_css(
						array(
							'.color__font-sub',
							'input:placeholder-shown',
							'.sidebar .widget_recent_entries a',
							'.sidebar .widget_categories a',
							'.sidebar .widget_archive a',
							'.sidebar .widget_nav_menu a',
							'.sidebar .widget_pages a',
							'.sidebar .widget_meta a',
							'.sidebar .widget_calendar a',
							'.sidebar .widget_ys_widgets_ranking a',
						),
						array(
							'color' => $html_font_sub
						)
					);

	/**
	 * プレースホルダ
	 */
	$css .= ys_customizer_create_inline_css(
						array(
							'input::-webkit-input-placeholder'
						),
						array(
							'color' => $html_font_sub
						)
					);
	$css .= ys_customizer_create_inline_css(
						array(
							'input:-ms-input-placeholder'
						),
						array(
							'color' => $html_font_sub
						)
					);
	$css .= ys_customizer_create_inline_css(
						array(
							'input::-moz-placeholder'
						),
						array(
							'color' => $html_font_sub
						)
					);

	$css .= ys_customizer_create_inline_css(
						array(
							'.search-field',
							'.entry-category-list a, .entry-tag-list a'
						),
						array(
							'border-color' => $html_font_sub
						)
					);
	$css .= ys_customizer_create_inline_css(
						array(
							'.search-submit svg',
							'.entry-meta svg'
						),
						array(
							'fill' => $html_font_sub
						)
					);

	/**
	 * ヘッダーカラー
	 */

	/**
	 * 背景色
	 */
	$css .= ys_customizer_create_inline_css(
						array(
							'.color__site-header'
						),
						array(
							'background-color' => $header_bg
						)
					);
	/**
	 * 文字色（テキストの場合のみ）
	 */
	$css .= ys_customizer_create_inline_css(
						array(
							'.color__site-header',
							'.color__site-title, .color__site-title:hover',
							'.color__site-dscr'
						),
						array(
							'color' => $header_font
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
							'.color__nav-bg--sp'
						),
						array(
							'background-color' => ys_customizer_get_color_option( 'ys_color_nav_bg_sp' )
						)
					);
	/**
	 * 文字
	 */
	$css .= ys_customizer_create_inline_css(
						array(
							'.color__nav-font--pc'
						),
						array(
							'color' => ys_customizer_get_color_option( 'ys_color_nav_font_sp' )
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
	$css .= ys_customizer_create_inline_css(
		array(
			'.color__nav-bg--pc',
			'.global-nav__sub-menu li'
		),
		array(
			'background-color' => ys_customizer_get_color_option( 'ys_color_nav_bg_pc' )
		)
	);
	/**
	* 文字
	*/
	$css .= ys_customizer_create_inline_css(
		array(
			'.color__nav-font--pc'
		),
		array(
			'color' => ys_customizer_get_color_option( 'ys_color_nav_font_pc' )
		)
	);
	/**
	* 文字下線
	*/
	$css .= ys_customizer_create_inline_css(
		array(
			'.global-nav__item:not(.menu-item-has-children):hover'
		),
		array(
			'border-bottom' => '1px solid ' . ys_customizer_get_color_option( 'ys_color_nav_font_pc' )
		)
	);
	$css .= '}';


	/**
	 * フッターカラー
	 */
	$css .= ys_customizer_create_inline_css(
						array(
							'.site__footer'
						),
						array(
							'background-color' => $footer_bg
						)
					);
	$css .= ys_customizer_create_inline_css(
						array(
							'.site__footer',
							'.site__footer a, .site__footer a:hover'
						),
						array(
							'color' => $footer_font
						)
					);
	/**
	 * フッターSNSリンク
	 */
	$css .= ys_customizer_get_footer_sns_css();

	return apply_filters( 'ys_customize_css', $css );
}

/**
 * フッターSNSリンク色設定
 */
function ys_customizer_get_footer_sns_css() {
	$css = '';
	$footer_sns_opacity = get_option( 'ys_color_footer_sns_bg_opacity', 0.3 );
	$footer_sns_opacity = ( $footer_sns_opacity / 100 );
	$footer_sns_hover_opacity = 1 < ( $footer_sns_opacity + 0.2 ) ? 1 : ( $footer_sns_opacity + 0.2 );
	if( 'light' === get_option( 'ys_color_footer_sns_bg_type', 'light' ) ){
		$footer_sns_color = 'rgba(255,255,255,' . $footer_sns_opacity . ')';
		$footer_sns_hover_color = 'rgba(255,255,255,' . $footer_sns_hover_opacity . ')';
	} else {
		$footer_sns_color = 'rgba(0,0,0,' . $footer_sns_opacity . ')';
		$footer_sns_hover_color = 'rgba(0,0,0,' . $footer_sns_hover_opacity . ')';
	}
	$css .= ys_customizer_create_inline_css(
						array(
							'.footer-sns__link'
						),
						array(
							'background-color' => $footer_sns_color
						)
					);
	$css .= ys_customizer_create_inline_css(
						array(
							'.footer-sns__link:hover'
						),
						array(
							'background-color' => $footer_sns_hover_color
						)
					);
	return $css;
}

/**
 * セレクタとプロパティをくっつけてCSS作る
 */
function ys_customizer_create_inline_css( $selectors, $propertys ) {
	$property = '';
	foreach ( $propertys as $key => $value ) {
		$property .= $key . ':' . $value . ';';
	}
	return implode( ',', $selectors ) . '{' . $property . '}';
}

/**
 * カスタマイザーの色設定取得
 */
function ys_customizer_get_color_option( $name ) {
	return get_option(
						$name,
						ys_customizer_get_default_color( $name )
					);
}

/**
 *	色デフォルト値取得
 */
function ys_customizer_get_default_color( $setting_name ){
			$default_colors = ys_customizer_get_defaults();
			return $default_colors[$setting_name];
}

/**
 * 色のデフォルト値一覧取得
 */
function ys_customizer_get_defaults() {
	return array(
						'ys_color_site_bg'             =>'#ffffff',
						'ys_color_site_font'           =>'#222222',
						'ys_color_site_font_sub'       =>'#939393',
						'ys_color_header_bg'           =>'#ffffff',
						'ys_color_header_font'         =>'#222222',
						'ys_color_nav_bg_pc'           =>'#ffffff',
						'ys_color_nav_font_pc'         =>'#939393',
						'ys_color_nav_bg_sp'           =>'#292b2c',
						'ys_color_nav_font_sp'         =>'#ffffff',
						'ys_color_footer_bg'           =>'#292b2c',
						'ys_color_footer_font'         =>'#ffffff'
					);
}