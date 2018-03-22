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
	ys_set_inline_style( ys_customizer_inline_css() );
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
		wp_add_inline_style( 'ys-style-full', ys_customizer_inline_css() );
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
		'ys-custom_uploader-scripts',
		get_template_directory_uri() . '/js/admin/custom_uploader.js',
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