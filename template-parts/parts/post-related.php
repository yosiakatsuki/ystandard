<?php
/**
 * 関連記事テンプレート
 *
 * @package ystandard
 * @author  yosiakatsuki
 * @license GPL-2.0+
 */

/**
 * 関連記事
 */
$param = apply_filters(
	'ys_related_posts_shortcode_param',
	array(
		'title'            => '関連記事',
		'list_type'        => 'card',
		'orderby'          => 'rand',
		'col_sp'           => 1,
		'col_tablet'       => 2,
		'col_pc'           => 4,
		'count'            => 4,
		'filter'           => 'category',
		'cache_key'        => 'related_posts',
		'cache_expiration' => ys_get_option( 'ys_query_cache_related_posts' ),
	)
);
ys_do_shortcode(
	'ys_recent_posts',
	$param
);