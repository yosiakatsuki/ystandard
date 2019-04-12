<?php
/**
 * ショートコード: 投稿者
 *
 * @package ystandard
 * @author yosiakatsuki
 * @license GPL-2.0+
 */

/**
 * 投稿者表示
 *
 * @param array $args パラメータ.
 */
function ys_shortcode_author( $args ) {
	$args = shortcode_atts(
		array(
			'default_user_name' => false,
			'user_name'         => false,
		),
		$args
	);
	/**
	 * デフォルト値をセット
	 */
	$default_user_name = $args['default_user_name'];
	$user_name         = $args['user_name'];
	$author_id         = '';
	/**
	 * 個別投稿であれば投稿者を取得
	 */
	if ( is_singular() ) {
		$author_id = get_the_author_meta( 'ID' );
	}
	/**
	 * User_nameが設定されていればユーザー名からauthor_idを取得
	 */
	if ( false !== $user_name ) {
		$user = get_user_by( 'slug', $user_name );
		if ( $user ) {
			$author_id = $user->ID;
		}
	}
	/**
	 * デフォルトユーザーが指定されていれば表示する
	 */
	if ( '' === $author_id && false !== $default_user_name ) {
		$user = get_user_by( 'slug', $default_user_name );
		if ( $user ) {
			$author_id = $user->ID;
		}
	}
	$author_id = apply_filters( 'ys_shortcode_author_id', $author_id );
	/**
	 * ユーザー指定が無ければ空白
	 */
	if ( '' === $author_id ) {
		return '';
	}
	global $ys_author;
	$ys_author_temp = $ys_author;
	$ys_author      = $author_id;
	/**
	 * バッファリング
	 */
	ob_start();
	get_template_part( 'template-parts/parts/author-box' );
	$html = ob_get_clean();
	/**
	 * 投稿者情報を戻す
	 */
	$ys_author = $ys_author_temp;
	return apply_filters( 'ys_shortcode_author_html', $html, $author_id );
}
add_shortcode( 'ys_author', 'ys_shortcode_author' );