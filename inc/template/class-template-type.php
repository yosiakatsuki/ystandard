<?php
/**
 * テンプレートタイプ
 *
 * @package ystandard
 * @author  yosiakatsuki
 * @license GPL-3.0+
 */

namespace ystandard;

use ystandard\utils\Convert;
use ystandard\utils\Post_Type;

defined( 'ABSPATH' ) || die();

/**
 * class Template_Type.
 */
class Template_Type {
	/**
	 * Constructor.
	 */
	public function __construct() {
		add_filter( 'body_class', [ $this, 'add_body_class' ] );
	}

	/**
	 * フル幅テンプレートか
	 *
	 * @return bool
	 */
	public static function is_wide() {
		/**
		 * 投稿タイプ毎に幅広タイプか判定
		 */
		$post_type = Content::get_post_type();
		$is_wide   = apply_filters( "ys_{$post_type}_is_wide", null );
		if ( ! is_null( $is_wide ) ) {
			return Convert::to_bool( $is_wide );
		}
		// 1カラムテンプレートの場合のみ幅広判定ができる.
		if ( self::is_one_column() ) {
			// 幅広テンプレート種類を取得
			$templates = self::get_wide_templates();
			// コンテンツタイプ設定.
			$content_type = Option::get_option( 'ys_design_one_col_content_type', 'normal' );
			// テンプレートが一致するか確認
			if ( is_page_template( $templates ) || 'wide' === $content_type ) {
				return true;
			}
		}

		return false;
	}

	/**
	 * 幅広タイプのテンプレート種類を取得
	 *
	 * @return array
	 */
	public static function get_wide_templates() {
		$templates = [
			'page-template/template-one-column-wide.php',
			'page-template/template-blank-wide.php',
		];
		// 旧フック適用
		$templates = apply_filters( 'ys_is_wide_templates', $templates );

		return apply_filters( 'ys_get_wide_templates', $templates );
	}

	/**
	 * 1カラム表示か
	 *
	 * @return bool
	 */
	public static function is_one_column() {
		// フックでのカスタマイズ内容を優先
		$filter = apply_filters( 'ys_is_one_column', null );
		if ( ! is_null( $filter ) ) {
			return $filter;
		}
		// 1カラムテンプレートを取得する.
		$template = self::get_one_column_templates();
		if ( is_page_template( $template ) ) {
			return true;
		}
		// サイドバーチェック.
		if ( ! is_active_sidebar( 'sidebar-widget' ) && ! is_active_sidebar( 'sidebar-fixed' ) ) {
			return true;
		}
		/**
		 * 一覧系
		 */
		$post_type = Post_Type::get_post_type();
		// 投稿タイプ一覧.
		$post_type_archive = apply_filters( "ys_{$post_type}_archive_one_column", null );
		if ( is_post_type_archive( $post_type ) && ! is_null( $post_type_archive ) ) {
			return Convert::to_bool( $post_type_archive );
		}
		// タクソノミー一覧.
		if ( is_tax() ) {
			$taxonomy    = get_query_var( 'taxonomy' );
			$tax_archive = apply_filters( "ys_{$taxonomy}_taxonomy_archive_one_column", null, $taxonomy );
			if ( is_tax( $taxonomy ) && ! is_null( $tax_archive ) ) {
				return Convert::to_bool( $tax_archive );
			}
		}
		// カテゴリー一覧.
		if ( is_category() ) {
			$tax_archive = apply_filters( 'ys_category_taxonomy_archive_one_column', null );
			if ( ! is_null( $tax_archive ) ) {
				return Convert::to_bool( $tax_archive );
			}
		}
		// タグ一覧.
		if ( is_tag() ) {
			$tax_archive = apply_filters( 'ys_post_tag_taxonomy_archive_one_column', null );
			if ( ! is_null( $tax_archive ) ) {
				return Convert::to_bool( $tax_archive );
			}
		}
		// その他一覧.
		if ( is_home() || is_archive() || is_search() || is_404() ) {
			if ( '1col' === Option::get_option( 'ys_archive_layout', '1col' ) ) {
				return true;
			}
		}

		/**
		 * 投稿タイプ別・詳細ページ
		 */
		$one_col = apply_filters( "ys_{$post_type}_one_column", null );
		if ( is_singular( $post_type ) && ! is_null( $one_col ) ) {
			return Convert::to_bool( $one_col );
		}
		// レイアウト設定の上書き判定.
		$type = apply_filters( "ys_{$post_type}_layout", null );
		if ( is_null( $type ) ) {
			// フックが使われていなければ、設定を取得する.
			$fallback = Post_Type::get_fallback_post_type( $post_type );
			$type     = Option::get_option( "ys_{$fallback}_layout", '1col' );
		}

		if ( is_singular( $post_type ) && '1col' === $type ) {
			return true;
		}

		return false;
	}

	/**
	 * 1カラムタイプのテンプレート種類を取得
	 *
	 * @return array
	 */
	public static function get_one_column_templates() {
		$templates = [
			'page-template/template-one-column.php',
			'page-template/template-one-column-wide.php',
			'page-template/template-blank.php',
			'page-template/template-blank-wide.php',
		];
		// 旧フック適用
		$templates = apply_filters( 'ys_is_one_column_templates', $templates );

		return apply_filters( 'ys_get_one_column_templates', $templates );
	}

	/**
	 * タイトルなしテンプレートか
	 *
	 * @return bool
	 */
	public static function is_no_title_template() {

		$post_type = Post_Type::get_post_type();
		$result    = apply_filters( "ys_{$post_type}_no_title", null );
		if ( ! is_null( $result ) ) {
			return Convert::to_bool( $result );
		}

		// タイトルなしテンプレートの一覧取得.
		$template = self::get_no_title_templates();

		return is_page_template( $template );
	}

	/**
	 * タイトル無しタイプのテンプレート種類を取得
	 *
	 * @return array
	 */
	public static function get_no_title_templates() {
		$templates = [
			'page-template/template-blank.php',
			'page-template/template-blank-wide.php',
		];
		// 旧フック適用
		$templates = apply_filters( 'ys_is_no_title_templates', $templates );

		return apply_filters( 'ys_get_no_title_templates', $templates );
	}

	/**
	 * Body class
	 *
	 * @param array $classes body classes.
	 *
	 * @return array
	 */
	public function add_body_class( $classes ) {

		/**
		 * ワイド幅テンプレート
		 */
		if ( self::is_wide() ) {
			$classes[] = 'is-wide';
		}
		/**
		 * ワンカラム / サイドバーあり
		 */
		if ( self::is_one_column() ) {
			$classes[] = 'is-one-column';
		} else {
			$classes[] = 'has-sidebar';
		}

		return $classes;
	}
}

new Template_Type();
