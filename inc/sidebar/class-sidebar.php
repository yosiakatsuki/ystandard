<?php
/**
 * サイドバー関連.
 *
 * @package ystandard
 * @author  yosiakatsuki
 * @license GPL-2.0+
 */

namespace ystandard;

use ystandard\utils\Post_Type;

/**
 * Class Sidebar
 */
class Sidebar {
	/**
	 * 旧モバイルサイドバー設定.
	 */
	private const LEGACY_HIDE_MOBILE_OPTION = 'ys_hide_sidebar_mobile';

	/**
	 * インスタンス
	 *
	 * @var Sidebar
	 */
	private static $instance;

	/**
	 * インスタンス取得
	 *
	 * @return Sidebar
	 */
	public static function get_instance(): Sidebar {
		if ( null === self::$instance ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	/**
	 * コンストラクタ.
	 */
	private function __construct() {
		add_filter( 'ys_sidebar_class', [ $this, 'sidebar_class' ] );
	}

	/**
	 * モバイルでサイドバーを非表示にするか.
	 *
	 * @return bool
	 */
	public static function is_hidden_on_mobile(): bool {
		$setting_name = self::get_mobile_setting_name();

		// 投稿タイプ別設定が保存されている場合は、旧共通設定より優先する.
		if ( $setting_name && Option::exists_option( $setting_name ) ) {
			return Option::get_option_by_bool( $setting_name, false );
		}

		// 新設定が未保存の場合は、v4までの共通設定を引き継ぐ.
		return Option::get_option_by_bool( self::LEGACY_HIDE_MOBILE_OPTION, false );
	}

	/**
	 * 現在のページに対応する設定名を取得.
	 *
	 * @return string
	 */
	private static function get_mobile_setting_name(): string {
		// 個別ページでは投稿タイプ別の詳細ページ設定を使用する.
		if ( is_singular() ) {
			$post_type = Post_Type::get_post_type();

			return $post_type ? "ys_hide_{$post_type}_sidebar_mobile" : '';
		}

		// 個別ページ以外では、一覧ページに対応する投稿タイプ別設定を使用する.
		$post_type = self::get_archive_post_type();

		return $post_type ? "ys_hide_{$post_type}_archive_sidebar_mobile" : '';
	}

	/**
	 * 現在の一覧ページに対応する投稿タイプを取得.
	 *
	 * @return string
	 */
	private static function get_archive_post_type(): string {
		// WordPress標準の投稿一覧として扱われるページは、投稿の設定にまとめる.
		if ( is_home() || is_category() || is_tag() || is_date() || is_author() || is_search() ) {
			return 'post';
		}

		// カスタム投稿タイプアーカイブは、クエリ対象の投稿タイプを使用する.
		if ( is_post_type_archive() ) {
			$queried_object = get_queried_object();

			// WP_Post_Typeが取得できる場合は、その情報を正として扱う.
			if ( $queried_object instanceof \WP_Post_Type ) {
				return $queried_object->name;
			}

			$post_type = get_query_var( 'post_type' );

			// オブジェクトを取得できない場合は、クエリ変数から投稿タイプを補完する.
			if ( is_string( $post_type ) ) {
				return $post_type;
			}

			// 対象が1種類に限定される場合だけ、配列形式のクエリ変数を使用する.
			if ( is_array( $post_type ) && 1 === count( $post_type ) ) {
				return (string) reset( $post_type );
			}
		}

		// タクソノミーアーカイブは、紐づく投稿タイプが一意な場合だけ設定対象にする.
		if ( is_tax() ) {
			$term = get_queried_object();

			// タームを特定できなければ、対応する投稿タイプも判定できない.
			if ( ! $term instanceof \WP_Term ) {
				return '';
			}

			$taxonomy = get_taxonomy( $term->taxonomy );

			// 複数投稿タイプで共有するタクソノミーは、適用先を決められないため除外する.
			if ( $taxonomy && 1 === count( $taxonomy->object_type ) ) {
				return (string) reset( $taxonomy->object_type );
			}
		}

		// 対応する投稿タイプを一意に決められないページでは新設定を使用しない.
		return '';
	}

	/**
	 * サイドバークラス
	 *
	 * @param array $classes Classes.
	 *
	 * @return array
	 */
	public function sidebar_class( array $classes ): array {
		// 投稿本文エリア背景色設定.
		$content_background = Post_Content::get_content_background_color( Post_Type::get_post_type() );
		if ( $content_background ) {
			$classes[] = 'has-content-background';
		}

		return $classes;
	}

}
