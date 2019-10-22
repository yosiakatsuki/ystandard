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
	/**
	 * JSエンキュー処理
	 */
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
	$scripts->pre_enqueue_styles();
}

add_action( 'wp_enqueue_scripts', 'ys_enqueue_styles', 9 );

/**
 * Gutenberg用WP標準CSSの削除
 */
function ys_dequeue_wp_block_css() {
	wp_dequeue_style( 'wp-block-library' );
	wp_dequeue_style( 'wp-block-library-theme' );
}

add_action( 'wp_enqueue_scripts', 'ys_dequeue_wp_block_css' );

/**
 * CSS読み込み
 */
function ys_set_enqueue_css() {
	$scripts = ys_scripts();
	if ( 'css' === ys_get_option( 'ys_enqueue_icon_font_type' ) ) {
		/**
		 * Font Awesome
		 */
		wp_enqueue_style(
			'font-awesome',
			ys_get_font_awesome_css_url(),
			array(),
			'v5.11.2'
		);
	}

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
	 * カスタマイザー/設定関連
	 */
	ys_enqueue_inline_css( ys_get_customizer_inline_css() );
	/**
	 * Gutenberg
	 */
	ys_enqueue_inline_css( $scripts->get_editor_font_size_css() );
	ys_enqueue_inline_css( $scripts->get_editor_color_palette() );
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
	/**
	 * アドミンバー処理
	 */
	if ( is_admin_bar_showing() ) {
		ys_enqueue_css(
			'adminbar-css',
			get_template_directory_uri() . '/css/ystandard-adminbar.css',
			false,
			array(),
			ys_get_theme_version( true )
		);
	}
	/**
	 * AMP用Font Awesome
	 */
	if ( ys_is_amp() ) {
		ys_enqueue_css(
			'ys-amp-fontawesome',
			ys_get_font_awesome_cdn_css_url(),
			false,
			array(),
			'v5.11.2'
		);
	}
}

add_action( 'ys_enqueue_styles', 'ys_set_enqueue_css' );

/**
 * JavaScript読み込み指定
 */
function ys_set_enqueue_scripts() {
	if ( ys_is_amp() ) {
		return;
	}
	if ( 'js' === ys_get_option( 'ys_enqueue_icon_font_type' ) ) {
		/**
		 * Font Awesome
		 */
		wp_enqueue_script(
			'font-awesome',
			ys_get_font_awesome_svg_url(),
			array(),
			'v5.11.2',
			true
		);
	}
	if ( 'kit' === ys_get_option( 'ys_enqueue_icon_font_type' ) && ! empty( ys_get_option( 'ys_enqueue_icon_font_kit_url' ) ) ) {
		/**
		 * Font Awesome
		 */
		wp_enqueue_script(
			'font-awesome',
			ys_get_option( 'ys_enqueue_icon_font_kit_url' ),
			array(),
			null,
			false
		);
		add_filter( 'script_loader_tag', 'ys_set_font_awesome_kit_attributes', 10, 2 );

	}
	wp_add_inline_script(
		'font-awesome',
		'FontAwesomeConfig = { searchPseudoElements: true };',
		'before'
	);

	/**
	 * Twitter関連スクリプト読み込み
	 */
	if ( ys_get_option( 'ys_load_script_twitter' ) ) {
		ys_enqueue_onload_script( 'twitter-wjs', ys_get_twitter_widgets_js() );
	}
	/**
	 * Facebook関連スクリプト読み込み
	 */
	if ( ys_get_option( 'ys_load_script_facebook' ) ) {
		ys_enqueue_onload_script( 'facebook-jssdk', ys_get_facebook_sdk_js() );
	}
}

add_action( 'ys_enqueue_scripts', 'ys_set_enqueue_scripts' );

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
	 * AMP以外は通常CSS
	 */
	if ( ! ys_is_amp() ) {
		$file = 'ystandard-main.css';
	}

	return $file;
}

/**
 * Font Awesome Kitのタグに属性つける
 *
 * @param string $tag    script tag.
 * @param string $handle handle.
 *
 * @return string
 */
function ys_set_font_awesome_kit_attributes( $tag, $handle ) {
	if ( 'font-awesome' !== $handle ) {
		return $tag;
	}
	$extra_tag_attributes = 'crossorigin="anonymous"';
	$modified_script_tag  = preg_replace(
		'/<script\s*(.*?src=.*?)>/',
		'<script \1' . " $extra_tag_attributes >",
		$tag,
		1
	);

	return $modified_script_tag;
}