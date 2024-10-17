<?php
/**
 * パンくずリスト データ取得
 *
 * @package ystandard
 * @author  yosiakatsuki
 * @license GPL-2.0+
 */

namespace ystandard;

use ystandard\utils\Post_Type;

defined( 'ABSPATH' ) || die();

class Breadcrumbs_Data {
	/**
	 * Position.
	 *
	 * @var int
	 */
	private static $position = 1;

	/**
	 * Items.
	 *
	 * @var array
	 */
	private static $items = [];

	/**
	 * Show on front
	 *
	 * @var bool
	 */
	private static $show_on_front = false;

	/**
	 * Page on front
	 *
	 * @var bool
	 */
	private static $page_on_front = false;

	/**
	 * Page for posts
	 *
	 * @var bool
	 */
	private static $page_for_posts = false;

	/**
	 * Homeの表示ラベル
	 *
	 * @var string
	 */
	private static $home_label = 'Home';

	/**
	 * パンくずを表示するか
	 *
	 * @return bool
	 */
	public static function is_show_breadcrumbs() {

		// フックで表示・非表示制御.
		$pre = apply_filters( 'ys_pre_is_show_breadcrumbs', null );
		if ( ! is_null( $pre ) ) {
			return $pre;
		}
		// パンくずの位置設定の確認.
		$position = Option::get_option( 'ys_breadcrumbs_position', 'footer' );
		if ( 'none' === $position ) {
			return false;
		}
		// フロントページは無し.
		if ( is_front_page() ) {
			return false;
		}
		// 404ページは無し.
		if ( is_404() ) {
			return false;
		}
		// タイトル無しテンプレートの場合.
		if ( Template_Type::is_no_title_template() ) {
			if ( 'footer' !== $position ) {
				// フッター以外（ヘッダー側）であればパンくず無し.
				return false;
			} else {
				// フッター側＆表示する設定であれば表示する.
				return Option::get_option_by_bool( 'ys_show_breadcrumb_blank_template', false );
			}
		}

		return true;
	}

	/**
	 * パンずく取得
	 *
	 * @return array
	 */
	public static function get_breadcrumbs() {
		self::$position       = 1;
		self::$show_on_front  = get_option( 'show_on_front' );
		self::$page_on_front  = get_option( 'page_on_front' );
		self::$page_for_posts = get_option( 'page_for_posts' );
		self::$items          = [];
		self::$home_label     = apply_filters( 'ys_breadcrumbs_home_label', 'Home' );
		/**
		 * フロントページの場合
		 */
		if ( is_front_page() ) {
			if ( self::$page_on_front ) {
				$title = get_the_title( self::$page_on_front );
				if ( ! $title ) {
					$title = get_bloginfo( 'name', 'display' );
				}
				self::set_item(
					$title,
					home_url( '/' )
				);
			} else {
				self::set_item( self::$home_label, home_url( '/' ) );
			}

			return apply_filters( 'ys_get_breadcrumbs_data', self::$items );
		}
		/**
		 * TOPページ
		 */
		self::set_item(
			self::$home_label,
			home_url( '/' )
		);
		/**
		 * 一覧先頭ページ
		 */
		self::set_front_item();
		/**
		 * 属性ごと
		 */
		if ( is_404() ) {
			self::set_404();
		} elseif ( is_search() ) {
			self::set_search();
		} elseif ( is_tax() ) {
			self::set_tax();
		} elseif ( is_attachment() ) {
			self::set_attachment();
		} elseif ( is_page() ) {
			self::set_page();
		} elseif ( is_post_type_archive() ) {
			self::set_post_type_archive();
		} elseif ( is_single() ) {
			self::set_single();
		} elseif ( is_category() ) {
			self::set_category();
		} elseif ( is_tag() ) {
			self::set_tag();
		} elseif ( is_author() ) {
			self::set_author();
		} elseif ( is_day() ) {
			self::set_day();
		} elseif ( is_month() ) {
			self::set_month();
		} elseif ( is_year() ) {
			self::set_year();
		} elseif ( is_home() ) {
			self::set_home();
		}

		return apply_filters( 'ys_get_breadcrumbs_data', self::$items );
	}

	/**
	 * 404ページ
	 */
	private static function set_404() {
		self::set_item(
			__( 'Page not found' ),
			''
		);
	}

	/**
	 * 検索ページ
	 */
	private static function set_search() {
		/* translators: %1$s 検索文字列. */
		$title = sprintf( __( '「%1$s」の検索結果' ), get_search_query() );
		self::set_item(
			$title,
			esc_url_raw( home_url( '?s=' . rawurlencode( get_query_var( 's' ) ) ) )
		);
	}

	/**
	 * タクソノミー
	 */
	private static function set_tax() {
		$taxonomy         = get_query_var( 'taxonomy' );
		$term             = get_term_by( 'slug', get_query_var( 'term' ), $taxonomy );
		$taxonomy_objects = get_taxonomy( $taxonomy );
		$post_types       = $taxonomy_objects->object_type;
		$post_type        = array_shift( $post_types );
		if ( $post_type ) {
			$post_type_object = get_post_type_object( $post_type );
			$label            = $post_type_object->label;
			if ( $post_type_object->has_archive ) {
				self::set_item(
					$label,
					get_post_type_archive_link( $post_type )
				);
			}
			if ( is_taxonomy_hierarchical( $taxonomy ) && $term->parent ) {
				self::set_ancestors( $term->term_id, $taxonomy );
			}
		}

		self::set_item(
			$term->name,
			get_term_link( $term )
		);
	}

	/**
	 * 添付ファイルページ
	 */
	private static function set_attachment() {
		self::set_item(
			get_the_title(),
			get_the_permalink()
		);
	}

	/**
	 * 固定ページ
	 */
	private static function set_page() {
		self::set_ancestors( get_the_ID(), 'page' );
		self::set_item(
			get_the_title(),
			get_the_permalink()
		);
	}

	/**
	 * 投稿タイプアーカイブ
	 */
	private static function set_post_type_archive() {
		$post_type = self::get_post_type();
		if ( $post_type && 'post' !== $post_type ) {
			$post_type_object = get_post_type_object( $post_type );
			$label            = $post_type_object->label;
			self::set_item(
				$label,
				get_post_type_archive_link( $post_type_object->name )
			);
		}
	}

	/**
	 * 投稿ページ
	 */
	private static function set_single() {
		$post_type = self::get_post_type();
		if ( $post_type ) {
			$taxonomy = '';
			if ( 'post' === $post_type ) {
				// 投稿.
				$taxonomy = apply_filters( 'ys_breadcrumbs_single_taxonomy', 'category', $post_type );
			} else {
				// カスタム投稿タイプ.
				$post_type_object = get_post_type_object( $post_type );
				$label            = $post_type_object->label;
				self::set_item(
					$label,
					get_post_type_archive_link( $post_type )
				);
				$taxonomies = $post_type_object->taxonomies;
				if ( ! empty( $taxonomies ) ) {
					$taxonomy = array_shift( $taxonomies );
					$taxonomy = apply_filters(
						'ys_breadcrumbs_custom_post_type_taxonomy',
						$taxonomy,
						$post_type
					);
					$taxonomy = apply_filters(
						"ys_breadcrumbs_{$post_type}_taxonomy",
						$taxonomy,
						$post_type
					);
				}
			}
			$terms = get_the_terms( get_the_ID(), $taxonomy );
			if ( $terms ) {
				$term = $terms[0];
				self::set_ancestors( $term->term_id, $taxonomy );
				$link = get_term_link( $term );
				if ( is_wp_error( $link ) ) {
					$link = '';
				}
				self::set_item(
					$term->name,
					$link
				);
			}
		}

		self::set_item(
			get_the_title(),
			get_the_permalink()
		);
	}

	/**
	 * カテゴリーアーカイブ
	 */
	private static function set_category() {
		$category_name = single_cat_title( '', false );
		$category_id   = get_cat_ID( $category_name );
		self::set_ancestors( $category_id, 'category' );
		self::set_item(
			$category_name,
			get_category_link( $category_id )
		);
	}

	/**
	 * タグアーカイブ
	 */
	private static function set_tag() {
		self::set_item(
			single_tag_title( '', false ),
			get_tag_link( get_queried_object() )
		);
	}

	/**
	 * 投稿者
	 */
	private static function set_author() {
		$author_id = get_query_var( 'author' );
		self::set_item(
			get_the_author_meta( 'display_name', $author_id ),
			get_author_posts_url( $author_id )
		);
	}

	/**
	 * 日アーカイブ
	 */
	private static function set_day() {

		$year = get_query_var( 'year' );
		if ( $year ) {
			$month = get_query_var( 'monthnum' );
			$day   = get_query_var( 'day' );
		} else {
			$ymd   = get_query_var( 'm' );
			$year  = substr( $ymd, 0, 4 );
			$month = substr( $ymd, 4, 2 );
			$day   = substr( $ymd, - 2 );
		}
		self::set_year_item( $year );
		self::set_month_item( $year, $month );
		self::set_day_item( $day, $month, $year );
	}

	/**
	 * 月アーカイブ
	 */
	private static function set_month() {
		/**
		 * Month
		 */
		$year = get_query_var( 'year' );
		if ( $year ) {
			$month = get_query_var( 'monthnum' );
		} else {
			$ymd   = get_query_var( 'm' );
			$year  = substr( $ymd, 0, 4 );
			$month = substr( $ymd, - 2 );
		}
		self::set_year_item( $year );
		self::set_month_item( $year, $month );
	}

	/**
	 * 年アーカイブ
	 */
	private static function set_year() {
		/**
		 * Year
		 */
		$year = get_query_var( 'year' );
		if ( ! $year ) {
			$ymd  = get_query_var( 'm' );
			$year = $ymd;
		}
		self::set_year_item( $year );
	}

	/**
	 * ホーム
	 */
	private static function set_home() {
		/**
		 * Home
		 */
		if ( 'page' === self::$show_on_front && self::$page_for_posts ) {
			self::set_item(
				get_the_title( self::$page_for_posts ),
				get_permalink( self::$page_for_posts )
			);
		}
	}

	/**
	 * パンくずアイテムの変数セット
	 *
	 * @param string $title タイトル.
	 * @param string $link URL.
	 */
	private static function set_item( $title, $link ) {

		$item = [
			'@type'    => 'ListItem',
			'position' => self::$position,
			'name'     => $title,
			'item'     => $link,
		];
		if ( empty( $link ) ) {
			unset( $item['item'] );
		}
		self::$items[] = $item;
		self::$position ++;
	}

	/**
	 * 一覧ページ先頭アイテムをセット
	 */
	private static function set_front_item() {
		$add_item = false;
		if ( 'page' !== self::$show_on_front || ! self::$page_for_posts ) {
			return;
		}
		if ( ! Option::get_option_by_bool( 'ys_show_page_for_posts_on_breadcrumbs', true ) ) {
			return;
		}
		$post_type = self::get_post_type();
		// 詳細ページ.
		if ( is_single() ) {
			if ( 'post' === $post_type ) {
				$add_item = true;
			}
		}
		// WP標準.
		if ( is_date() || is_author() || is_category() || is_tag() ) {
			$add_item = true;
		}
		// カスタムタクソノミー.
		if ( is_tax() ) {
			$term = get_queried_object();
			if ( $term ) {
				$taxonomy = get_taxonomy( $term->taxonomy );
				if ( in_array( 'post', $taxonomy->object_type, true ) ) {
					$add_item = true;
				}
			}
		}
		if ( $add_item ) {
			self::set_item(
				get_the_title( self::$page_for_posts ),
				get_permalink( self::$page_for_posts )
			);
		}
	}

	/**
	 * 階層アイテムをセット
	 *
	 * @param int $object_id オブジェクトID.
	 * @param string $object_type オブジェクトタイプ.
	 * @param string $resource_type 固定ページ・カテゴリーなど.
	 */
	private static function set_ancestors( $object_id, $object_type, $resource_type = '' ) {
		$ancestors = get_ancestors( $object_id, $object_type, $resource_type );
		krsort( $ancestors );
		foreach ( $ancestors as $ancestor_id ) {
			if ( 'page' === $object_type ) {
				self::set_item(
					get_the_title( $ancestor_id ),
					get_permalink( $ancestor_id )
				);
			} else {
				$ancestors_term = get_term_by( 'id', $ancestor_id, $object_type );
				if ( $ancestors_term ) {
					self::set_item(
						$ancestors_term->name,
						get_term_link( $ancestor_id, $object_type )
					);
				}
			}
		}
	}

	/**
	 * 年の情報セット.
	 *
	 * @param string|int $year 年.
	 * @param bool $link URLをセットするか.
	 */
	private static function set_year_item( $year, $link = true ) {
		$label = $year;
		$url   = '';
		if ( 'ja' === get_locale() ) {
			$label .= '年';
		}
		if ( $link ) {
			$url = get_year_link( $year );
		}
		self::set_item(
			$label,
			$url
		);
	}

	/**
	 * 月情報セット
	 *
	 * @param string|int $year 年.
	 * @param string|int $month 月.
	 * @param bool $link URLをセットするか.
	 */
	private static function set_month_item( $year, $month, $link = true ) {
		$label = $month;
		$url   = '';
		if ( 'ja' === get_locale() ) {
			$label .= '月';
		} else {
			$monthes = [
				1  => 'January',
				2  => 'February',
				3  => 'March',
				4  => 'April',
				5  => 'May',
				6  => 'June',
				7  => 'July',
				8  => 'August',
				9  => 'September',
				10 => 'October',
				11 => 'November',
				12 => 'December',
			];
			$label   = $monthes[ $month ];
		}
		if ( $link ) {
			$url = get_month_link( $year, $month );
		}
		self::set_item(
			$label,
			$url
		);
	}

	/**
	 * 日情報をセットするか
	 *
	 * @param string|int $day 日.
	 * @param string|int $month 月.
	 * @param string|int $year 年.
	 */
	private static function set_day_item( $day, $month, $year ) {
		$label = $day;
		if ( 'ja' === get_locale() ) {
			$label .= '日';
		}
		self::set_item(
			$label,
			get_day_link( $year, $month, $day )
		);
	}

	/**
	 * Get Post Type
	 */
	private static function get_post_type() {
		return Post_Type::get_post_type();
	}

}
