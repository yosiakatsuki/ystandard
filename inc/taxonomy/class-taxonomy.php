<?php
/**
 * タクソノミー関連
 *
 * @package ystandard
 * @author  yosiakatsuki
 * @license GPL-2.0+
 */

namespace ystandard;

defined( 'ABSPATH' ) || die();

/**
 * Class Taxonomy
 *
 * @package ystandard
 */
class Taxonomy {

	/**
	 * Taxonomy constructor.
	 */
	public function __construct() {
		add_action( 'after_setup_theme', [ $this, 'taxonomy_description_filter' ] );
	}

	/**
	 * タクソノミー説明の処理カスタマイズ
	 */
	public function taxonomy_description_filter() {
		// サニタイズのフィルターを投稿と同じにする.
		remove_filter( 'pre_term_description', 'wp_filter_kses' );
		add_filter( 'pre_term_description', 'wp_filter_post_kses' );
		// ショートコードを有効にする（管理画面では展開しない）.
		if ( ! is_admin() ) {
			add_filter( 'term_description', 'do_shortcode' );
		}
	}

	/**
	 * ターム用アイコン
	 *
	 * @param string $taxonomy Taxonomy.
	 *
	 * @return string
	 */
	public static function get_taxonomy_icon( $taxonomy ) {
		$icon_name = 'folder';
		if ( ! empty( $taxonomy ) ) {
			if ( ! is_taxonomy_hierarchical( $taxonomy ) ) {
				$icon_name = 'tag';
			}
		}

		$icon_name = apply_filters( "ys_get_taxonomy_icon_name_{$taxonomy}", $icon_name );

		return apply_filters(
			'ys_get_taxonomy_icon',
			Icon::get_icon( $icon_name ),
			$taxonomy,
			$icon_name
		);
	}
}

new Taxonomy();
