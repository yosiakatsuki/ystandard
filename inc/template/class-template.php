<?php
/**
 * テンプレート関連の関数
 *
 * @package ystandard
 * @author  yosiakatsuki
 * @license GPL-2.0+
 */

namespace ystandard;

use ystandard\utils\Post_Type;

defined( 'ABSPATH' ) || die();

/**
 * Class Template_Function
 *
 * @package ystandard
 */
class Template {

	/**
	 * 投稿タイプ
	 *
	 * @var array
	 */
	private static $post_types = [];

	/**
	 * タクソノミー
	 *
	 * @var array
	 */
	private static $taxonomies = [];

	/**
	 * テンプレート
	 *
	 * @var array
	 */
	private static $templates = [];

	/**
	 * テンプレート読み込み拡張
	 *
	 * @param string $slug The slug name for the generic template.
	 * @param string $name The name of the specialised template.
	 * @param array $args テンプレートに渡す変数.
	 */
	public static function get_template_part( $slug, $name = null, $args = [] ) {
		// テンプレート上書き.
		$slug = apply_filters( 'ys_get_template_part_slug', $slug, $name, $args );
		$name = apply_filters( 'ys_get_template_part_name', $name, $slug, $args );
		$args = apply_filters( 'ys_get_template_part_args', $args, $slug, $name );

		do_action( "get_template_part_{$slug}", $slug, $name );

		// テーマ・プラグイン内のファイルのパスが指定されてきた場合そちらを優先.
		if ( false !== strpos( $slug, ABSPATH ) && file_exists( $slug ) ) {
			load_template( $slug, false, $args );

			return true;
		}

		// 初期化.
		self::$post_types = [];
		self::$taxonomies = [];
		self::$templates  = [];
		// 名前確認.
		$name = ! is_string( $name ) ? '' : $name;

		// 投稿タイプセット.
		self::get_post_types();
		// タクソノミーセット.
		self::get_taxonomies();

		// テンプレートセット.
		self::set_templates( $slug, $name ); // nameあり.
		self::set_templates( $slug ); // nameなし.

		do_action( 'get_template_part', $slug, $name, self::$templates );

		// テンプレート読み込み.
		if ( ! locate_template( self::$templates, true, false, $args ) ) {
			return false;
		}

		return true;
	}

	/**
	 * 投稿タイプセット
	 *
	 * @return void
	 */
	private static function get_post_types() {
		$post_type = Post_Type::get_post_type();
		$post_type = empty( $post_type ) ? '' : $post_type;

		self::$post_types = array_merge( self::$post_types, [ $post_type ] );
	}

	/**
	 * タクソノミーセット
	 *
	 * @return void
	 */
	private static function get_taxonomies() {
		$taxonomy = '';
		if ( is_category() ) {
			$taxonomy = 'category';
		}
		if ( is_tag() ) {
			$taxonomy = 'tag';
		}
		if ( is_tax() ) {
			$taxonomy         = get_query_var( 'taxonomy' );
			$taxonomy_objects = get_taxonomy( $taxonomy );
			$post_types       = $taxonomy_objects->object_type;
			self::$post_types = array_diff( $post_types, self::$post_types );
		}
		self::$taxonomies = array_merge( self::$taxonomies, [ $taxonomy ] );
	}

	/**
	 * テンプレートセット
	 *
	 * @param string $slug テンプレートスラッグ.
	 * @param string $name 追加の名前.
	 *
	 * @return void
	 */
	private static function set_templates( $slug, $name = '' ) {
		$name = ! empty( $name ) ? "-{$name}" : '';

		foreach ( self::$taxonomies as $taxonomy ) {
			if ( ! empty( $taxonomy ) ) {
				self::add_template( "{$slug}{$name}-{$taxonomy}.php" );
			}
		}
		foreach ( self::$post_types as $post_type ) {
			if ( ! empty( $post_type ) ) {
				self::add_template( "{$slug}{$name}-{$post_type}.php" );
			}
		}
		self::add_template( "{$slug}{$name}.php" );
	}

	/**
	 * テンプレートの追加.
	 *
	 * @param string $template テンプレート.
	 *
	 * @return void
	 */
	private static function add_template( $template ) {
		if ( in_array( $template, self::$templates, true ) ) {
			return;
		}
		self::$templates[] = $template;
	}
}
