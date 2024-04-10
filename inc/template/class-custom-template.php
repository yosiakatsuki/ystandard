<?php
/**
 * カスタムテンプレート
 *
 * @package ystandard
 * @author  yosiakatsuki
 * @license GPL-3.0+
 */

namespace ystandard;

defined( 'ABSPATH' ) || die();

/**
 * class Custom_Template.
 *
 * @package ystandard
 */
class Custom_Template {

	/**
	 * Constructor.
	 */
	public function __construct() {
		add_filter( 'theme_templates', [ $this, 'add_custom_template' ], 10, 4 );
	}

	/**
	 * 各投稿タイプでもカスタムテンプレートを選択できるようにする
	 *
	 * @param string[] $post_templates Array of template header names keyed by the template file name.
	 * @param \WP_Theme $theme The theme object.
	 * @param \WP_Post|null $post The post being edited, provided for context, or null.
	 * @param string $post_type Post type to get the templates for.
	 *
	 * @return string[];
	 */
	public function add_custom_template( $post_templates, $theme, $post, $post_type ) {

		if ( Parts::POST_TYPE === $post_type ) {
			return $post_templates;
		}

		// 投稿タイプ取得.
		$post_type = get_post_type_object( $post_type );
		if ( is_null( $post_type ) ) {
			return $post_templates;
		}

		// 公開されていない場合は追加しない.
		if ( ! $post_type->public ) {
			return $post_templates;
		}

		// テーマのテンプレートを各投稿タイプでも選択できるようにする.
		$templates = self::get_theme_custom_templates();
		foreach ( $templates as $file => $name ) {
			if ( ! array_key_exists( $file, $post_templates ) ) {
				$post_templates[ $file ] = $name;
			}
		}

		return $post_templates;
	}

	/**
	 * テーマのカスタムテンプレート一覧を取得
	 *
	 * @return array
	 */
	public static function get_theme_custom_templates() {
		/**
		 * TODO:サイドバーありテンプレートも足す
		 *
		 * 'page-template/template-sidebar-right.php'   => __( '右サイドバー', 'ystandard' ),
		 * 'page-template/template-sidebar-left.php'    => __( '左サイドバー', 'ystandard' ),
		 */
		return [
			'page-template/template-blank-wide.php'      => __( '本文のみ 1カラム(ワイド)', 'ystandard' ),
			'page-template/template-blank.php'           => __( '本文のみ 1カラム', 'ystandard' ),
			'page-template/template-one-column-wide.php' => __( '1カラム(ワイド)', 'ystandard' ),
			'page-template/template-one-column.php'      => __( '1カラム', 'ystandard' ),
		];
	}
}

new Custom_Template();
