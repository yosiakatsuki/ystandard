<?php
/**
 * アーカイブ
 *
 * @package ystandard
 * @author  yosiakatsuki
 * @license GPL-2.0+
 */

namespace ystandard;

/**
 * Class Archive
 *
 * @package ystandard
 */
class Archive {

	/**
	 * アーカイブ画像の縦横比取得
	 *
	 * @return string
	 */
	public static function get_archive_image_ratio() {
		return apply_filters( 'ys_archive_image_ratio', 'is-16-9' );
	}

	/**
	 * アーカイブメタ情報取得
	 */
	public static function the_archive_meta() {

		$date = self::get_archive_detail_date();
		$cat  = self::get_archive_detail_category();

		if ( empty( $date ) && empty( $cat ) ) {
			return;
		}
		printf(
			'<div class="archive__meta">%s%s</div>',
			$date,
			$cat
		);
	}

	/**
	 * 投稿抜粋
	 *
	 * @param int $length Length.
	 *
	 * @return string
	 */
	public static function the_archive_description( $length = 0 ) {
		if ( ! Option::get_option_by_bool( 'ys_show_archive_description', true ) ) {
			return '';
		}
		if ( 0 === $length ) {
			$length = Option::get_option_by_int( 'ys_option_excerpt_length', 40 );
		}
		$excerpt = Content::get_custom_excerpt( '…', $length );
		if ( empty( $excerpt ) ) {
			return '';
		}
		printf(
			'<p class="archive__excerpt">%s</p>',
			$excerpt
		);
	}

	/**
	 * 日付取得
	 *
	 * @return string
	 */
	public static function get_archive_detail_date() {

		if ( ! Option::get_option_by_bool( 'ys_show_archive_publish_date', true ) ) {
			return '';
		}

		return sprintf(
			'<div class="archive__date">%s<time class="updated" datetime="%s">%s</time></div>',
			Icon::get_icon( 'calendar' ),
			get_the_date( 'Y-m-d' ),
			get_the_date( get_option( 'date_format' ) )
		);

	}

	/**
	 * カテゴリー
	 *
	 * @return string
	 */
	public static function get_archive_detail_category() {

		if ( ! Option::get_option_by_bool( 'ys_show_archive_category', true ) ) {
			return '';
		}

		$taxonomy = apply_filters( 'ys_archive_detail_taxonomy', 'category' );
		$term     = get_the_terms( false, $taxonomy );
		if ( is_wp_error( $term ) || empty( $term ) ) {
			return '';
		}

		return sprintf(
			'<div class="archive__category">%s%s</div>',
			Icon::get_icon( 'folder' ),
			$term[0]->name
		);
	}
}
