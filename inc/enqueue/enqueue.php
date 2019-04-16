<?php
/**
 * スクリプトの読み込み
 *
 * @package ystandard
 * @author  yosiakatsuki
 * @license GPL-2.0+
 */

/**
 * JavaScriptの読み込み
 *
 * @return void
 */
function ys_enqueue_scripts() {
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
	/**
	 * アイコンフォント JS読み込み方式
	 */
	if ( 'js' === ys_get_option( 'ys_enqueue_icon_font_type' ) ) {
		/**
		 * Font Awesomeの読み込み
		 */
		wp_enqueue_script(
			'font-awesome-js',
			ys_get_font_awesome_js_url(),
			array(),
			ys_get_font_awesome_js_ver(),
			true
		);
		wp_add_inline_script(
			'font-awesome-js',
			'FontAwesomeConfig = { searchPseudoElements: true };',
			'before'
		);
	}
}

add_action( 'wp_enqueue_scripts', 'ys_enqueue_scripts' );

/**
 * CSSの読み込み
 *
 * @return void
 */
function ys_enqueue_styles() {
	/**
	 * ブロックのスタイルを削除
	 */
	if ( ! ys_is_active_gutenberg_css() ) {
		wp_dequeue_style( 'wp-block-library' );
		wp_dequeue_style( 'wp-block-library-theme' );
	}
	/**
	 * テーマのCSSを読み込み
	 */
	if ( ys_is_optimize_load_css() ) {
		/**
		 * CSS最適化する場合、インラインでCSS読み込み
		 */
		ys_inline_styles();
	} else {
		/**
		 * CSS最適化しない場合、通常形式でCSSの読み込み
		 */
		ys_enqueue_styles_normal();
	}
}

add_action( 'wp_enqueue_scripts', 'ys_enqueue_styles' );

/**
 * インラインスタイルのセットと出力
 *
 * @return void
 */
function ys_inline_styles() {
	/**
	 * インラインCSSのセット
	 */
	ys_set_inline_style( get_template_directory() . '/css/ys-firstview.min.css', false );
	if ( ys_is_use_background_color() ) {
		ys_set_inline_style( get_template_directory() . '/css/ys-use-bgc.min.css', false );
	}
	ys_set_inline_style( ys_get_customizer_inline_css() );
	if ( ys_is_active_gutenberg_css() ) {
		ys_set_inline_style( get_template_directory() . '/css/ys-wp-blocks.min.css', false );
	}
	ys_set_inline_style( locate_template( 'style-firstview.css' ) );
	/**
	 * インラインCSSの出力
	 */
	ys_the_inline_style();
}

/**
 * 通常のCSS読み込み
 */
function ys_enqueue_styles_normal() {
	/**
	 * 完全版CSSの読み込み
	 */
	wp_enqueue_style(
		'ys-style-full',
		get_template_directory_uri() . '/css/ys-style-full.min.css',
		array(),
		ys_get_theme_version( true )
	);
	/**
	 * ブロックエディタのCSS
	 */
	if ( ys_is_active_gutenberg_css() ) {
		wp_enqueue_style(
			'ys-style-block',
			get_template_directory_uri() . '/css/ys-wp-blocks.min.css',
			array(),
			ys_get_theme_version( true )
		);
	}

	/**
	 * カスタマイザーで設定変更可能なインラインCSSを追加
	 */
	wp_add_inline_style( 'ys-style-full', ys_get_customizer_inline_css() );
	/**
	 * 背景色を使う場合は調整用CSSを読み込む
	 */
	if ( ys_is_use_background_color() ) {
		wp_enqueue_style(
			'ys-style-use-bgc',
			get_template_directory_uri() . '/css/ys-use-bgc.min.css',
			array(),
			ys_get_theme_version( true )
		);
	}
	/**
	 * Style.cssの読み込み（ユーザーカスタマイズ用）
	 */
	wp_enqueue_style(
		'style-css',
		get_stylesheet_directory_uri() . '/style.css',
		array( 'ys-style-full' ),
		ys_get_theme_version( true )
	);
	/**
	 * CSS読み込み方式のみCSS読み込み
	 */
	if ( 'css' === ys_get_option( 'ys_enqueue_icon_font_type' ) ) {
		/**
		 * Font Awesomeの読み込み
		 */
		wp_enqueue_style(
			'font-awesome',
			ys_get_font_awesome_url(),
			array(),
			''
		);
	}
}

/**
 * IE,Edge関連のスクリプト・CSS読み込み
 */
function ys_enqueue_scripts_ie_edge() {
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
		/**
		 * IE,Edge用調整CSS
		 */
		wp_enqueue_style(
			'ys_ie',
			get_template_directory_uri() . '/css/ys-ie.min.css',
			array(),
			ys_get_theme_version( true )
		);
	}
}

add_action( 'wp_enqueue_scripts', 'ys_enqueue_scripts_ie_edge' );

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
	 * CSS読み込み方式のみCSS読み込み
	 */
	if ( 'css' === ys_get_option( 'ys_enqueue_icon_font_type' ) ) {
		/**
		 * キャッシュが効いたりするのでCDN読み込みにする
		 * get_template_directory_uri() . '/library/font-awesome/css/font-awesome.min.css',
		 */
		ys_enqueue_non_critical_css(
			'font-awesome',
			ys_get_font_awesome_url(),
			''
		);
	}
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