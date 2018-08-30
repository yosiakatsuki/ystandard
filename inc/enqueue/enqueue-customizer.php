<?php
/**
 * カスタマイザー関連のスクリプト・CSS
 *
 * @package ystandard
 * @author  yosiakatsuki
 * @license GPL-2.0+
 */

/**
 * セレクタとプロパティをくっつけてCSS作る
 *
 * @param  array $selectors セレクタ.
 * @param  array $properties プロパティ.
 *
 * @return string
 */
function ys_customizer_create_inline_css( $selectors, $properties ) {
	$property = '';
	foreach ( $properties as $key => $value ) {
		$property .= $key . ':' . $value . ';';
	}

	return implode( ',', $selectors ) . '{' . $property . '}';
}

/**
 * テーマカスタマイザーでの色指定 CSS取得
 *
 * @return string
 */
function ys_get_customizer_inline_css() {
	$css = '';
	/**
	 * カスタマイザー色指定
	 */
	$css .= ys_get_customizer_inline_css_color();

	/**
	 * カスタムヘッダー
	 */
	$css .= ys_get_customizer_inline_css_custom_header();

	return apply_filters( 'ys_get_customizer_inline_css', $css );
}


/**
 * 管理画面-テーマカスタマイザーページでのスタイルシートの読み込み
 *
 * @param string $hook_suffix suffix.
 *
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