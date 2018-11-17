<?php
/**
 * ショートコード: タクソノミー指定の記事一覧
 *
 * @package ystandard
 * @author  yosiakatsuki
 * @license GPL-2.0+
 */

/**
 * タクソノミー指定の記事一覧
 *
 * @param array $args パラメーター.
 *
 * @return string
 */
function ys_shortcode_tax_posts( $args ) {
	$args = shortcode_atts(
		array(
			'id'             => '',
			'class'          => '',
			'class_list'     => '',
			'class_item'     => '',
			'class_link'     => '',
			'mode'           => 'vertical',
			'cols'           => '1',
			'taxonomy'       => '',
			'term_slug'      => '',
			'post_count'     => 5,
			'show_img'       => true,
			'thumbnail_size' => 'thumbnail',
			'template'       => '',
		),
		$args
	);
	/**
	 * 変数
	 */
	$post_count     = $args['post_count'];
	$thumbnail_size = $args['thumbnail_size'];
	if ( ! $args['show_img'] || 'false' === $args['show_img'] ) {
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
				'tax_query' => array(
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
	$args = array(
		'id'             => $args['id'],
		'class'          => $args['class'],
		'thumbnail_size' => $thumbnail_size,
		'template'       => $args['template'],
		'class_list'     => $args['class_list'],
		'class_item'     => $args['class_item'],
		'class_link'     => $args['class_link'],
		'mode'           => $args['mode'],
		'cols'           => $args['cols'],
	);
	/**
	 * データ取得準備
	 */
	$ys_post_list = new YS_Post_List( $args );
	$expiration   = ys_get_option( 'ys_query_cache_taxonomy_posts' );
	$ys_post_list->set_cache_key( 'tax_posts' );
	$ys_post_list->set_cache_expiration( $expiration );
	/**
	 * HTML作成
	 */
	$html = $ys_post_list->get_post_list( $post_args );

	return apply_filters( 'ys_shortcode_tax_posts', $html, $args['id'] );
}

add_shortcode( 'ys_tax_posts', 'ys_shortcode_tax_posts' );
