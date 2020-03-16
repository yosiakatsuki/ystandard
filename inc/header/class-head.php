<?php
/**
 * HTML HEADタグ内の処理
 *
 * @package ystandard
 * @author  yosiakatsuki
 * @license GPL-2.0+
 */

namespace ystandard;

/**
 * Class Head
 *
 * @package ystandard
 */
class Head {

	/**
	 * Head constructor.
	 */
	public function __construct() {
		add_action( 'wp_head', [ $this, 'google_analytics' ], 99 );
		add_action( 'wp_head', [ $this, 'meta_noindex' ] );
		add_action( 'wp_head', [ $this, 'meta_description' ] );
		add_action( 'wp_head', [ $this, 'pingback_url' ] );
	}

	/**
	 * メタディスクリプション作成
	 *
	 * @return string
	 */
	public static function get_meta_description() {
		$length = ys_get_option_by_int( 'ys_option_meta_description_length', 80 );
		$dscr   = '';

		if ( ys_is_top_page() ) {
			/**
			 * TOPページの場合
			 */
			$dscr = trim( ys_get_option( 'ys_wp_site_description', '' ) );
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
		if ( '' !== $dscr ) {
			$dscr = mb_substr( $dscr, 0, $length );
		}

		return apply_filters(
			'ys_get_meta_description',
			wp_strip_all_tags( $dscr, true )
		);
	}

	/**
	 * ピンバックURLの出力
	 */
	public function pingback_url() {
		if ( is_singular() && pings_open( get_queried_object() ) ) {
			echo '<link rel="pingback" href="' . get_bloginfo( 'pingback_url' ) . '" />' . PHP_EOL;
		}
	}

	/**
	 * メタディスクリプションタグ出力
	 */
	public function meta_description() {
		if ( ! ys_get_option_by_bool( 'ys_option_create_meta_description', true ) ) {
			return;
		}
		if ( is_single() || is_page() ) {
			if ( ys_to_bool( ys_get_post_meta( 'ys_hide_meta_dscr' ) ) ) {
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
	 * Google Analyticsタグ出力
	 */
	public function google_analytics() {
		/**
		 * 管理画面ログイン中はGAタグを出力しない
		 */
		if ( ! Conditional_Tag::is_enable_google_analytics() ) {
			return;
		}
		/**
		 * トラッキング タイプ
		 */
		ys_get_template_part(
			'template-parts/parts/ga',
			ys_get_option( 'ys_ga_tracking_type', 'gtag' )
		);
	}

	/**
	 * Noindex処理
	 */
	public function meta_noindex() {
		$noindex = false;
		if ( is_404() ) {
			/**
			 * 404ページをnoindex
			 */
			$noindex = true;
		} elseif ( is_search() ) {
			/**
			 * 検索結果をnoindex
			 */
			$noindex = true;
		} elseif ( is_category() && ys_get_option_by_bool( 'ys_archive_noindex_category', false ) ) {
			/**
			 * カテゴリーページのnoindex設定がされていればnoindex
			 */
			$noindex = true;
		} elseif ( is_tag() && ys_get_option_by_bool( 'ys_archive_noindex_tag', true ) ) {
			/**
			 * カテゴリーページのnoindex設定がされていればnoindex
			 */
			$noindex = true;
		} elseif ( is_author() && ys_get_option_by_bool( 'ys_archive_noindex_author', true ) ) {
			/**
			 * カテゴリーページのnoindex設定がされていればnoindex
			 */
			$noindex = true;

		} elseif ( is_date() && ys_get_option_by_bool( 'ys_archive_noindex_date', true ) ) {
			/**
			 * カテゴリーページのnoindex設定がされていればnoindex
			 */
			$noindex = true;
		} elseif ( is_single() || is_page() ) {
			if ( '1' === ys_get_post_meta( 'ys_noindex' ) ) {
				/**
				 * 投稿・固定ページでnoindex設定されていればnoindex
				 */
				$noindex = true;
			}
		}
		$noindex = apply_filters( 'ys_the_noindex', $noindex );
		/**
		 * Noindex出力
		 */
		if ( $noindex ) {
			echo '<meta name="robots" content="noindex,follow">' . PHP_EOL;
		}
	}
}

new Head();
