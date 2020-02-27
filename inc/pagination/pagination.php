<?php
/**
 * ページネーション
 *
 * @package ystandard
 * @author  yosiakatsuki
 * @license GPL-2.0+
 */

if ( ! function_exists( 'ys_get_pagination' ) ) {
	/**
	 * ページネーション用データ取得
	 *
	 * @param integer $range 現在ページの前後に何ページリンクを出力するか.
	 *
	 * @return array
	 */
	function ys_get_pagination( $range = 1 ) {
		global $wp_query;
		$pagination = array();
		/**
		 * MAXページ数と現在ページ数を取得
		 */
		$total   = isset( $wp_query->max_num_pages ) ? $wp_query->max_num_pages : 1;
		$current = get_query_var( 'paged' ) ? (int) get_query_var( 'paged' ) : 1;

		/**
		 * 全部で１ページなら出力しない
		 */
		if ( 1 === intval( $total ) ) {
			return $pagination;
		}
		/**
		 * 前へリンク
		 */
		if ( 1 < $current ) {
			$pagination[] = ys_set_pagination_item(
				apply_filters( 'ys_get_pagination_prev_text', '<i class="fas fa-angle-left"></i>' ),
				get_pagenum_link( $current - 1 )
			);
		}
		/**
		 * 1ページ目へリンク
		 */
		if ( $range + 2 < $current ) {
			$pagination[] = ys_set_pagination_item(
				'1',
				get_pagenum_link( 1 )
			);
			$pagination[] = ys_set_pagination_item(
				'…',
				'',
				'pagination__item pagination__dot'
			);
		}
		/**
		 * 各ページへのリンク作る
		 */
		for ( $i = 1; $i <= $total; $i ++ ) {
			if ( $current - $range <= $i && $i <= $current + $range ) {
				if ( $i === $current ) {
					$pagination[] = ys_set_pagination_item(
						$i,
						'',
						'pagination__item -current'
					);
				} else {
					$pagination[] = ys_set_pagination_item(
						$i,
						get_pagenum_link( $i )
					);
				}
			}
		}
		/**
		 * 最終ページへリンク
		 */
		if ( $current + $range + 1 < $total ) {
			$pagination[] = ys_set_pagination_item(
				'…',
				'',
				'pagination__item pagination__dot'
			);
			$pagination[] = ys_set_pagination_item(
				$total,
				get_pagenum_link( $total )
			);
		}
		/**
		 * 次ページへリンク
		 */
		if ( $current < $total ) {
			$pagination[] = ys_set_pagination_item(
				apply_filters( 'ys_get_pagination_next_text', '<i class="fas fa-angle-right"></i>' ),
				get_pagenum_link( $current + 1 )
			);
		}

		return apply_filters( 'ys_get_pagination', $pagination );
	}
}
/**
 * ページネーション用配列作成
 *
 * @param string $text  テキスト.
 * @param string $url   リンクURL.
 * @param string $class 共通以外のクラス指定.
 *
 * @return array
 */
function ys_set_pagination_item( $text, $url, $class = 'pagination__item' ) {

	return array(
		'text'  => $text,
		'url'   => $url,
		'class' => $class,
	);
}
