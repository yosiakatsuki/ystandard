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
		add_action( 'customize_register', [ $this, 'customize_register' ] );
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
		if ( '' !== $dscr ) {
			$dscr = mb_substr( $dscr, 0, $length );
		}

		$dscr = apply_filters( 'ys_get_meta_description', $dscr );

		return Utility::get_plain_text( $dscr );
	}

	/**
	 * カスタマイザー追加
	 *
	 * @param \WP_Customize_Manager $wp_customize カスタマイザー.
	 */
	public function customize_register( $wp_customize ) {
		$customizer = new Customize_Control( $wp_customize );
		/**
		 * SEO : meta description
		 */
		$customizer->add_section(
			[
				'section'  => 'ys_meta_description',
				'title'    => 'meta description設定',
				'priority' => 1,
				'panel'    => SEO::PANEL_NAME,
			]
		);
		$customizer->add_section_label(
			'meta description自動生成',
			[
				'description' => Admin::manual_link( 'manual/meta-description' ),
			]
		);
		// 自動生成する.
		$customizer->add_checkbox(
			[
				'id'        => 'ys_option_create_meta_description',
				'default'   => 1,
				'transport' => 'postMessage',
				'label'     => 'meta descriptionを自動生成する',
			]
		);
		// 抜粋文字数.
		$customizer->add_number(
			[
				'id'        => 'ys_option_meta_description_length',
				'default'   => 80,
				'transport' => 'postMessage',
				'label'     => 'meta descriptionに使用する文字数',
			]
		);
		/**
		 * TOPページ
		 */
		$customizer->add_section_label(
			'TOPページのmeta description',
			[
				'description' => Admin::manual_link( 'manual/top-meta-description' ),
			]
		);
		$customizer->add_plain_textarea(
			[
				'id'          => 'ys_wp_site_description',
				'default'     => '',
				'transport'   => 'postMessage',
				'label'       => 'TOPページのmeta description',
				'description' => '※HTMLタグ・改行は削除されます',
			]
		);
	}
}

new Meta_Description();
