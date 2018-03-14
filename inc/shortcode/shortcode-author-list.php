<?php
/**
 * ショートコード: 投稿者一覧
 *
 * @package ystandard
 * @author yosiakatsuki
 * @license GPL-2.0+
 */

/**
 * 投稿者一覧出力
 *
 * @param array $args パラメーター.
 * @return string
 */
function ys_shortcode_author_list( $args ) {
	$html = '';
	$args = shortcode_atts(
		array(
			'id'      => '',
			'exclude' => '',
		),
		$args
	);
	/**
	 * ユーザーデータ取得パラメータ
	 */
	$user_args = array(
		'orderby' => 'ID',
		'order'   => 'ASC',
	);
	/**
	 * 初期値セット
	 */
	$id = $args['id'];
	if ( '' !== $args['exclude'] ) {
		/**
		 * ユーザー名からIDを取得
		 */
		$exclude_id   = array();
		$exclude_name = explode( ',', $args['exclude'] );
		foreach ( $exclude_name as $value ) {
			$user = get_user_by( 'slug', $value );
			if ( $user ) {
				$exclude_id[] = $user->ID;
			}
		}
		$user_args = wp_parse_args(
			array( 'exclude' => $exclude_id ),
			$user_args
		);
	}
	/**
	 * ユーザー一覧取得
	 */
	$users = get_users( $user_args );
	foreach ( $users as $user ) {
		global $ys_author;
		$ys_author_temp = $ys_author;
		$ys_author      = $user->ID;
		ob_start();
		get_template_part( 'template-parts/author/profile-box' );
		$html .= apply_filters( 'ys_shortcode_author_list_html', ob_get_clean(), $ys_author, $id );
		/**
		 * 戻す
		 */
		$ys_author = $ys_author_temp;
	}
	$html = sprintf( '<div class="ys_author_list author--2col">%s</div>', $html );
	return apply_filters( 'ys_shortcode_author_list', $html, $id );
}
add_shortcode( 'ys_author_list', 'ys_shortcode_author_list' );
