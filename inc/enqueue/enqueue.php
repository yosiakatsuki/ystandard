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
		'ystandard-script',
		get_template_directory_uri() . '/js/ystandard.js',
		array(),
		ys_get_theme_version( true ),
		true
	);
	/**
	 * Font Awesome
	 */
	wp_enqueue_script(
		'font-awesome',
		ys_get_font_awesome_svg_url(),
		array(),
		ys_get_font_awesome_svg_version(),
		true
	);
	wp_add_inline_script(
		'font-awesome',
		'FontAwesomeConfig = { searchPseudoElements: true };',
		'before'
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
	ys_set_inline_style( ys_get_customizer_inline_css() );
	if ( ys_is_active_gutenberg_css() ) {
		ys_set_inline_style( get_template_directory() . '/css/ys-wp-blocks.min.css', false );
	}
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
	 * CSSの読み込み
	 */
	wp_enqueue_style(
		'ystandard',
		ys_get_enqueue_css_file_uri(),
		array(),
		ys_get_theme_version( true )
	);
	/**
	 * カスタマイザーで設定変更可能なインラインCSSを追加
	 */
	wp_add_inline_style(
		'ystandard',
		ys_get_customizer_inline_css()
	);
	/**
	 * ブロックエディタのCSS
	 */
	if ( ys_is_active_gutenberg_css() ) {
		wp_enqueue_style(
			'ys-style-block',
			get_template_directory_uri() . '/css/ys-wp-blocks.css',
			array( 'ystandard' ),
			ys_get_theme_version( true )
		);
	}
	/**
	 * ユーザーカスタマイズ用CSSの読み込み（style.css）
	 */
	wp_enqueue_style(
		'style-css',
		get_stylesheet_directory_uri() . '/style.css',
		array( 'ystandard' ),
		ys_get_theme_version( true )
	);
}

/**
 * 読み込むCSSファイルのURLを取得する
 *
 * @return string
 */
function ys_get_enqueue_css_file_uri() {
	return get_template_directory_uri() . '/css/' . ys_get_enqueue_css_file_name();
}

/**
 * 読み込むCSSファイルのパスを取得する
 *
 * @return string
 */
function ys_get_enqueue_css_file_path() {
	return get_template_directory() . '/css/' . ys_get_enqueue_css_file_name();
}

/**
 * 読み込むCSSファイルの名前を取得する
 *
 * @return string
 */
function ys_get_enqueue_css_file_name() {
	$file = 'ystandard-light.css';
	/**
	 * PC表示
	 */
	if ( ! ys_is_mobile() ) {
		$file = 'ystandard-desktop.css';
		/**
		 * PC & サイドバーあり
		 */
		if ( ! ys_is_one_column() ) {
			$file = 'ystandard-has-sidebar-desktop.css';
		}
	}

	return $file;
}