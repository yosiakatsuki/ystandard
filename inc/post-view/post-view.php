<?php
/**
 * 簡易的なビュー数カウント
 *
 * @package ystandard
 * @author yosiakatsuki
 * @license GPL-2.0+
 */

/**
 * 定数
 */
define( 'YS_METAKEY_PV_ALL', 'ys_pv_all' );
define( 'YS_METAKEY_PV_DAY_KEY', 'ys_pv_key_d' );
define( 'YS_METAKEY_PV_DAY_VAL', 'ys_pv_val_d' );
define( 'YS_METAKEY_PV_WEEK_KEY', 'ys_pv_key_w' );
define( 'YS_METAKEY_PV_WEEK_VAL', 'ys_pv_val_w' );
define( 'YS_METAKEY_PV_MONTH_KEY', 'ys_pv_key_m' );
define( 'YS_METAKEY_PV_MONTH_VAL', 'ys_pv_val_m' );

if ( ! function_exists( 'ys_get_post_view_count' ) ) {
	/**
	 * ビュー数の取得
	 *
	 * @param integer $post_id post_id.
	 * @param string  $type type.
	 * @return integer
	 */
	function ys_get_post_view_count( $post_id, $type = 'all' ) {
		if ( 'all' === $type ) {
			/**
			 * 全期間の値を取得
			 */
			$key = YS_PV_ALL;
		} elseif ( 'd' === $type ) {
			/**
			 * 日別の値を取得
			 */
			$key = YS_METAKEY_PV_DAY_KEY;
		} elseif ( 'w' === $type ) {
			/**
			 * 週別の値を取得
			 */
			$key = YS_METAKEY_PV_WEEK_KEY;
		} elseif ( 'm' === $type ) {
			/**
			 * 月別の値を取得
			 */
			$key = YS_METAKEY_PV_MONTH_KEY;
		} else {
			return 0;
		}

		/**
		 * ビュー数取得
		 */
		$views = ys_get_post_meta( $key, $post_id );

		if ( is_numeric( $views ) ) {
			return (int) $views;
		} else {
			return 0;
		}

	}
}
if ( ! function_exists( 'ys_get_post_view_count_format' ) ) {
	/**
	 * ビュー数の取得(文字列)
	 *
	 * @param integer $post_id postid.
	 * @param string  $type 期間.
	 * @return string
	 */
	function ys_get_post_view_count_format( $post_id, $type = 'all' ) {
		/**
		 * ビュー数取得
		 */
		$views = ys_get_post_view_count( $post_id, $type );

		if ( 1000000 <= $views ) {
			/**
			 * 100万以上の値を「M」に変換
			 */
			$result = sprintf( '%.2f', $views / 1000000 ) . 'M';
		} elseif ( 1000 <= $views ) {
			$result = sprintf( '%.2f', $views / 1000 ) . 'K';
		} else {
			$result = (string) $views;
		}
		return $result;
	}
}

if ( ! function_exists( 'ys_update_post_views' ) ) {
	/**
	 * 簡易ビュー数カウント
	 *
	 * @return void
	 */
	function ys_update_post_views() {
		global $post;
		if ( ! is_single() ) {
			return;
		}
		/**
		 * 全アクセスカウント
		 */
		$post_meta_views = ys_get_post_meta( YS_METAKEY_PV_ALL, $post->ID );
		$views           = ys_get_update_post_view( $post_meta_views );
		update_post_meta( $post->ID, YS_METAKEY_PV_ALL, $views );
		/**
		 * 日別アクセスカウント
		 */
		ys_update_post_view_meta(
			$post->ID,
			date_i18n( 'Y/m/d' ),
			YS_METAKEY_PV_DAY_KEY,
			YS_METAKEY_PV_DAY_VAL
		);
		/**
		 * 週別アクセスカウント
		 */
		ys_update_post_view_meta(
			$post->ID,
			date_i18n( 'Y-W' ),
			YS_METAKEY_PV_WEEK_KEY,
			YS_METAKEY_PV_WEEK_VAL
		);
		/**
		 * 月別アクセスカウント
		 */
		ys_update_post_view_meta(
			$post->ID,
			date_i18n( 'Y-m' ),
			YS_METAKEY_PV_MONTH_KEY,
			YS_METAKEY_PV_MONTH_VAL
		);
	}
}
add_filter( 'wp_head', 'ys_update_post_views' );
/**
 * 更新後のView数取得
 *
 * @param integer $view view.
 * @param integer $increment incriment.
 * @return integer
 */
function ys_get_update_post_view( $view, $increment = 1 ) {
	if ( is_numeric( $view ) ) {
		$view = (int) $view + $increment;
	} else {
		$view = 1;
	}
	return $view;
}
/**
 * Post　meta にPV数登録・更新
 *
 * @param integer $post_id post id.
 * @param string  $meta_key meta key.
 * @param string  $meta_pv_key pv key.
 * @param string  $meta_pv_val val key.
 * @return void
 */
function ys_update_post_view_meta( $post_id, $meta_key, $meta_pv_key, $meta_pv_val ) {
	$post_meta_key = ys_get_post_meta( $meta_pv_key, $post_id );
	if ( $meta_key === $post_meta_key ) {
		$post_meta_views = ys_get_post_meta( $meta_pv_val, $post_id );
		$views           = ys_get_update_post_view( $post_meta_views );
		update_post_meta( $post_id, $meta_pv_val, $views );
	} else {
		update_post_meta( $post_id, $meta_pv_key, $meta_key );
		update_post_meta( $post_id, $meta_pv_val, 1 );
	}
}

if ( ! function_exists( 'ys_get_post_views_query_base' ) ) {
	/**
	 * ランキング表示用query作成
	 *
	 * @param integer $posts_per_page 投稿数.
	 * @param string  $meta key.
	 * @param array   $option オプション.
	 * @return WP_Query
	 */
	function ys_get_post_views_query_base( $posts_per_page, $meta, $option ) {
		$args = array(
			'post_type'           => 'post',
			'posts_per_page'      => $posts_per_page,
			'order'               => 'DESC',
			'orderby'             => 'meta_value_num',
			'no_found_rows'       => true,
			'ignore_sticky_posts' => true,
		);
		/**
		 * ランキングの条件部分をマージ
		 */
		$args = wp_parse_args( $meta, $args );
		if ( null !== $option ) {
			$args = wp_parse_args( $option, $args );
		}
		/**
		 * WP_Queryを作成
		 */
		return new WP_Query( $args );
	}
}

if ( ! function_exists( 'ys_get_post_views_query' ) ) {
	/**
	 * ランキング作成クエリ取得
	 *
	 * @param string  $type 期間.
	 * @param integer $posts_per_page 表示数.
	 * @param array   $option オプション.
	 * @return WP_Query
	 */
	function ys_get_post_views_query( $type = 'all', $posts_per_page = 4, $option = null ) {
		if ( 'd' === $type ) {
			return ys_get_post_views_query_day( $posts_per_page, $option );
		} elseif ( 'w' === $type ) {
			return ys_get_post_views_query_week( $posts_per_page, $option );
		} elseif ( 'm' === $type ) {
			return ys_get_post_views_query_month( $posts_per_page, $option );
		}
		return ys_get_post_views_query_all( $posts_per_page, $option );
	}
}

if ( ! function_exists( 'ys_get_post_views_query_all' ) ) {
	/**
	 * 全ランキング表示用query作成
	 *
	 * @param integer $posts_per_page 表示数.
	 * @param array   $option オプション.
	 * @return WP_Query
	 */
	function ys_get_post_views_query_all( $posts_per_page = 4, $option = null ) {
		/**
		 * ランキングの条件部分を作成
		 */
		$meta = array( 'meta_key' => YS_METAKEY_PV_ALL );
		/**
		 * WP_Queryを作成
		 */
		return ys_get_post_views_query_base( $posts_per_page, $meta, $option );
	}
}

if ( ! function_exists( 'ys_get_post_views_query_day' ) ) {
	/**
	 * 日別ランキング表示用query作成
	 *
	 * @param integer $posts_per_page 表示数.
	 * @param array   $option オプション.
	 * @return WP_Query
	 */
	function ys_get_post_views_query_day( $posts_per_page = 4, $option = null ) {
		/**
		 * ランキングの条件部分を作成
		 */
		$meta = array(
			'meta_key'   => YS_METAKEY_PV_DAY_VAL,
			'meta_query' => array(
				array(
					'key'     => YS_METAKEY_PV_DAY_KEY,
					'value'   => date_i18n( 'Y/m/d' ),
					'compare' => '=',
				),
			),
		);
		/**
		 * WP_Queryを作成
		 */
		return ys_get_post_views_query_base( $posts_per_page, $meta, $option );
	}
}

if ( ! function_exists( 'ys_get_post_views_query_week' ) ) {
	/**
	 * 週別ランキング表示用query作成
	 *
	 * @param integer $posts_per_page 投稿数.
	 * @param array   $option オプション.
	 * @return WP_Query
	 */
	function ys_get_post_views_query_week( $posts_per_page = 4, $option = null ) {
		/**
		 * ランキングの条件部分を作成
		 */
		$meta = array(
			'meta_key'   => YS_METAKEY_PV_WEEK_VAL,
			'meta_query' => array(
				array(
					'key'     => YS_METAKEY_PV_WEEK_KEY,
					'value'   => date_i18n( 'Y-W' ),
					'compare' => '=',
				),
			),
		);
		/**
		 * WP_Queryを作成
		 */
		return ys_get_post_views_query_base( $posts_per_page, $meta, $option );
	}
}

if ( ! function_exists( 'ys_get_post_views_query_month' ) ) {
	/**
	 * 月別ランキング表示用query作成
	 *
	 * @param integer $posts_per_page 表示数.
	 * @param array   $option オプション.
	 * @return WP_Query
	 */
	function ys_get_post_views_query_month( $posts_per_page = 4, $option = null ) {
		/**
		 * ランキングの条件部分を作成
		 */
		$meta = array(
			'meta_key'   => YS_METAKEY_PV_MONTH_VAL,
			'meta_query' => array(
				array(
					'key'     => YS_METAKEY_PV_MONTH_KEY,
					'value'   => date_i18n( 'Y-m' ),
					'compare' => '=',
				),
			),
		);
		/**
		 * WP_Queryを作成
		 */
		return ys_get_post_views_query_base( $posts_per_page, $meta, $option );
	}
}