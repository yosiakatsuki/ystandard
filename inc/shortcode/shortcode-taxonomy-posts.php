<?php
/**
 * ショートコード: タクソノミー指定の記事一覧
 *
 * @package ystandard
 * @author yosiakatsuki
 * @license GPL-2.0+
 */

/**
 * タクソノミー指定の記事一覧
 *
 * @param array $args パラメーター.
 * @return string
 */
function ys_shortcode_tax_posts( $args ) {
	$html = '';
	$args = shortcode_atts(
		array(
			'id'             => '',
			'class'          => '',
			'title'          => '',
			'taxonomy'       => '',
			'term_slug'      => '',
			'post_count'     => 5,
			'show_img'       => true,
			'thumbnail_size' => 'thumbnail',
		),
		$args
	);
	/**
	 * 変数
	 */
	$id         = '';
	$class      = '';
	$title      = $args['title'];
	$post_count = $args['post_count'];
	if ( '' !== $args['id'] ) {
		$id = sprintf( ' id="%s"', $args['id'] );
	}
	if ( '' !== $args['class'] ) {
		$class = sprintf( ' class="%s"', $args['class'] );
	}
	$thumbnail_size = $args['thumbnail_size'];
	if ( ! $args['show_img'] ) {
		$thumbnail_size = '';
	}
	/**
	 * パラメータの作成
	 */
	$post_args = array(
		'posts_per_page' => $post_count,
	);
	/**
	 * タクソノミーがあればタクソノミー指定
	 */
	if ( '' !== $args['taxonomy'] && '' !== $args['term_slug'] ) {
		$post_args = wp_parse_args(
			$post_args,
			array(
				'tax_query'      => array(
					array(
						'taxonomy' => $args['taxonomy'],
						'field'    => 'slug',
						'terms'    => $args['term_slug'],
					),
				),
			)
		);
	}
	/**
	 * 投稿データ取得
	 */
	$ys_post_list = new YS_Post_List( $args['id'], $args['class'], $thumbnail_size );
	$html         = $ys_post_list->get_post_list( $post_args );
	return apply_filters( 'ys_shortcode_tax_posts', $html, $args['id'] );
}
add_shortcode( 'ys_tax_posts', 'ys_shortcode_tax_posts' );
