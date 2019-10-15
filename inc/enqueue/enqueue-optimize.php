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
	if ( is_admin() || ys_is_login_page() || is_customize_preview() ) {
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
	wp_scripts();
	if ( null !== $wp_scripts ) {
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
	 * WordPressのjqueryを停止
	 */
	if ( ys_is_deregister_jquery() ) {
		return;
	}
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