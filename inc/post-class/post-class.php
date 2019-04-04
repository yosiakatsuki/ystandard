<?php
/**
 * Post Class 関連
 *
 * @package ystandard
 * @author  yosiakatsuki
 * @license GPL-2.0+
 */

/**
 * Post Classを操作する
 *
 * @param  array $classes Classes.
 *
 * @return array
 */
function ys_post_class( $classes ) {
	/**
	 * [hentryの削除]
	 */
	if ( apply_filters( 'ystd_remove_hentry', true ) ) {
		$classes = array_diff( $classes, array( 'hentry' ) );
	}
	/**
	 * アイキャッチ画像の有無
	 */
	if ( is_singular() ) {
		if ( ys_is_active_post_thumbnail() ) {
			$classes[] = 'has-thumbnail';
		}
	}

	return $classes;
}

add_filter( 'post_class', 'ys_post_class' );

/**
 * Single,Pageで振り分けるクラスを作成する
 *
 * @param string $class 作成するクラス.
 *
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

