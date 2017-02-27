<?php
//------------------------------------------------------------------------------
//
//	テーマカスタマイザー
//
//------------------------------------------------------------------------------




//-----------------------------------------------
//	カスタマイザーコア機能の書き換え
//-----------------------------------------------
if (!function_exists( 'ys_customizer')) {
	function ys_customizer($wp_customize) {
		/**
		 * 設定の削除
		 */

		// サイトアイコン削除
		ys_customizer_delete_site_icon($wp_customize);

		/**
		 * 設定の追加
		 */

		// apple touch icon
		ys_customizer_add_apple_touch_icon($wp_customize);

		// 色変更
		ys_customizer_color_setting($wp_customize);

	}
}
add_action('customize_register', 'ys_customizer');




//-----------------------------------------------
//	サイトアイコンの削除
//-----------------------------------------------
if (!function_exists( 'ys_customizer_delete_site_icon')) {
	function ys_customizer_delete_site_icon($wp_customize) {
		//
		$wp_customize->remove_setting('background_color');
		$wp_customize->remove_section('colors');
	}
}




//-----------------------------------------------
//	apple touch icon設定追加
//-----------------------------------------------
if (!function_exists( 'ys_customizer_add_apple_touch_icon')) {
	function ys_customizer_add_apple_touch_icon($wp_customize) {

		// サイトアイコンの説明を変更
		$wp_customize->get_control('site_icon')->description = sprintf(
			'ファビコン用の画像を設定して下さい。縦横%spx以上である必要があります。',
			'<strong>512</strong>'
		);

		$wp_customize->add_setting( 'ys_apple_touch_icon', array(
			'type'       => 'option',
			'capability' => 'manage_options',
			'transport'  => 'postMessage', // Previewed with JS in the Customizer controls window.
		) );

		$wp_customize->add_control( new WP_Customize_Site_Icon_Control( $wp_customize, 'ys_apple_touch_icon', array(
			'label'       => 'apple touch icon',
			'description' => sprintf(
				'apple touch icon用の画像を設定して下さい。縦横%spx以上である必要があります。',
				'<strong>512</strong>'
			),
			'section'     => 'title_tagline',
			'priority'    => 61,
			'height'      => 512,
			'width'       => 512,
		) ) );
	}
}




/**
 *
 *	色変更
 *
 */
if (!function_exists( 'ys_customizer_color_setting')) {
	function ys_customizer_color_setting($wp_customize){

		// パネルの追加
		$wp_customize->add_panel(
											'ys_color_panel',
											array(
												'priority'       => 40,
												'title'          => '色'
											)
										);

		/**
		 *	サイト全体色
		 */
		$wp_customize->add_section(
											'ys_color_site',
											array(
												'title' => 'サイト全体',
												'panel' => 'ys_color_panel',
												'priority' => 1,
											)
										);
		// サイト背景色
		ys_customizer_add_colorsetting(
			$wp_customize,
			array(
				'section'  => 'ys_color_site',
				'settings' => 'ys_color_site_bg',
				'default' => '#fff',
				'label' => 'サイト背景色'
			)
		);

		// サイト文字色(メイン)
		ys_customizer_add_colorsetting(
			$wp_customize,
			array(
				'section'  => 'ys_color_site',
				'settings' => 'ys_color_site_font',
				'default' => '#2c3e50',
				'label' => 'サイト文字色（メイン）'
			)
		);
		// サイト文字色（薄字）
		ys_customizer_add_colorsetting(
			$wp_customize,
			array(
				'section'  => 'ys_color_site',
				'settings' => 'ys_color_site_font_sub',
				'default' => '#888',
				'label' => 'サイト文字色（薄字）'
			)
		);


		/**
		 *	ヘッダー色
		 */
		$wp_customize->add_section(
											'ys_color_header',
											array(
												'title' => 'ヘッダー',
												'panel' => 'ys_color_panel',
												'priority' => 2,
											)
										);
		// ヘッダー背景色
		ys_customizer_add_colorsetting(
			$wp_customize,
			array(
				'section'  => 'ys_color_header',
				'settings' => 'ys_color_header_bg',
				'default' => '#fafafa',
				'label' => 'ヘッダー背景色'
			)
		);

		// ヘッダー文字色
		ys_customizer_add_colorsetting(
			$wp_customize,
			array(
				'section'  => 'ys_color_header',
				'settings' => 'ys_color_header_font',
				'default' => '#2c3e50',
				'label' => 'ヘッダー文字色'
			)
		);

		/**
		 *	ナビゲーション色
		 */
		$wp_customize->add_section(
											'ys_color_nav',
											array(
												'title' => 'グローバルナビ',
												'panel' => 'ys_color_panel',
												'priority' => 3,
											)
										);

		// ナビゲーション文字色（PC）
		ys_customizer_add_colorsetting(
			$wp_customize,
			array(
				'section'  => 'ys_color_nav',
				'settings' => 'ys_color_nav_font_pc',
				'default' => '#888',
				'label' => '[PC]ナビゲーション文字色'
			)
		);

		// ナビゲーションホバー時の下線（PC）
		ys_customizer_add_colorsetting(
			$wp_customize,
			array(
				'section'  => 'ys_color_nav',
				'settings' => 'ys_color_nav_border_pc',
				'default' => '#888',
				'label' => '[PC]ナビゲーションホバー時の下線色'
			)
		);

		// ナビゲーション背景色（SP）
		ys_customizer_add_colorsetting(
			$wp_customize,
			array(
				'section'  => 'ys_color_nav',
				'settings' => 'ys_color_nav_bg_sp',
				'default' => '#2c3e50',
				'label' => '[SP]ナビゲーション背景色'
			)
		);


		// ナビゲーション文字色（SP）
		ys_customizer_add_colorsetting(
			$wp_customize,
			array(
				'section'  => 'ys_color_nav',
				'settings' => 'ys_color_nav_font_sp',
				'default' => '#fff',
				'label' => '[SP]ナビゲーション文字色'
			)
		);

		// ナビゲーション区切り線（SP）
		// ys_customizer_add_colorsetting(
		// 	$wp_customize,
		// 	array(
		// 		'section'  => 'ys_color_nav',
		// 		'settings' => 'ys_color_nav_border_sp',
		// 		'default' => '#fff',
		// 		'label' => '[SP]ナビゲーション区切り線'
		// 	)
		// );


		/**
		 *	フッター色
		 */
		$wp_customize->add_section(
											'ys_color_footer',
											array(
												'title' => 'フッター',
												'panel' => 'ys_color_panel',
												'priority' => 4,
											)
										);

		// フッター背景色
		ys_customizer_add_colorsetting(
			$wp_customize,
			array(
				'section'  => 'ys_color_footer',
				'settings' => 'ys_color_footer_bg',
				'default' => '#2c3e50',
				'label' => 'フッター背景色'
			)
		);

		// フッターSNSアイコン背景色
		ys_customizer_add_colorsetting(
			$wp_customize,
			array(
				'section'  => 'ys_color_footer',
				'settings' => 'ys_color_footer_sns_bg',
				'default' => '#3d4f61',
				'label' => 'フッターSNSアイコン背景色'
			)
		);
		// フッターSNSアイコン背景色 hover
		ys_customizer_add_colorsetting(
			$wp_customize,
			array(
				'section'  => 'ys_color_footer',
				'settings' => 'ys_color_footer_sns_hover_bg',
				'default' => '#5f7183',
				'label' => 'フッターSNSアイコン hover 背景色'
			)
		);

		// フッター文字色
		ys_customizer_add_colorsetting(
			$wp_customize,
			array(
				'section'  => 'ys_color_footer',
				'settings' => 'ys_color_footer_font',
				'default' => '#fff',
				'label' => 'フッター文字色'
			)
		);

	}
}




/**
 *
 *	テーマカスタマイザーでの色指定
 *
 */
if( ! function_exists( 'ys_customizer_inline_css' )){
	function ys_customizer_inline_css() {

		if(ys_get_setting('ys_desabled_color_customizeser') == true){
			return '';
		}

		/**
		 *	設定取得
		 */
		$html_bg = get_option('ys_color_site_bg','#fff');
		$html_font = get_option('ys_color_site_font','#2c3e50');
		$html_font_sub = get_option('ys_color_site_font_sub','#888');

		$header_bg = get_option('ys_color_header_bg','#fafafa');
		$header_font = get_option('ys_color_header_font','#2c3e50');

		$nav_font_pc = get_option('ys_color_nav_font_pc','#888');
		$nav_border_pc = get_option('ys_color_nav_border_pc','#888');
		$nav_bg_sp = get_option('ys_color_nav_bg_sp','#2c3e50');
		$nav_font_sp = get_option('ys_color_nav_font_sp','#fff');
		// $nav_border_sp = get_option('ys_color_nav_border_sp','#fff');

		$footer_bg = get_option('ys_color_footer_bg','#2c3e50');
		$footer_sns_bg = get_option('ys_color_footer_sns_bg','#3d4f61');
		$footer_sns_hover_bg = get_option('ys_color_footer_sns_hover_bg','#5f7183');
		$footer_font = get_option('ys_color_footer_font','#fff');

		$css = '';

		/**
		 * 背景色
		 */

		//サイト背景色
		$selectors = array(
									'body'
								);
		$css .= implode(",", $selectors)."{background-color:{$html_bg};}";


		//  文字色を使っている部分
		$selectors = array(
									'body, html',
									'.entry-title',
									'.pagination-list li .current,.pagination-list li a',
									'.page-links a .page-text',
									'.author-title a, .widget .author-title a',
									'.post-navigation .nav-next a, .post-navigation .nav-prev a',
									'.post-navigation .home a, .post-navigation .home a:hover'
								);
		$css .= implode(",", $selectors)."{color:{$html_font};}";


		$selectors = array(
									'.pagination-list .next, .pagination-list .previous',
									'.page-links .page-text',
									'.comment-form input[type=submit]'
								);
		$css .= implode(",", $selectors)."{background-color:{$html_font};}";

		$selectors = array(
									'.pagination-list li .current',
									'.page-links .page-text',
									'.comment-form input[type=submit]'
								);
		$css .= implode(",", $selectors)."{border-color:{$html_font};}";


		// 薄文字部分
		$selectors = array(
									'.breadcrumb ol li a',
									'.breadcrumb ol li::after',
									'.entry-meta',
									'.entry-excerpt',
									'.post-navigation .next-label, .post-navigation .prev-label',
									'.search-field:placeholder-shown',
									'.sidebar-right a',
									'.entry-category-list a, .entry-tag-list a'
								);
		$css .= implode(",", $selectors)."{color:{$html_font_sub};}";

		// プレースホルダ
		$selectors = array('.search-field::-webkit-input-placeholder');
		$css .= implode(",", $selectors)."{color:{$html_font_sub};}";
		$selectors = array('.search-field:-ms-input-placeholder');
		$css .= implode(",", $selectors)."{color:{$html_font_sub};}";
		$selectors = array('.search-field::-moz-placeholder');
		$css .= implode(",", $selectors)."{color:{$html_font_sub};}";


		$selectors = array(
									'.search-field',
									'.entry-category-list a, .entry-tag-list a'
								);
		$css .= implode(",", $selectors)."{border-color:{$html_font_sub};}";

		$selectors = array(
									'.search-submit svg',
									'.entry-meta svg'
								);
		$css .= implode(",", $selectors)."{fill:{$html_font_sub};}";


		/**
		 * ヘッダーカラー
		 */
		//  背景色
		$selectors = array('.color-site-header');
		$css .= implode(",", $selectors)."{background-color:{$header_bg};}";
		// 文字色（テキストの場合のみ）
		$selectors = array(
										'.color-site-header',
										'.color-site-title, .color-site-title:hover',
										'.color-site-dscr'
									);
		$css .= implode(",", $selectors)."{color:{$header_font};}";


		/**
		 * ナビゲーションカラー
		 */
		// SP Only
		$css .= '@media screen and (max-width: 959px) {';
		// 背景
		$selectors = array(
										'.site-header-menu'
									);
		$css .= implode(",", $selectors)."{background-color:{$nav_bg_sp};}";

		// 文字
		$selectors = array(
										'.gloval-menu>li a'
									);
		$css .= implode(",", $selectors)."{color:{$nav_font_sp};}";
		$css .= '}';// SP Only END

		// PC Only
		$css .= '@media screen and (min-width: 960px) {';
		// 背景
		$selectors = array(
										'.gloval-menu>li:hover a',
										'.gloval-menu>li:hover.menu-item-has-children a:hover'
									);
		$css .= implode(",", $selectors)."{border-color:{$nav_border_pc};}";

		// 文字
		$selectors = array(
										'.gloval-menu>li a'
									);
		$css .= implode(",", $selectors)."{color:{$nav_font_pc};}";
		$css .= '}';// SP Only END


		/**
		 * フッターカラー
		 */
		$selectors = array(
										'.site-footer'
									);
		$css .= implode(",", $selectors)."{background-color:{$footer_bg};}";

		$selectors = array(
										'.site-footer',
										'.site-footer a, .site-footer a:hover'
									);
		$css .= implode(",", $selectors)."{color:{$footer_font};}";

		$selectors = array(
										'.follow-sns-list a'
									);
		$css .= implode(",", $selectors)."{background-color:{$footer_sns_bg};}";
		$selectors = array(
										'.follow-sns-list a:hover'
									);
		$css .= implode(",", $selectors)."{background-color:{$footer_sns_hover_bg};}";


		return apply_filters('ys_customize_css',$css);
	}
}




/**
 *	色設定追加
 */
function ys_customizer_add_colorsetting($wp_customize,$args) {

	$wp_customize->add_setting(
										$args['settings'],
										array(
											'default' => $args['default'],
											'type' => 'option',
											'sanitize_callback' => 'sanitize_hex_color'
										)
									);

	$wp_customize->add_control(
										new WP_Customize_Color_Control(
																				$wp_customize,
																				$args['settings'],
																				array(
																						'label' => $args['label'],
																						'section'  => $args['section'],
																						'settings' => $args['settings'],
																				) ) );

}




?>