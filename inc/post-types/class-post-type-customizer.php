<?php
/**
 * 投稿タイプ カスタマイザー基底クラス
 *
 * @package ystandard
 * @author  yosiakatsuki
 * @license GPL-2.0+
 */

namespace ystandard;

use ystandard\utils\Convert;
use ystandard\utils\Taxonomy;

defined( 'ABSPATH' ) || die();

/**
 * Class Post_Type_Customizer
 */
class Post_Type_Customizer {

	/**
	 * カスタマイザー
	 *
	 * @var Customize_Control
	 */
	private $customizer;

	/**
	 * 投稿タイプ
	 *
	 * @var string
	 */
	private $post_type;

	/**
	 * 投稿タイプラベル
	 *
	 * @var string
	 */
	private $label;

	/**
	 * 優先度
	 *
	 * @var int
	 */
	private $priority;

	/**
	 * 投稿タイプオブジェクト
	 *
	 * @var \WP_Post_Type
	 */
	private $post_type_object;

	/**
	 * 投稿タイプに関連付けられたタクソノミー
	 *
	 * @var array
	 */
	private $post_type_taxonomies;

	/**
	 * Post_Type_Customizer constructor.
	 *
	 * @param \WP_Customize_Manager $wp_customize カスタマイザー.
	 * @param string $post_type 投稿タイプ.
	 * @param string $label ラベル.
	 * @param int $priority 優先度.
	 */
	public function __construct( $wp_customize, $post_type, $label, $priority ) {
		$this->customizer = new Customize_Control( $wp_customize );
		$this->post_type  = $post_type;
		$this->label      = $label;
		$this->priority   = $priority;

		$this->post_type_object     = get_post_type_object( $post_type );
		$this->post_type_taxonomies = Taxonomy::get_post_type_taxonomies( $post_type );

		$this->register_section();
		$this->register_settings();
	}

	/**
	 * 投稿タイプにアイキャッチ画像が設定されているか確認
	 *
	 * @return bool
	 */
	private function has_post_thumbnail() {
		if ( empty( $this->post_type_object ) ) {
			return false;
		}
		// $this->post_type_object->supports があるか確認.
		if ( isset( $this->post_type_object->supports ) && is_array( $this->post_type_object->supports ) ) {
			return in_array( 'thumbnail', $this->post_type_object->supports, true );
		}

		// supports がない場合はデフォルトでアイキャッチ画像が有効とする.
		return true;
	}

	/**
	 * 投稿タイプにアーカイブがあるか確認
	 *
	 * @return bool
	 */
	private function has_archive() {

		if ( 'post' === $this->post_type_object->name ) {
			return true;
		}

		if ( ! empty( $this->post_type_object ) && isset( $this->post_type_object->has_archive ) ) {
			return $this->post_type_object->has_archive;
		}

		return false;
	}

	/**
	 * セクション登録
	 */
	private function register_section() {
		$this->customizer->add_section(
			[
				'section'     => "ys_post_type_option_{$this->post_type}",
				'title'       => '[ys]' . __( '投稿タイプ設定 - ', 'ystandard' ) . "{$this->label}",
				'priority'    => $this->priority,
				'description' => Admin::manual_link( 'manual/post_type_layout' ),
			]
		);
	}

	/**
	 * 設定登録
	 */
	private function register_settings() {
		// レイアウト設定.
		$this->add_layout_settings();

		// 本文エリア背景色設定.
		$this->add_content_background_settings();

		// 記事上部設定（階層なしタイプ用）.
		$this->add_header_settings();

		// 記事下部設定（階層なしタイプ用）.
		$this->add_footer_settings();
	}

	/**
	 * レイアウト設定を追加
	 */
	private function add_layout_settings() {
		$this->customizer->add_section_label( 'レイアウト' );

		// ページレイアウト.
		$col1 = Customizer::get_assets_dir_uri( '/design/column-type/col-1.png' );
		$col2 = Customizer::get_assets_dir_uri( '/design/column-type/col-2.png' );
		$img  = '<img src="%s" alt="" width="100" height="100" />';

		$this->customizer->add_image_label_radio(
			[
				'id'          => "ys_{$this->post_type}_layout",
				'default'     => '1col',
				'label'       => __( 'ページレイアウト', 'ystandard' ),
				'description' => "{$this->label}" . __( 'ページの表示レイアウト', 'ystandard' ),
				'choices'     => [
					'1col' => sprintf( $img, $col1 ),
					'2col' => sprintf( $img, $col2 ),
				],
			]
		);
	}

	/**
	 * 本文エリア背景色設定を追加
	 */
	private function add_content_background_settings() {
		$this->customizer->add_section_label( '本文エリア背景色' );

		$this->customizer->add_checkbox(
			[
				'id'          => "ys_{$this->post_type}_use_content_bg",
				'default'     => 0,
				'label'       => __( '本文エリア背景色を使用する', 'ystandard' ),
				'description' => __( '本文エリアの背景色設定を有効にします。', 'ystandard' ),
			]
		);

		$this->customizer->add_color(
			[
				'id'              => "ys_{$this->post_type}_content_bg",
				'default'         => '',
				'label'           => __( '本文エリア背景色', 'ystandard' ),
				'description'     => __( '本文エリアの背景色設定。', 'ystandard' ) . '<br>' . self::get_xs_text(
						__( '※本文の背景色を設定した場合、全幅設定をしたブロックはデスクトップサイズの表示時に画面の横幅いっぱいに広がらず、コンテンツ本文領域が上限となります。', 'ystandard' )
					),
				'active_callback' => function () {
					return Convert::to_bool( get_option( "ys_{$this->post_type}_use_content_bg", 0 ) );
				},
			]
		);
	}

	/**
	 * 記事上部設定を追加
	 */
	private function add_header_settings() {
		$this->customizer->add_section_label( '記事上部' );

		if ( $this->has_post_thumbnail() ) {
			// アイキャッチ画像の表示設定.
			$this->customizer->add_label(
				[
					'id'    => "ys_show_{$this->post_type}_header_thumbnail_label",
					'label' => __( 'アイキャッチ画像の表示設定', 'ystandard' ),
				]
			);

			$this->customizer->add_checkbox(
				[
					'id'      => "ys_show_{$this->post_type}_header_thumbnail",
					'default' => 1,
					'label'   => __( 'アイキャッチ画像を表示する', 'ystandard' ),
				]
			);

			// アイキャッチ画像表示タイプ.
			$default = Customizer::get_assets_dir_uri( '/design/eye-catch/default.png' );
			$full    = Customizer::get_assets_dir_uri( '/design/eye-catch/full.png' );
			$img     = '<img src="%s" alt="" width="100" height="100" />';

			$this->customizer->add_image_label_radio(
				[
					'id'      => "ys_{$this->post_type}_post_thumbnail_type",
					'default' => 'default',
					'label'   => __( 'アイキャッチ画像の表示タイプ', 'ystandard' ),
					'choices' => [
						'default' => sprintf( $img, $default ),
						'full'    => sprintf( $img, $full ),
					],
				]
			);
		}

		// 投稿日・更新日の表示.
		$this->customizer->add_select(
			[
				'id'      => "ys_show_{$this->post_type}_publish_date",
				'default' => 'both',
				'label'   => __( '投稿日・更新日の表示タイプ', 'ystandard' ),
				'choices' => [
					'none'    => __( '表示しない', 'ystandard' ),
					'publish' => __( '投稿日のみ', 'ystandard' ),
					'both'    => __( '投稿日・更新日', 'ystandard' ),
					'update'  => __( '更新日のみ', 'ystandard' ),
				],
			]
		);

		// カテゴリー情報の表示.
		if ( $this->post_type_taxonomies ) {
			$choices = [
				'none' => __( '表示しない', 'ystandard' ),
			];
			foreach ( $this->post_type_taxonomies as $taxonomy ) {
				$choices[ $taxonomy->name ] = $taxonomy->label;
			}
			$this->customizer->add_select(
				[
					'id'          => "ys_{$this->post_type}_header_taxonomy",
					'default'     => 'none',
					'label'       => __( 'カテゴリー情報の表示設定', 'ystandard' ),
					'description' => self::get_xs_text( __( '※ページ上部では1種類かつ1件のみ表示されます', 'ystandard' ) ),
					'choices'     => $choices,
				]
			);
		}

		// SNSシェアボタンの表示設定.
		$this->customizer->add_select(
			[
				'id'          => "ys_{$this->post_type}_share_button_type_header",
				'default'     => 'none',
				'label'       => __( 'SNSシェアボタン表示タイプ', 'ystandard' ),
				'description' => self::get_xs_text( __( '※表示するシェアボタンの種類は「[ys]SNS」から設定できます', 'ystandard' ) ),
				'choices'     => Share_Button::get_share_button_type(),
			]
		);
	}


	/**
	 * 記事下部設定を追加
	 */
	private function add_footer_settings() {
		$this->customizer->add_section_label( '記事下部' );

		// SNSシェアボタンの表示設定.
		$this->customizer->add_select(
			[
				'id'          => "ys_{$this->post_type}_share_button_type_footer",
				'default'     => 'circle',
				'label'       => __( 'SNSシェアボタン表示タイプ', 'ystandard' ),
				'description' => self::get_xs_text( __( '※表示するシェアボタンの種類は「[ys]SNS」から設定できます', 'ystandard' ) ),
				'choices'     => Share_Button::get_share_button_type(),
			]
		);

		if ( $this->post_type_taxonomies ) {
			$this->customizer->add_label(
				[
					'id'          => "ys_{$this->post_type}_footer_taxonomy_label",
					'label'       => __( 'カテゴリー・タグ情報の選択', 'ystandard' ),
					'description' => self::get_xs_text(
						__( '※選択したカテゴリー・タグ情報（タクソノミー）を記事下に表示します。', 'ystandard' )
					),
				]
			);
			foreach ( $this->post_type_taxonomies as $taxonomy ) {
				// タクソノミー情報の選択.
				$this->customizer->add_checkbox(
					[
						'id'      => "ys_{$this->post_type}_footer_taxonomy_{$taxonomy->name}",
						'default' => 1,
						'label'   => $taxonomy->label,
					]
				);
			}
		}

		$this->customizer->add_label(
			[
				'id'    => "ys_show_{$this->post_type}_author_label",
				'label' => __( '著者情報の表示', 'ystandard' ),
			]
		);
		// 著者情報.
		$this->customizer->add_checkbox(
			[
				'id'      => "ys_show_{$this->post_type}_author",
				'default' => 1,
				'label'   => __( '著者情報', 'ystandard' ),
			]
		);

		// 投稿タイプがアーカイブページを持つ場合.
		if ( $this->has_archive() ) {
			$this->customizer->add_label(
				[
					'id'    => "ys_show_{$this->post_type}_related_label",
					'label' => __( '関連記事・前後の記事', 'ystandard' ),
				]
			);
			// 関連記事.
			$this->customizer->add_checkbox(
				[
					'id'      => "ys_show_{$this->post_type}_related",
					'default' => 1,
					'label'   => '関連記事',
				]
			);

			// 次の記事・前の記事.
			$this->customizer->add_checkbox(
				[
					'id'      => "ys_show_{$this->post_type}_paging",
					'default' => 1,
					'label'   => '次の記事・前の記事',
				]
			);
		}
	}


	/**
	 * カスタマイザー用のXSサイズの説明テキストを取得
	 *
	 * @param string $text 説明テキスト.
	 *
	 * @return string
	 */
	private static function get_xs_text( $text ) {
		return Customize_Control::get_xs_description_text( $text );
	}
}
