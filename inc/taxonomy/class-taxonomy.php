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


class Taxonomy {
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
