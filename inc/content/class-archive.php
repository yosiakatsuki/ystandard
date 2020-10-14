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
	 * アクション・フィルターの登録
	 */
	public function register() {
		add_action( 'customize_register', [ $this, 'customize_register' ] );
		add_filter( 'get_the_archive_description', [ $this, 'archive_description' ], 999 );
	}

	/**
	 * アーカイブタイプ取得
	 *
	 * @return string
	 */
	public static function get_archive_type() {
		return apply_filters(
			'ys_get_archive_type',
			Option::get_option( 'ys_archive_type', 'card' )
		);
	}

	/**
	 * アーカイブ画像の縦横比取得
	 *
	 * @return string
	 */
	public static function get_archive_image_ratio() {
		return apply_filters( 'ys_archive_image_ratio', 'is-16-9' );
	}

	/**
	 * アーカイブデフォルト画像
	 *
	 * @param string $class          Class.
	 * @param string $icon_class     Icon Class.
	 * @param string $thumbnail_size Thumbnail size.
	 *
	 * @return string
	 */
	public static function get_archive_default_image( $class = 'archive__no-img', $icon_class = 'archive__image', $thumbnail_size = 'full' ) {
		$image = '<div class="' . $class . '">' . Icon::get_icon( 'image', $icon_class ) . '</div>';

		$thumbnail_size = apply_filters( 'ys_get_archive_default_image_size', $thumbnail_size );

		return apply_filters( 'ys_get_archive_default_image', $image, $class, $icon_class, $thumbnail_size );
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

		$format      = '<div class="archive__date">%s<time class="updated" datetime="%s">%s</time></div>';
		$icon        = Icon::get_icon( 'calendar' );
		$date_time   = get_the_date( 'Y-m-d' );
		$date_format = get_option( 'date_format' );
		$date_label  = get_the_date( $date_format );
		$date        = sprintf( $format, $icon, $date_time, $date_label );

		return apply_filters( 'ys_get_archive_detail_date', $date, $format, $icon, $date_format );

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

		$taxonomy = apply_filters( 'ys_archive_detail_taxonomy', self::get_archive_meta_taxonomy() );
		$term     = get_the_terms( false, $taxonomy );
		if ( is_wp_error( $term ) || empty( $term ) ) {
			return '';
		}

		return sprintf(
			'<div class="archive__category %s">%s%s</div>',
			esc_attr( $taxonomy ) . '--' . esc_attr( $term[0]->slug ),
			Utility::get_taxonomy_icon( $taxonomy ),
			esc_html( $term[0]->name )
		);
	}

	/**
	 * アーカイブ用タクソノミー情報取得
	 *
	 * @return bool|string
	 */
	public static function get_archive_meta_taxonomy() {
		$taxonomy = false;

		if ( is_tax() ) {
			$taxonomy = get_query_var( 'taxonomy' );
		}
		if ( is_category() ) {
			$taxonomy = 'category';
		}
		if ( is_tag() ) {
			$taxonomy = 'post_tag';
		}

		if ( ! $taxonomy ) {
			$taxonomies = get_the_taxonomies();
			if ( ! $taxonomies ) {
				return false;
			}
			$taxonomy = array_key_first( $taxonomies );
			if ( 'post' === get_post_type( get_the_ID() ) ) {
				$taxonomy = 'category';
			}
		}

		return $taxonomy;
	}

	/**
	 * 説明 2ページ目以降削除
	 *
	 * @param string $description 説明文.
	 *
	 * @return string
	 */
	public function archive_description( $description ) {
		if ( get_query_var( 'paged' ) ) {
			$description = '';
		}

		return $description;
	}

	/**
	 * アーカイブページ設定
	 *
	 * @param \WP_Customize_Manager $wp_customize カスタマイザー.
	 */
	public function customize_register( $wp_customize ) {
		$customizer = new Customize_Control( $wp_customize );
		$customizer->add_section(
			[
				'section'     => 'ys_design_archive',
				'title'       => 'アーカイブページ',
				'panel'       => Design::PANEL_NAME,
				'priority'    => 120,
				'description' => Admin::manual_link( 'archive-layout' ),
			]
		);
		$customizer->add_section_label( 'レイアウト' );
		/**
		 * 表示カラム数
		 */
		$col1 = Customizer::get_assets_dir_uri( '/design/column-type/col-1.png' );
		$col2 = Customizer::get_assets_dir_uri( '/design/column-type/col-2.png' );
		$img  = '<img src="%s" alt="" width="100" height="100" />';
		$customizer->add_image_label_radio(
			[
				'id'          => 'ys_archive_layout',
				'default'     => '2col',
				'label'       => 'ページレイアウト',
				'description' => 'アーカイブページの表示レイアウト',
				'choices'     => [
					'2col' => sprintf( $img, $col2 ),
					'1col' => sprintf( $img, $col1 ),
				],
			]
		);
		/**
		 * 一覧タイプ
		 */
		$list = Customizer::get_assets_dir_uri( '/design/archive/list.png' );
		$card = Customizer::get_assets_dir_uri( '/design/archive/card.png' );
		$img  = '<img src="%s" alt="" width="100" height="100" />';
		$customizer->add_image_label_radio(
			[
				'id'          => 'ys_archive_type',
				'default'     => 'card',
				'label'       => '一覧レイアウト',
				'description' => '記事一覧の表示タイプ',
				'choices'     => [
					'card' => sprintf( $img, $card ),
					'list' => sprintf( $img, $list ),
				],
			]
		);
		$customizer->add_section_label( '表示・非表示設定' );
		// 投稿日.
		$customizer->add_checkbox(
			[
				'id'      => 'ys_show_archive_publish_date',
				'default' => 1,
				'label'   => '投稿日を表示する',
			]
		);
		// カテゴリー.
		$customizer->add_checkbox(
			[
				'id'      => 'ys_show_archive_category',
				'default' => 1,
				'label'   => 'カテゴリーを表示する',
			]
		);
		// 概要.
		$customizer->add_checkbox(
			[
				'id'      => 'ys_show_archive_description',
				'default' => 1,
				'label'   => '概要を表示する',
			]
		);
		$customizer->add_number(
			[
				'id'      => 'ys_option_excerpt_length',
				'default' => 80,
				'label'   => '概要文の文字数',
			]
		);
	}
}

$class_archive = new Archive();
$class_archive->register();
