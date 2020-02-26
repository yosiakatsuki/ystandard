<?php
/**
 * 投稿関連
 *
 * @package ystandard
 * @author  yosiakatsuki
 * @license GPL-2.0+
 */

/**
 * クラス読み込み
 */
require_once dirname( __FILE__ ) . '/class-ys-post.php';
require_once dirname( __FILE__ ) . '/class-ys-post-type-parts.php';
require_once dirname( __FILE__ ) . '/post-view.php';

/**
 * Single,Pageで振り分けるクラスを作成する
 *
 * @param string $class 作成するクラス.
 */
function ys_the_singular_class( $class ) {
	echo ys_get_singular_class( $class );
}

/**
 * Single,Pageで振り分けるクラスを作成する
 *
 * @param string $class 作成するクラス.
 *
 * @return string
 */
function ys_get_singular_class( $class ) {
	$prefix = '';
	if ( is_single() ) {
		$prefix = 'single';
	} elseif ( is_page() ) {
		$prefix = 'page';
	}
	$prefix = apply_filters( 'ys_get_singular_class_prefix', $prefix );
	if ( '' !== $prefix ) {
		$prefix .= '__';
	}

	return $prefix . $class;
}


/**
 * 投稿オプション(post-meta)取得
 *
 * @param  string  $key     設定キー.
 * @param  integer $post_id 投稿ID.
 *
 * @return string
 */
function ys_get_post_meta( $key, $post_id = 0 ) {
	if ( 0 === $post_id ) {
		$post_id = get_the_ID();
	}

	return apply_filters( 'ys_get_post_meta', get_post_meta( $post_id, $key, true ), $key, $post_id );
}
