<?php
/**
 * スクリプト・CSSの読み込み関連の関数
 *
 * @package ystandard
 * @author  yosiakatsuki
 * @license GPL-2.0+
 */

if ( ! function_exists( 'ys_set_inline_style' ) ) {
	/**
	 * インラインCSSセット
	 *
	 * @param string  $style  インラインCSS.
	 * @param boolean $minify minifyするかどうか.
	 *
	 * @return void
	 */
	function ys_set_inline_style( $style, $minify = true ) {
		global $ys_enqueue;
		$ys_enqueue->set_inline_style( $style, $minify );
	}
}

/**
 * インラインCSS取得
 *
 * @param bool $is_amp AMPかどうか.
 *
 * @return string
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
			/**
			 * インラインCSS出力用にダミーでenqueueする.
			 */
			wp_enqueue_style( 'ystandard', get_template_directory_uri() . '/css/ys-style.min.css' );
			/**
			 * インラインCSSの登録
			 */
			wp_add_inline_style( 'ystandard', $style );
			/**
			 * ダミー削除用フック登録
			 */
			add_filter( 'style_loader_tag', 'ys_delete_ystandard_css', 10, 2 );
			$style = '';
		}
		echo $style . PHP_EOL;
	}
}
/**
 * ダミーで追加したCSSを削除
 *
 * @param string $html linkタグ.
 * @param string $handle キー.
 *
 * @return string
 */
function ys_delete_ystandard_css( $html, $handle ) {
	if ( 'ystandard' === $handle ) {
		$html = '';
	}

	return $html;
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
 * Non-critical-cssのセット
 *
 * @param string  $id  id.
 * @param string  $src src.
 * @param boolean $ver version.
 *
 * @return void
 */
function ys_enqueue_non_critical_css( $id, $src, $ver = false ) {
	global $ys_enqueue;
	$ys_enqueue->set_non_critical_css( $id, $src, $ver );
}


/**
 * 追加読み込みスクリプト・CSSの追加(onload)
 *
 * @param string $tag    tag.
 * @param string $handle handle.
 * @param string $src    src.
 *
 * @return string
 */
function ys_the_onload_scripts( $tag, $handle, $src ) {
	global $ys_enqueue;
	/**
	 * TODO:wp_localize_scriptに移行予定
	 */
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

	if ( 'ystandard-script' !== $handle ) {
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
 *
 * @return void
 */
function ys_enqueue_onload_script( $id, $src, $ver = false ) {
	global $ys_enqueue;
	$ys_enqueue->set_onload_script( $id, $src, $ver );
}

/**
 * Lazyload-scriptのセット
 *
 * @param string  $id  id.
 * @param string  $src src.
 * @param boolean $ver version.
 *
 * @return void
 */
function ys_enqueue_lazyload_script( $id, $src, $ver = false ) {
	global $ys_enqueue;
	$ys_enqueue->set_lazyload_script( $id, $src, $ver );
}

/**
 * Lazyload-cssのセット
 *
 * @param string  $id  id.
 * @param string  $src src.
 * @param boolean $ver version.
 *
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