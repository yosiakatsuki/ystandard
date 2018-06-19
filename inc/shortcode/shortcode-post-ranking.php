<?php
/**
 * ショートコード: 記事ランキング
 *
 * @package ystandard
 * @author yosiakatsuki
 * @license GPL-2.0+
 */

/**
 * 記事ランキング
 *
 * @param array $args パラメーター.
 *
 * @return string
 */
function ys_shortcode_post_ranking( $args ) {
	$args = shortcode_atts(
		array(
			'id'             => '',
			'class'          => '',
			'class_list'     => '',
			'class_item'     => '',
			'class_link'     => '',
			'title'          => '',
			'post_count'     => 5,
			'show_img'       => true,
			'thumbnail_size' => 'thumbnail',
			'period'         => 'all',
			'filter'         => 'cat',
			'template'       => '',
		),
		$args
	);
	/**
	 * 変数
	 */
	$title          = $args['title'];
	$period         = $args['period'];
	$post_count     = $args['post_count'];
	$thumbnail_size = $args['thumbnail_size'];
	if ( ! $args['show_img'] || 'false' === $args['show_img'] ) {
		$thumbnail_size = '';
	}
	/**
	 * クエリ作成
	 */
	$query  = null;
	$option = null;
	/**
	 * 投稿とカテゴリーページの場合
	 * カスタムタクソノミー対応はそのうち
	 */
	if ( is_single() || is_category() ) {
		if ( 'cat' === $args['filter'] ) {
			/**
			 * カテゴリーで絞り込む
			 */
			$cat_ids = ys_get_the_category_id_list( true );
			/**
			 * オプションパラメータ作成
			 */
			$option = array( 'category__in' => $cat_ids );
		}
		/**
		 * 投稿ならば表示中の投稿をのぞく
		 */
		if ( is_single() ) {
			global $post;
			$option = wp_parse_args(
				array( 'post__not_in' => array( $post->ID ) ),
				$option
			);
		}
	}
	$option = apply_filters(
		'ys_shortcode_post_ranking_option',
		$option,
		$args['id'],
		$title
	);
	$query  = ys_get_post_views_query( $period, $post_count, $option );
	/**
	 * 個別記事・カテゴリーアーカイブで関連記事が取れない場合、全体の人気記事にする
	 */
	if ( ( is_single() || is_category() ) && ! $query->have_posts() ) {
		wp_reset_postdata();
		$query = ys_get_post_views_query( $period, $post_count );
	}
	/**
	 * 投稿データ取得
	 */
	$ys_post_list = new YS_Post_List( $args['id'], $args['class'], $thumbnail_size, $query );
	if ( '' !== $args['template'] ) {
		$ys_post_list->set_template( $args['template'] );
	}
	$ys_post_list->set_class_list( $args['class_list'] );
	$ys_post_list->set_class_item( $args['class_item'] );
	$ys_post_list->set_class_link( $args['class_link'] );
	$html = $ys_post_list->get_post_list( array() );

	return apply_filters( 'ys_shortcode_post_ranking', $html, $args['id'] );
}

add_shortcode( 'ys_post_ranking', 'ys_shortcode_post_ranking' );
