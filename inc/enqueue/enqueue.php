<?php
/**
 * スクリプトの読み込み
 *
 * @package ystandard
 * @author yosiakatsuki
 * @license GPL-2.0+
 */

if ( ! function_exists( 'ys_set_inline_style' ) ) {
	/**
	 * インラインCSSセット
	 *
	 * @param string  $style インラインCSS.
	 * @param boolean $minify minifyするかどうか.
	 * @return void
	 */
	function ys_set_inline_style( $style, $minify = true ) {
		global $ys_enqueue;
		$ys_enqueue->set_inline_style( $style, $minify );
	}
}

/**
 * インラインスタイルのセットと出力
 *
 * @return void
 */
function ys_inline_styles() {
	if ( ! ys_is_optimize_load_css() ) {
		return;
	}
	/**
	 * インラインCSSのセット
	 */
	ys_set_inline_style( get_template_directory() . '/css/ys-firstview.min.css', true );
	ys_set_inline_style( ys_get_customizer_inline_css() );
	ys_set_inline_style( locate_template( 'style-firstview.css' ) );
	/**
	 * インラインCSSの出力
	 */
	ys_the_inline_style();
}
add_action( 'wp_head', 'ys_inline_styles', 5 );


/**
 * インラインCSS取得
 *
 * @param bool $is_amp AMPかどうか.
 */
function ys_get_the_inline_style( $is_amp ) {
	global $ys_enqueue;
	$style = $ys_enqueue->get_inline_style( $is_amp );
	return apply_filters( 'ys_get_the_inline_style', $style );
}


if ( ! function_exists( 'ys_the_inline_style' ) ) {
	/**
	 * インラインCSS出力
	 */
	function ys_the_inline_style() {
		$style = ys_get_the_inline_style( ys_is_amp() );
		if ( ys_is_amp() ) {
			$style = sprintf( '<style amp-custom>%s</style>', $style );
		} else {
			$style = sprintf( '<style id="ystandard-inline-style">%s</style>', $style );
		}
		echo $style . PHP_EOL;
	}
}

/**
 * JSが使えなかった時のお助け処理
 *
 * @return void
 */
function ys_the_noscript_fallback() {
	if ( ! ys_is_optimize_load_css() ) {
		return;
	}
	$styles = '';
	global $ys_enqueue;
	$list = $ys_enqueue->get_non_critical_css_list();
	if ( empty( $list ) ) {
		return;
	}
	foreach ( $list as $item ) {
		$styles .= sprintf( '<link rel="stylesheet" href="%s">', $item );
	}
	echo sprintf( '<noscript>%s</noscript>', $styles );
}
add_action( 'wp_head', 'ys_the_noscript_fallback', 999 );

/**
 * JavaScriptの読み込み
 *
 * @return void
 */
function ys_enqueue_scripts() {
	/**
	 * JavaScript
	 */
	if ( ys_is_load_cdn_jquery() ) {
		wp_enqueue_script( 'jquery', ys_get_option( 'ys_load_cdn_jquery_url' ) );
	}
	/**
	 * IE,Edge関連
	 */
	if ( ys_is_ie() || ys_is_edge() ) {
		/**
		 * Object-fit
		 */
		wp_enqueue_script(
			'object-fit-images',
			get_template_directory_uri() . '/library/object-fit-images/ofi.min.js'
		);
		/**
		 * Sticky
		 */
		wp_enqueue_script(
			'stickyfill',
			get_template_directory_uri() . '/library/stickyfill/stickyfill.min.js'
		);
		/**
		 * Polyfill関連の実行処理など
		 */
		wp_enqueue_script(
			'ys-polyfill',
			get_template_directory_uri() . '/js/polyfill.bundle.js',
			array( 'object-fit-images', 'stickyfill' )
		);
	}
	/**
	 * テーマのjs読み込む
	 */
	wp_enqueue_script(
		'ystandard-scripts',
		get_template_directory_uri() . '/js/ystandard.bundle.js',
		array(),
		ys_get_theme_version( true ),
		true
	);
}
add_action( 'wp_enqueue_scripts', 'ys_enqueue_scripts' );

/**
 * CSSの読み込み
 *
 * @return void
 */
function ys_enqueue_styles() {
	/**
	 * IE,Edge関連
	 */
	if ( ys_is_ie() || ys_is_edge() ) {
		wp_enqueue_style(
			'ys_ie',
			get_template_directory_uri() . '/css/ys-ie.min.css',
			array(),
			ys_get_theme_version( true )
		);
	}
	/**
	 * CSS最適化しない場合、通常形式でCSSの読み込み
	 */
	if ( ! ys_is_optimize_load_css() ) {
		wp_enqueue_style(
			'ys-style-full',
			get_template_directory_uri() . '/css/ys-style-full.min.css',
			array(),
			ys_get_theme_version( true )
		);
		wp_add_inline_style( 'ys-style-full', ys_get_customizer_inline_css() );
		wp_enqueue_style(
			'style-css',
			get_stylesheet_directory_uri() . '/style.css',
			array( 'ys-style-full' ),
			ys_get_theme_version( true )
		);
		wp_enqueue_style(
			'font-awesome',
			'https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css',
			array(),
			'4.7.0'
		);
	}
}
add_action( 'wp_enqueue_scripts', 'ys_enqueue_styles' );

/**
 * CSS追加読み込みの指定
 */
function ys_enqueue_styles_non_critical_css() {
	if ( ! ys_is_optimize_load_css() ) {
		return;
	}
	/**
	 * 読み込むCSSを指定する
	 */
	ys_enqueue_non_critical_css(
		'ys-style',
		get_template_directory_uri() . '/css/ys-style.min.css',
		ys_get_theme_version( true )
	);
	ys_enqueue_non_critical_css(
		'style-css',
		get_stylesheet_directory_uri() . '/style.css',
		ys_get_theme_version()
	);
	/**
	 * キャッシュが効いたりするのでCDN読み込みにする
	 * get_template_directory_uri() . '/library/font-awesome/css/font-awesome.min.css',
	 */
	ys_enqueue_non_critical_css(
		'font-awesome',
		'https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css',
		'4.7.0'
	);
}
add_action( 'wp_enqueue_scripts', 'ys_enqueue_styles_non_critical_css' );

/**
 * CSS追加読み込み（ファーストビュー以外のCSSをJavaScriptで読み込みする）
 *
 * @return void
 */
function ys_the_load_non_critical_css() {
	if ( ! ys_is_optimize_load_css() ) {
		return;
	}
	/**
	 * CSS出力
	 */
	ys_the_non_critical_css();
}
add_action( 'wp_footer', 'ys_the_load_non_critical_css' );

/**
 * Non-critical-cssのセット
 *
 * @param string  $id id.
 * @param string  $src src.
 * @param boolean $ver version.
 * @return void
 */
function ys_enqueue_non_critical_css( $id, $src, $ver = false ) {
	global $ys_enqueue;
	$ys_enqueue->set_non_critical_css( $id, $src, $ver );
}
/**
 * 追加読み込みスクリプト・CSSの追加(onload)
 *
 * @param string $tag tag.
 * @param string $handle handle.
 * @param string $src src.
 * @return string
 */
function ys_the_onload_scripts( $tag, $handle, $src ) {
	global $ys_enqueue;

	/**
	 * Twitter関連スクリプト読み込み
	 */
	if ( ys_get_option( 'ys_load_script_twitter' ) ) {
		ys_enqueue_onload_script( 'twitter-wjs', '//platform.twitter.com/widgets.js' );
	}
	/**
	 * Facebook関連スクリプト読み込み
	 */
	if ( ys_get_option( 'ys_load_script_facebook' ) ) {
		ys_enqueue_onload_script( 'facebook-jssdk', '//connect.facebook.net/ja_JP/sdk.js#xfbml=1&version=v2.11' );
	}

	if ( 'ystandard-scripts' !== $handle ) {
		return $tag;
	}
	/**
	 * Data属性の作成
	 */
	$onload_scripts   = $ys_enqueue->get_onload_script_attr();
	$lazyload_scripts = $ys_enqueue->get_lazyload_script_attr();
	$lazyload_css     = $ys_enqueue->get_lazyload_css_attr();
	/**
	 * メインのJavaScriptに属性追加
	 */
	$data = $onload_scripts . ' ' . $lazyload_scripts . ' ' . $lazyload_css;
	return str_replace( 'src', $data . ' id="ys-main-script" src', $tag );
}
add_action( 'script_loader_tag', 'ys_the_onload_scripts', 10, 3 );

/**
 * Onload-scriptのセット
 *
 * @param [type]  $id id.
 * @param [type]  $src src.
 * @param boolean $ver version.
 * @return void
 */
function ys_enqueue_onload_script( $id, $src, $ver = false ) {
	global $ys_enqueue;
	$ys_enqueue->set_onload_script( $id, $src, $ver );
}
/**
 * Lazyload-scriptのセット
 *
 * @param string  $id id.
 * @param string  $src src.
 * @param boolean $ver version.
 * @return void
 */
function ys_enqueue_lazyload_script( $id, $src, $ver = false ) {
	global $ys_enqueue;
	$ys_enqueue->set_lazyload_script( $id, $src, $ver );
}

/**
 * Lazyload-cssのセット
 *
 * @param string  $id id.
 * @param string  $src src.
 * @param boolean $ver version.
 * @return void
 */
function ys_enqueue_lazyload_css( $id, $src, $ver = false ) {
	global $ys_enqueue;
	$ys_enqueue->set_lazyload_css( $id, $src, $ver );
}
/**
 * Non-critical-cssの出力
 *
 * @return void
 */
function ys_the_non_critical_css() {
	global $ys_enqueue;
	echo $ys_enqueue->get_non_critical_css();
}


/**
 * テーマカスタマイザーでの色指定 CSS取得
 *
 * @return string
 */
function ys_get_customizer_inline_css() {
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
			'.color__font-sub',
			'input:placeholder-shown',
			'.sidebar .widget_recent_entries a:not(:hover)',
			'.sidebar .widget_categories a:not(:hover)',
			'.sidebar .widget_archive a:not(:hover)',
			'.sidebar .widget_nav_menu a:not(:hover)',
			'.sidebar .widget_pages a:not(:hover)',
			'.sidebar .widget_meta a:not(:hover)',
			'.sidebar .widget_calendar a:not(:hover)',
			'.sidebar .widget_ys_ranking a:not(:hover)',
			'.sidebar .widget_ys_taxonomy_posts a:not(:hover)',
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
			'.color__site-dscr',
		),
		array(
			'color' => $header_font,
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
			'.global-nav__sub-menu li',
		),
		array(
			'background-color' => ys_customizer_get_color_option( 'ys_color_nav_bg_pc' ),
		)
	);
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

	return apply_filters( 'ys_customize_css', $css );
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
 * セレクタとプロパティをくっつけてCSS作る
 *
 * @param  array $selectors セレクタ.
 * @param  array $propertys プロパティ.
 * @return string
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
 *
 * @param  string $name 設定名.
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
 * @return string
 */
function ys_customizer_get_default_color( $setting_name ) {
			$default_colors = ys_customizer_get_defaults();
			return $default_colors[ $setting_name ];
}

/**
 * 色のデフォルト値一覧取得
 */
function ys_customizer_get_defaults() {
	return array(
		'ys_color_site_bg'       => '#ffffff',
		'ys_color_site_font'     => '#222222',
		'ys_color_site_font_sub' => '#939393',
		'ys_color_header_bg'     => '#ffffff',
		'ys_color_header_font'   => '#222222',
		'ys_color_nav_bg_pc'     => '#ffffff',
		'ys_color_nav_font_pc'   => '#939393',
		'ys_color_nav_bg_sp'     => '#292b2c',
		'ys_color_nav_font_sp'   => '#ffffff',
		'ys_color_footer_bg'     => '#292b2c',
		'ys_color_footer_font'   => '#ffffff',
	);
}

/**
 * スタイルシート・スクリプトの読み込み停止
 */
function ys_enqueue_deregister() {
	/**
	 * WordPressのjqueryを停止
	 */
	if ( ys_is_deregister_jquery() ) {
		wp_deregister_script( 'jquery' );
	}
}
add_action( 'wp_enqueue_scripts', 'ys_enqueue_deregister', 9 );

/**
 * 管理画面-JavaScriptの読み込み
 *
 * @param string $hook_suffix suffix.
 * @return void
 */
function ys_enqueue_admin_scripts( $hook_suffix ) {
	/**
	 * メディアアップローダ
	 */
	wp_enqueue_media();
	wp_enqueue_script(
		'ys-admin-scripts',
		get_template_directory_uri() . '/js/admin.bundle.js',
		array( 'jquery', 'jquery-core' ),
		ys_get_theme_version( true ),
		true
	);
}
add_action( 'admin_enqueue_scripts', 'ys_enqueue_admin_scripts' );

/**
 * 管理画面-スタイルシートの読み込み
 *
 * @param string $hook_suffix suffix.
 * @return void
 */
function ys_admin_enqueue_scripts( $hook_suffix ) {
	wp_enqueue_style(
		'ys_admin_style',
		get_template_directory_uri() . '/css/admin/admin.min.css',
		array(),
		ys_get_theme_version( true )
	);
	/**
	 * テーマ独自の設定ページ
	 */
	if ( 'toplevel_page_ys_settings_start' === $hook_suffix ) {
		wp_enqueue_style(
			'ys_settings_style',
			get_template_directory_uri() . '/css/admin/ystandard-settings.min.css'
		);
		wp_enqueue_style(
			'ys_settings_font',
			'https://fonts.googleapis.com/css?family=Orbitron'
		);
	}
}
add_action( 'admin_enqueue_scripts', 'ys_admin_enqueue_scripts' );

/**
 * 管理画面-テーマカスタマイザーページでのスタイルシートの読み込み
 *
 * @param string $hook_suffix suffix.
 * @return void
 */
function ys_enqueue_customizer_styles( $hook_suffix ) {
	wp_enqueue_style(
		'ys_customizer_style',
		get_template_directory_uri() . '/css/customizer/customizer.min.css',
		array(),
		ys_get_theme_version( true )
	);
}
add_action( 'customize_controls_print_styles', 'ys_enqueue_customizer_styles' );

/**
 * テーマカスタマイザー用JS
 *
 * @return void
 */
function ys_enqueue_customize_controls_js() {
		wp_enqueue_script(
			'ys_customize_controls_js',
			get_template_directory_uri() . '/js/admin/customizer-control.js',
			array( 'customize-controls', 'jquery' ),
			ys_get_theme_version( true ),
			true
		);
}
add_action( 'customize_controls_enqueue_scripts', 'ys_enqueue_customize_controls_js' );

if ( ! function_exists( 'ys_script_loader_tag' ) ) {
	/**
	 * 出力される script 要素を加工
	 *
	 * @param string $tag tag.
	 * @param string $handle handle.
	 * @param string $src src.
	 * @return string
	 */
	function ys_script_loader_tag( $tag, $handle, $src ) {
		if ( is_admin() ) {
			return $tag;
		}
		/**
		 * 属性削除 : type
		 */
		$tag = str_replace( "type='text/javascript'", '', $tag );
		$tag = str_replace( 'type="text/javascript"', '', $tag );
		/**
		 * ファイル名にjqueryが付くものは除外
		 */
		if ( false !== strpos( $tag, 'jquery' ) ) {
			return $tag;
		}
		/**
		 * 非同期読み込みを除外
		 */
		$exclude = apply_filters( 'ys_exclude_add_async_scripts', array() );
		if ( ! empty( $exclude ) ) {
			if ( in_array( $handle, $exclude ) ) {
				return $tag;
			}
		}
		if ( 'ystandard-scripts' === $handle || ys_get_option( 'ys_option_optimize_load_js' ) ) {
			$tag = str_replace( 'src', 'async defer src', $tag );
		}
		return $tag;
	}
}
add_filter( 'script_loader_tag', 'ys_script_loader_tag', 10, 3 );

if ( ! function_exists( 'ys_style_loader_tag' ) ) {
	/**
	 * 出力されるcssロード用link要素を加工
	 *
	 * @param string $html html.
	 * @param string $handle handle.
	 * @param string $href href.
	 * @return string
	 */
	function ys_style_loader_tag( $html, $handle, $href ) {
		if ( is_admin() ) {
			return $html;
		}
		/**
		 * 属性削除 : type.
		 */
		$html = str_replace( "type='text/css'", '', $html );
		$html = str_replace( 'type="text/css"', '', $html );
		return $html;
	}
}
add_filter( 'style_loader_tag', 'ys_style_loader_tag', 10, 3 );