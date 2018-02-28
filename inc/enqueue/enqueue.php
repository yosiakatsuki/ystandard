<?php
/**
 * スクリプトの読み込み
 */
if( ! function_exists( 'ys_enqueue_scripts' ) ) {
	function ys_enqueue_scripts() {
		/**
		 * javascript
		 */
		if( ys_is_load_cdn_jquery() ){
			wp_enqueue_script( 'jquery', ys_get_option( 'ys_load_cdn_jquery_url' ) );
		}
		/**
		 * IE,Edge関連
		 */
		if( ys_is_ie() || ys_is_edge() ) {
			/**
			 * object-fit
			 */
			wp_enqueue_script( 
				'object-fit-images', 
				get_template_directory_uri() . '/library/object-fit-images/ofi.min.js' 
			);
			/**
			 * sticky
			 */
			wp_enqueue_script( 
				'stickyfill', 
				get_template_directory_uri() . '/library/stickyfill/stickyfill.min.js' 
			);
			/**
			 * polyfill関連の実行処理など
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
			ys_get_theme_version(true),
			true
		);

	}
}
add_action( 'wp_enqueue_scripts', 'ys_enqueue_scripts' );

/**
 * CSSの読み込み
 */
if ( ! function_exists( 'ys_enqueue_styles' ) ) {
	function ys_enqueue_styles(){
		/**
		 * IE,Edge関連
		 */
		if( ys_is_ie() || ys_is_edge() ) {
			wp_enqueue_style(
				'ys_ie',
				get_template_directory_uri() . '/css/ys-ie.min.css',
				array(),
				ys_get_theme_version( true )
			);
		}
	}
}
add_action( 'wp_enqueue_scripts', 'ys_enqueue_styles' );

/**
 * CSS追加読み込みの指定
 */
function ys_enqueue_styles_non_critical_css() {
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
		ys_enqueue_non_critical_css(
			'font-awesome',
			get_template_directory_uri() . '/library/font-awesome/css/font-awesome.min.css',
			'4.7.0'
		);
}
add_action( 'wp_enqueue_scripts', 'ys_enqueue_styles_non_critical_css' );


/**
 * CSS追加読み込み（ファーストビュー以外のCSSをjavascriptで読み込みする）
 */
if( ! function_exists( 'ys_the_load_non_critical_css' ) ) {
	function ys_the_load_non_critical_css() {
		/**
		 * CSS出力
		 */
		ys_the_non_critical_css();
	}
}
add_action( 'wp_footer', 'ys_the_load_non_critical_css' );

/**
 * non-critical-cssのセット
 */
if( ! function_exists( 'ys_enqueue_non_critical_css' ) ) {
	function ys_enqueue_non_critical_css( $id, $src, $ver = false ) {
		global $ys_enqueue;
		$ys_enqueue->set_non_critical_css( $id, $src, $ver );
	}
}

/**
 *	追加読み込みスクリプト・CSSの追加(onload)
 */
if( ! function_exists( 'ys_the_onload_scripts' ) ) {
	function ys_the_onload_scripts( $tag ) {
		global $ys_enqueue;

		/**
		 * Twitter関連スクリプト読み込み
		 */
		if( ys_get_setting('ys_load_script_twitter') ){
			ys_enqueue_onload_script( 'twitter-wjs', '//platform.twitter.com/widgets.js' );
		}
		/**
		 * Facebook関連スクリプト読み込み
		 */
		if( ys_get_setting('ys_load_script_facebook') ){
			ys_enqueue_onload_script( 'facebook-jssdk', '//connect.facebook.net/ja_JP/sdk.js#xfbml=1&version=v2.11' );
		}

		if ( false === strpos( $tag, 'ystandard.bundle.js' ) ) return $tag;
		/**
		 * data属性の作成、メインのjavascriptに属性追加
		 */
		$onload_scripts = $ys_enqueue->get_onload_script_attr();
		$lazyload_scripts = $ys_enqueue->get_lazyload_script_attr();
		$lazyload_css = $ys_enqueue->get_lazyload_css_attr();
		$data = $onload_scripts . ' ' . $lazyload_scripts . ' ' . $lazyload_css;
		return str_replace( 'src', $data . ' id="ys-main-script" src', $tag );
	}
}
add_action( 'script_loader_tag', 'ys_the_onload_scripts' );

/**
 * onload-scriptのセット
 */
if( ! function_exists( 'ys_enqueue_onload_script' ) ) {
	function ys_enqueue_onload_script( $id, $src, $ver = false ) {
		global $ys_enqueue;
		$ys_enqueue->set_onload_script( $id, $src, $ver );
	}
}
/**
 * lazyload-scriptのセット
 */
if( ! function_exists( 'ys_enqueue_lazyload_script' ) ) {
	function ys_enqueue_lazyload_script( $id, $src, $ver = false) {
		global $ys_enqueue;
		$ys_enqueue->set_lazyload_script( $id, $src, $ver );
	}
}
/**
 * lazyload-cssのセット
 */
if( ! function_exists( 'ys_enqueue_lazyload_css' ) ) {
	function ys_enqueue_lazyload_css( $id, $src, $ver = false ) {
		global $ys_enqueue;
		$ys_enqueue->set_lazyload_css( $id, $src, $ver );
	}
}
/**
 * non-critical-cssの出力
 */
if( ! function_exists( 'ys_the_non_critical_css' ) ) {
	function ys_the_non_critical_css() {
		global $ys_enqueue;
		echo $ys_enqueue->get_non_critical_css();
	}
}


/**
 * スタイルシート・スクリプトの読み込み停止
 */
if( ! function_exists( 'ys_enqueue_deregister' ) ) {
	function ys_enqueue_deregister() {

		/**
		 * WordPressのjqueryを停止
		 */
		if( ys_is_deregister_jquery() ){
			wp_deregister_script( 'jquery' );
		}

	}
}
add_action( 'wp_enqueue_scripts', 'ys_enqueue_deregister',9 );

/**
 * 管理画面-javascriptの読み込み
 */
if( ! function_exists( 'ys_enqueue_admin_scripts' ) ) {
	function ys_enqueue_admin_scripts( $hook_suffix ){
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
}
add_action( 'admin_enqueue_scripts', 'ys_enqueue_admin_scripts' );

/**
 * 管理画面-スタイルシートの読み込み
 */
if ( ! function_exists( 'ys_enqueue_admin_styles' ) ) {
	function ys_enqueue_admin_styles( $hook_suffix ){
			wp_enqueue_style(
				'ys_admin_style',
				get_template_directory_uri() . '/css/ys-editor-style.css',
				array(),
				ys_get_theme_version( true )
			);
	}
}
add_action( 'admin_enqueue_scripts', 'ys_enqueue_admin_styles' );

/**
 * 管理画面-テーマカスタマイザーページでのスタイルシートの読み込み
 */
if ( ! function_exists( 'ys_enqueue_customizer_styles' ) ) {
	function ys_enqueue_customizer_styles( $hook_suffix ){
		wp_enqueue_style(
			'ys_customizer_style',
			get_template_directory_uri() . '/css/customizer/customizer.min.css',
			array(),
			ys_get_theme_version( true )
		);
	}
}
add_action( 'customize_controls_print_styles', 'ys_enqueue_customizer_styles' );

/**
 * テーマカスタマイザー用JS
 */
if ( ! function_exists( 'ys_enqueue_customize_controls_js' ) ) {
	function ys_enqueue_customize_controls_js() {
			wp_enqueue_script(
				'ys_customize_controls_js',
				get_template_directory_uri() . '/js/admin/customizer-control.js',
				array( 'customize-controls', 'jquery' ),
				ys_get_theme_version( true ),
				true
			);
	}
}
add_action( 'customize_controls_enqueue_scripts', 'ys_enqueue_customize_controls_js' );

/**
 * スクリプトにasync付ける
 */
if( ! function_exists( 'ys_add_async_on_js' ) ) {
	function ys_add_async_on_js( $tag ) {
		if( is_admin() ){
			return $tag;
		}
		/**
		 * jQuery関連以外のjsにasyncを付ける
		 */
		if ( false !== strpos( $tag, 'jquery' ) ) return $tag;
		return str_replace( 'src', 'async src', $tag );
	}
}
add_filter( 'script_loader_tag', 'ys_add_async_on_js' );