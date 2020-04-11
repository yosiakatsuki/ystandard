<?php
/**
 * Pagination
 *
 * @package ystandard
 * @author  yosiakatsuki
 * @license GPL-2.0+
 */

namespace ystandard;

/**
 * Class Pagination
 *
 * @package ystandard
 */
class Pagination {

	/**
	 * 現在ページの前後に何ページリンクを出力するか.
	 *
	 * @var int
	 */
	private $range = 1;

	/**
	 * ページネーション
	 *
	 * @var array
	 */
	private $pagination = [];

	/**
	 * ページネーション用データ取得
	 *
	 * @param int $range 現在ページの前後に何ページリンクを出力するか.
	 *
	 * @return array|bool
	 */
	public function get_pagination( $range = 1 ) {
		/**
		 * @global \WP_Query
		 */
		global $wp_query;
		$this->range = $range;

		$total   = isset( $wp_query->max_num_pages ) ? $wp_query->max_num_pages : 1;
		$current = get_query_var( 'paged' ) ? (int) get_query_var( 'paged' ) : 1;
		if ( 1 === intval( $total ) ) {
			return false;
		}
		// 前へ.
		if ( 1 < $current ) {
			$this->set_pagination_item(
				apply_filters( 'ys_get_pagination_prev_text', Icon::get_icon( 'chevron-left' ) ),
				get_pagenum_link( $current - 1 )
			);
		}
		// 先頭へ.
		if ( $range + 2 <= $current ) {
			$this->set_pagination_item(
				'1',
				get_pagenum_link( 1 ),
				'pagination__item is-first is-hide-mobile'
			);
			if ( $range + 3 <= $current ) {
				$this->set_pagination_item(
					'…',
					'',
					'pagination__item is-dot is-hide-mobile'
				);
			}
		}
		// 各ページ.
		for ( $i = 1; $i <= $total; $i ++ ) {
			if ( $current - $range <= $i && $i <= $current + $range ) {
				if ( $i === $current ) {
					$this->set_pagination_item(
						$i,
						'',
						'pagination__item is-current'
					);
				} else {
					$this->set_pagination_item(
						$i,
						get_pagenum_link( $i )
					);
				}
			}
		}
		// 最終ページ.
		if ( $current + $range + 1 <= $total ) {
			if ( $current + $range + 2 <= $total ) {
				$this->set_pagination_item(
					'…',
					'',
					'pagination__item is-dot is-hide-mobile'
				);
			}
			$this->set_pagination_item(
				$total,
				get_pagenum_link( $total ),
				'pagination__item is-last is-hide-mobile'
			);
		}
		// 次ページ.
		if ( $current < $total ) {
			$this->set_pagination_item(
				apply_filters( 'ys_get_pagination_next_text', Icon::get_icon( 'chevron-right' ) ),
				get_pagenum_link( $current + 1 )
			);
		}

		return apply_filters( 'ys_get_pagination', $this->pagination );
	}

	/**
	 * ページネーション用配列作成
	 *
	 * @param string $text  テキスト.
	 * @param string $url   リンクURL.
	 * @param string $class 共通以外のクラス指定.
	 */
	private function set_pagination_item( $text, $url, $class = 'pagination__item' ) {
		$this->pagination[] = [
			'text'  => $text,
			'url'   => $url,
			'class' => $class,
		];
	}

}
