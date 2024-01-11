<?php
/**
 * Meta Description.
 *
 * @package ystandard
 * @author  yosiakatsuki
 * @license GPL-2.0+
 */

namespace ystandard;

defined( 'ABSPATH' ) || die();

/**
 * Class Meta_Description
 *
 * @package ystandard
 */
class Meta_Description {

	/**
	 * Meta_Description constructor.
	 */
	public function __construct() {
		// @todo メタディスクリプションの出力移行.
		add_action( 'wp_head', [ $this, 'meta_description' ] );
	}

	/**
	 * メタディスクリプションタグ出力
	 */
	public function meta_description() {
		if ( ! Option::get_option_by_bool( 'ys_option_create_meta_description', true ) ) {
			return;
		}
		if ( is_single() || is_page() ) {
			if ( Utility::to_bool( Content::get_post_meta( 'ys_hide_meta_dscr' ) ) ) {
				return;
			}
		}
		/**
		 * Metaタグの作成
		 */
		$dscr = self::get_meta_description();
		if ( '' !== $dscr ) {
			echo '<meta name="description" content="' . $dscr . '" />' . PHP_EOL;
		}
	}

	/**
	 * メタディスクリプション作成
	 *
	 * @return string
	 */
	public static function get_meta_description() {
		$length = Option::get_option_by_int( 'ys_option_meta_description_length', 80 );
		$dscr   = '';

		if ( Template::is_top_page() ) {
			/**
			 * TOPページの場合
			 */
			$dscr = trim( Option::get_option( 'ys_wp_site_description', '' ) );
		} elseif ( is_category() && ! is_paged() ) {
			/**
			 * カテゴリー
			 */
			$dscr = category_description();
		} elseif ( is_tag() && ! is_paged() ) {
			/**
			 * タグ
			 */
			$dscr = tag_description();
		} elseif ( is_tax() ) {
			/**
			 * その他タクソノミー
			 */
			$taxonomy = get_query_var( 'taxonomy' );
			$term     = get_term_by( 'slug', get_query_var( 'term' ), $taxonomy );
			$dscr     = term_description( $term->term_id, $taxonomy );
		} elseif ( is_singular() ) {
			/**
			 * 投稿ページ
			 */
			if ( ! get_query_var( 'paged' ) ) {
				$dscr = Content::get_custom_excerpt( '', $length );
			}
		}

		$dscr = apply_filters( 'ys_get_meta_description', $dscr );

		return Utility::get_plain_text( $dscr );
	}

}

new Meta_Description();
