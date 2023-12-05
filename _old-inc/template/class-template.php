<?php
/**
 * テンプレート関連の関数
 *
 * @package ystandard
 * @author  yosiakatsuki
 * @license GPL-2.0+
 */

namespace ystandard;

defined( 'ABSPATH' ) || die();

/**
 * Class Template_Function
 *
 * @package ystandard
 */
class Template {

	/**
	 * Template_Function constructor.
	 */
	public function __construct() {
		add_filter( 'the_excerpt_rss', [ $this, 'add_rss_thumbnail' ] );
		add_filter( 'the_content_feed', [ $this, 'add_rss_thumbnail' ] );
	}


	/**
	 * フル幅テンプレートか
	 *
	 * @return bool
	 */
	public static function is_wide() {
		$post_type = Content::get_post_type();
		$is_wide   = apply_filters( "ys_{$post_type}_is_wide", null );
		if ( ! is_null( $is_wide ) ) {
			return Utility::to_bool( $is_wide );
		}
		if ( self::is_one_column() ) {
			/**
			 * フル幅にするテンプレート
			 */
			$templates = apply_filters(
				'ys_is_wide_templates',
				[
					'page-template/template-one-column-wide.php',
					'page-template/template-blank-wide.php',
				]
			);
			if ( is_page_template( $templates ) || 'wide' === Option::get_option( 'ys_design_one_col_content_type', 'normal' ) ) {
				return true;
			}
		}

		return false;
	}

	/**
	 * 1カラム表示か
	 *
	 * @return bool
	 */
	public static function is_one_column() {

		$filter = apply_filters( 'ys_is_one_column', null );
		if ( ! is_null( $filter ) ) {
			return $filter;
		}
		/**
		 * ワンカラムテンプレート
		 */
		$template = apply_filters(
			'ys_is_one_column_templates',
			[
				'page-template/template-one-column.php',
				'page-template/template-one-column-wide.php',
				'page-template/template-blank.php',
				'page-template/template-blank-wide.php',
			]
		);
		if ( is_page_template( $template ) ) {
			return true;
		}
		/**
		 * サイドバーが無ければ1カラム
		 */
		if ( ! is_active_sidebar( 'sidebar-widget' ) && ! is_active_sidebar( 'sidebar-fixed' ) ) {
			return true;
		}
		/**
		 * 一覧系
		 */
		$post_type = Content::get_post_type();
		// 投稿タイプ一覧.
		$post_type_archive = apply_filters( "ys_{$post_type}_archive_one_column", null );
		if ( is_post_type_archive( $post_type ) && ! is_null( $post_type_archive ) ) {
			return Utility::to_bool( $post_type_archive );
		}
		// タクソノミー一覧.
		if ( is_tax() ) {
			$taxonomy    = get_query_var( 'taxonomy' );
			$tax_archive = apply_filters( "ys_{$taxonomy}_taxonomy_archive_one_column", null );
			if ( is_tax( $taxonomy ) && ! is_null( $tax_archive ) ) {
				return Utility::to_bool( $tax_archive );
			}
		}
		// カテゴリー一覧.
		if ( is_category() ) {
			$tax_archive = apply_filters( 'ys_category_taxonomy_archive_one_column', null );
			if ( ! is_null( $tax_archive ) ) {
				return Utility::to_bool( $tax_archive );
			}
		}
		// タグ一覧.
		if ( is_tag() ) {
			$tax_archive = apply_filters( 'ys_post_tag_taxonomy_archive_one_column', null );
			if ( ! is_null( $tax_archive ) ) {
				return Utility::to_bool( $tax_archive );
			}
		}
		// その他一覧.
		if ( is_home() || is_archive() || is_search() || is_404() ) {
			if ( '1col' === Option::get_option( 'ys_archive_layout', '2col' ) ) {
				return true;
			}
		}

		/**
		 * 投稿タイプ別
		 */
		$one_col = apply_filters( "ys_{$post_type}_one_column", null );
		if ( is_singular( $post_type ) && ! is_null( $one_col ) ) {
			return Utility::to_bool( $one_col );
		}
		$filter = apply_filters( "ys_{$post_type}_layout", null );
		if ( is_null( $filter ) ) {
			$fallback = Content::get_fallback_post_type( $post_type );
			$type     = Option::get_option( "ys_{$fallback}_layout", '2col' );
		} else {
			$type = $filter;
		}

		if ( is_singular( $post_type ) && '1col' === $type ) {
			return true;
		}

		return false;
	}

	/**
	 * 投稿ヘッダーを表示するか
	 *
	 * @return bool
	 */
	public static function is_active_post_header() {
		$result = true;
		if ( self::is_single_front_page() ) {
			$result = false;
		}
		if ( self::is_no_title_template() ) {
			$result = false;
		}
		if ( is_singular() ) {
			$post_type = Content::get_post_type();
			$result    = apply_filters(
				"ys_is_active_post_header_{$post_type}",
				$result
			);
		}

		return apply_filters( 'ys_is_active_post_header', $result );
	}

	/**
	 * 投稿フッターを表示するか
	 *
	 * @return bool
	 */
	public static function is_active_post_footer() {
		$result = true;
		if ( is_singular() ) {
			$post_type = Content::get_post_type();
			$result    = apply_filters(
				"ys_is_active_post_footer_{$post_type}",
				$result
			);
		}

		return apply_filters( 'ys_is_active_post_footer', $result );
	}

	/**
	 * タイトルなしテンプレートか
	 *
	 * @return bool
	 */
	public static function is_no_title_template() {

		$post_type = Content::get_post_type();
		$result    = apply_filters( "ys_{$post_type}_no_title", null );
		if ( ! is_null( $result ) ) {
			return Utility::to_bool( $result );
		}

		$template = apply_filters(
			'ys_is_no_title_templates',
			[
				'page-template/template-blank.php',
				'page-template/template-blank-wide.php',
			]
		);

		return is_page_template( $template );
	}



	/**
	 * RSSフィードにアイキャッチ画像を表示
	 *
	 * @param string $content content.
	 *
	 * @return string
	 */
	public function add_rss_thumbnail( $content ) {
		global $post;
		if ( Content::is_active_post_thumbnail( $post->ID ) ) {
			$content = get_the_post_thumbnail( $post->ID ) . $content;
		}

		return $content;
	}
}

new page\Template();
