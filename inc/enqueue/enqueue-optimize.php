<?php
/**
 * スクリプト・CSSの最適化処理
 *
 * @package ystandard
 * @author  yosiakatsuki
 * @license GPL-2.0+
 */

/**
 * [jQueryの読み込み]
 */
function ys_enqueue_jquery() {
	/**
	 * [jQueryを読み込まない場合]
	 */
	if ( ys_is_disable_jquery() ) {
		return;
	}
	wp_enqueue_script( 'jquery' );
	wp_enqueue_script( 'jquery-core' );
}

add_action( 'wp_enqueue_scripts', 'ys_enqueue_jquery' );

/**
 * [jQuery]読み込み最適化
 */
function ys_optimize_jquery() {
	/**
	 * 管理画面 or ログインページは操作しない
	 */
	if ( is_admin() || ys_is_login_page() ) {
		return;
	}
	/**
	 * WordPressのjqueryを停止
	 */
	if ( ! ys_is_deregister_jquery() ) {
		return;
	}
	global $wp_scripts;
	$ver = ys_get_theme_version();
	$src = '';
	/**
	 * 必要があればwp_scriptsを初期化
	 */
	if ( is_null( $wp_scripts ) ) {
		wp_scripts();
	}
	if ( ! is_null( $wp_scripts ) ) {
		$jquery = $wp_scripts->registered['jquery-core'];
		$ver    = $jquery->ver;
		$src    = $jquery->src;
	}
	/**
	 * CDN経由の場合
	 */
	if ( ys_is_load_cdn_jquery() ) {
		$src = ys_get_option( 'ys_load_cdn_jquery_url' );
		$ver = null;
	}
	if ( '' === $src ) {
		return;
	}
	/**
	 * [jQuery削除]
	 */
	wp_deregister_script( 'jquery' );
	wp_deregister_script( 'jquery-core' );
	/**
	 * フッターで読み込むか
	 */
	$in_footer = ys_is_load_jquery_in_footer();
	/**
	 * [jQueryをフッターに移動]
	 */
	wp_register_script(
		'jquery',
		false,
		array( 'jquery-core' ),
		$ver,
		$in_footer
	);
	wp_register_script(
		'jquery-core',
		$src,
		array(),
		$ver,
		$in_footer
	);
}

add_action( 'init', 'ys_optimize_jquery' );

/**
 * 出力される script 要素を加工
 *
 * @param string $tag    tag.
 * @param string $handle handle.
 * @param string $src    src.
 *
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
	$exclude_file = array(
		'jquery',
		'wp-custom-header',
		'wp-a11y',
	);
	$exclude_file = apply_filters(
		'ys_exclude_add_async_scripts_file',
		$exclude_file
	);
	if ( ! empty( $exclude_file ) ) {
		foreach ( $exclude_file as $val ) {
			if ( false !== strpos( $tag, $val ) ) {
				return $tag;
			}
		}
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
	/**
	 * デフォルトで非同期読み込みさせるスクリプト
	 */
	$async_list = array(
		'ystandard-script',
		'font-awesome',
	);
	$async_list = apply_filters(
		'ys_add_async_scripts_list',
		$async_list
	);
	if ( ! empty( $async_list ) ) {
		if ( in_array( $handle, $async_list ) ) {
			return str_replace( 'src', 'async defer src', $tag );
		}
	}
	/**
	 * 除外・デフォルトで非同期以外は設定に従う
	 */
	if ( ys_get_option( 'ys_option_optimize_load_js' ) ) {
		$tag = str_replace( 'src', 'async defer src', $tag );
	}

	return $tag;
}

add_filter( 'script_loader_tag', 'ys_script_loader_tag', 10, 3 );

/**
 * 出力されるcssロード用link要素を加工
 *
 * @param string $html   html.
 * @param string $handle handle.
 * @param string $href   href.
 *
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

add_filter( 'style_loader_tag', 'ys_style_loader_tag', 10, 3 );