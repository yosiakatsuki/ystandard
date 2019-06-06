<?php
/**
 * スクリプトの読み込み関連
 *
 * @package ystandard
 * @author  yosiakatsuki
 * @license GPL-2.0+
 */

/**
 * スクリプト関連のクラス準備
 *
 * @return YS_Scripts
 */
function ys_scripts() {
	global $ys_scripts;
	if ( ! ( $ys_scripts instanceof YS_Scripts ) ) {
		$ys_scripts = new YS_Scripts();
	}

	return $ys_scripts;
}


/**
 * JavaScriptの読み込み
 *
 * @return void
 */
function ys_enqueue_scripts() {
	$scripts = ys_scripts();
	$scripts->enqueue_script();
}

add_action( 'wp_enqueue_scripts', 'ys_enqueue_scripts' );

/**
 * CSSの読み込み
 *
 * @return void
 */
function ys_enqueue_styles() {
	$scripts = ys_scripts();
	/**
	 * CSSエンキュー処理
	 */
	$scripts->enqueue_styles();
}

add_action( 'wp_enqueue_scripts', 'ys_enqueue_styles', 9 );

/**
 * Gutenberg用WP標準CSSの削除
 */
function ys_dequeue_wp_block_css() {
	/**
	 * 本体側のブロックのスタイルを削除
	 */
	if ( ! ys_is_active_gutenberg_css() ) {
		wp_dequeue_style( 'wp-block-library' );
		wp_dequeue_style( 'wp-block-library-theme' );
	}
}
add_action( 'wp_enqueue_scripts', 'ys_dequeue_wp_block_css' );

/**
 * CSS読み込み
 */
function ys_set_enqueue_css() {
	$scripts = ys_scripts();
	/**
	 * CSSの読み込み
	 */
	ys_enqueue_css(
		YS_Scripts::CSS_HANDLE_MAIN,
		ys_get_enqueue_css_file_uri(),
		ys_is_optimize_load_css(),
		array(),
		ys_get_theme_version( true )
	);
	/**
	 * カスタマイザー関連
	 */
	ys_enqueue_inline_css( ys_get_customizer_inline_css() );
	/**
	 * Gutenbergフォントサイズ
	 */
	ys_enqueue_inline_css( $scripts->get_editor_font_size_css() );
	/**
	 * 追加CSS
	 */
	ys_enqueue_inline_css( wp_get_custom_css() );
	/**
	 * 追加CSSの出力削除
	 */
	remove_action( 'wp_head', 'wp_custom_css_cb', 101 );
	/**
	 * Style.css
	 */
	ys_enqueue_css(
		'style-css',
		get_stylesheet_uri(),
		ys_is_optimize_load_css(),
		array(),
		ys_get_theme_version( true )
	);
}

add_action( 'ys_enqueue_styles', 'ys_set_enqueue_css' );

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
	if ( ys_is_active_sidebar_widget() ) {
		$file = 'ystandard-has-sidebar.css';
	}
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