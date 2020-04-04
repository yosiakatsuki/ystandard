<?php
/**
 * テーマカスタマイザーコントロール追加
 *
 * @package ystandard
 * @author  yosiakatsuki
 * @license GPL-2.0+
 */

namespace ystandard;

/**
 * Class Customizer
 */
class Customizer {

	/**
	 * パネルリスト
	 */
	const PANEL_PRIORITY = [
		'ys_info_bar'           => 1000,
		'ys_design'             => 1010,
		'ys_sns'                => 1100,
		'ys_seo'                => 1110,
		'ys_performance_tuning' => 1120,
		'ys_advertisement'      => 1130,
		'ys_amp'                => 1300,
	];

	/**
	 * カスタマイザー
	 *
	 * @var \WP_Customize_Manager
	 */
	private $_wp_customize = null;

	/**
	 * YS_Customize_Register constructor.
	 */
	public function __construct() {
		add_action( 'customize_register', [ $this, 'customize_register' ] );
		add_action( 'customize_preview_init', [ $this, 'preview_init' ], 999 );
		add_action( Enqueue_Utility::FILTER_INLINE_CSS, [ $this, 'preview_inline_css' ], 999 );
		add_action( 'customize_controls_print_styles', [ $this, 'print_styles' ] );
		add_action( 'customize_controls_enqueue_scripts', [ $this, 'enqueue_scripts' ] );
	}

	/**
	 * パネル・セクションの優先度を取得
	 *
	 * @param string $key Panel or Section name.
	 *
	 * @return int
	 */
	public static function get_priority( $key ) {

		if ( isset( self::PANEL_PRIORITY[ $key ] ) ) {
			return self::PANEL_PRIORITY[ $key ];
		}

		return 1000;
	}

	/**
	 * テーマカスタマイザープレビュー用JS
	 *
	 * @return void
	 */
	public function preview_init() {
		wp_enqueue_script(
			'ys-customize-preview-js',
			get_template_directory_uri() . '/js/admin/customizer-preview.js',
			[ 'jquery', 'customize-preview' ],
			date_i18n( 'YmdHis' ),
			true
		);
	}

	/**
	 * プレビュー用CSS
	 *
	 * @param string $css CSS.
	 *
	 * @return string
	 */
	public function preview_inline_css( $css ) {
		if ( ! is_customize_preview() ) {
			return $css;
		}
		// ヘッダー固定設定用.
		$css .= '
		.header-height-info {
			position: absolute;
			top:0;
			left:0;
			padding:.25em 1em;
			background-color:rgba(0,0,0,.7);
			font-size:.7rem;
			color:#fff;
			z-index:99;
		}';
		// サイドバー表示用.
		if ( Option::get_option_by_bool( 'ys_show_sidebar_mobile', false ) ) {
			$css .= Enqueue_Styles::add_media_query(
				'.is-customize-preview .sidebar {display:none;}',
				'',
				'md'
			);
		}

		return $css;
	}

	/**
	 * テーマカスタマイザー用JS
	 *
	 * @return void
	 */
	public function enqueue_scripts() {
		wp_enqueue_script(
			'ys-customize-controls-js',
			get_template_directory_uri() . '/js/admin/customizer-control.js',
			[ 'customize-controls', 'jquery' ],
			Utility::get_ystandard_version(),
			true
		);
	}


	/**
	 * 管理画面-テーマカスタマイザーページでのスタイルシートの読み込み
	 *
	 * @param string $hook_suffix suffix.
	 *
	 * @return void
	 */
	public function print_styles( $hook_suffix ) {
		wp_enqueue_style(
			'ys-customizer',
			get_template_directory_uri() . '/css/customizer.css',
			[],
			Utility::get_ystandard_version()
		);
	}


	/**
	 * カスタマイザー追加
	 *
	 * @param \WP_Customize_Manager $wp_customize カスタマイザー.
	 */
	public function customize_register( $wp_customize ) {
		$this->_wp_customize = $wp_customize;

		$customizer = new Customize_Control( $wp_customize );
		$customizer->set_refresh( 'background_image' );
		$customizer->set_refresh( 'background_preset' );
		$customizer->set_refresh( 'background_size' );
		$customizer->set_refresh( 'background_repeat' );
		$customizer->set_refresh( 'background_attachment' );
		$customizer->set_refresh( 'background_image' );
		/**
		 * WP標準の設定を削除
		 */
		$wp_customize->remove_setting( 'background_color' );
		$wp_customize->remove_section( 'colors' );
		$wp_customize->remove_control( 'display_header_text' );

		/**
		 * お知らせバー
		 */
		$this->info_bar();
		/**
		 * [ys]デザイン
		 */
		$this->design_breadcrumb();
		$this->design_mobile();
		$this->design_post();
		$this->design_page();
		$this->design_archive();
		$this->design_one_column_template();
		$this->design_icon_font();

		/**
		 * 拡張機能
		 */
		$this->ystandard_extension();
	}

	/**
	 * お知らせバー
	 */
	private function info_bar() {
		$ys_customizer = new Customize_Control( $this->_wp_customize );

		/**
		 * セクション追加
		 */
		$ys_customizer->add_section(
			[
				'section'     => 'ys_customizer_section_info_bar',
				'title'       => '[ys]お知らせバー設定',
				'description' => 'ヘッダー下に表示されるお知らせバーの設定',
				'priority'    => 1000,
			]
		);

		// お知らせバーテキスト.
		$ys_customizer->add_text(
			[
				'id'      => 'ys_info_bar_text',
				'default' => '',
				'label'   => 'お知らせテキスト',
			]
		);
		// お知らせURL.
		$ys_customizer->add_url(
			[
				'id'      => 'ys_info_bar_url',
				'default' => '',
				'label'   => 'お知らせテキストリンク',
			]
		);
		// お知らせリンクを新しいタブで開く.
		$ys_customizer->add_checkbox(
			[
				'id'      => 'ys_info_bar_external',
				'default' => 0,
				'label'   => 'お知らせリンクを新しいタブで開く',
			]
		);
		// テキストカラー.
		$ys_customizer->add_color(
			[
				'id'      => 'ys_info_bar_text_color',
				'default' => '#222222',
				'label'   => 'お知らせバー文字色',
			]
		);
		// 背景色カラー.
		$ys_customizer->add_color(
			[
				'id'      => 'ys_info_bar_bg_color',
				'default' => '#f1f1f3',
				'label'   => 'お知らせバー背景色',
			]
		);
		// お知らせテキストを太字にする.
		$ys_customizer->add_checkbox(
			[
				'id'      => 'ys_info_bar_text_bold',
				'default' => 1,
				'label'   => 'お知らせテキストを太字にする',
			]
		);
	}


	/**
	 * デザイン -> パンくずリスト
	 */
	private function design_breadcrumb() {
		$ys_customizer = new Customize_Control( $this->_wp_customize );
		/**
		 * セクション追加
		 */
		$ys_customizer->add_section(
			[
				'section'     => 'ys_customizer_section_breadcrumb_design',
				'title'       => 'パンくずリスト設定',
				'description' => 'パンくずリストの表示設定',
				'panel'       => 'ys_customizer_panel_design',
			]
		);

		/**
		 * パンくずリスト表示位置
		 */
		$ys_customizer->add_radio(
			[
				'id'          => 'ys_breadcrumbs_position',
				'default'     => 'header',
				'label'       => 'パンくずリストの表示位置',
				'description' => '',
				'choices'     => [
					'header' => 'ヘッダー',
					'footer' => 'フッター',
					'none'   => '表示しない',
				],
			]
		);
		/**
		 * パンくずリストに「投稿ページ」を表示する
		 */
		if ( 'page' === get_option( 'show_on_front' ) && get_option( 'page_for_posts' ) ) {

			$ys_customizer->add_label(
				[
					'id'          => 'ys_show_page_for_posts_on_breadcrumbs_label',
					'label'       => 'パンくずリストの「投稿ページ」表示',
					'description' => 'パンくずリストに「設定」→「表示設定」→「ホームページの表示」で「投稿ページ」で指定したページを表示する。',
					'section'     => 'ys_customizer_section_archive',
				]
			);
			$ys_customizer->add_checkbox(
				[
					'id'      => 'ys_show_page_for_posts_on_breadcrumbs',
					'default' => 1,
					'label'   => 'パンくずリストに「投稿ページ」を表示する',
					'section' => 'ys_customizer_section_archive',
				]
			);
		}
	}

	/**
	 * デザイン -> モバイルページ
	 */
	private function design_mobile() {


	}

	/**
	 * デザイン -> ワンカラム
	 */
	private function design_one_column_template() {
		$ys_customizer = new Customize_Control( $this->_wp_customize );
		$ys_customizer->add_section(
			[
				'section'     => 'ys_customizer_section_one_column_template',
				'title'       => 'ワンカラムテンプレート設定',
				'description' => 'ワンカラムテンプレートの設定',
				'panel'       => 'ys_customizer_panel_design',
			]
		);
		/**
		 * ヘッダータイプ
		 */
		$assets_url = $this->get_assets_dir_uri();
		$row1       = $assets_url . '/design/one-col-template/full.png';
		$center     = $assets_url . '/design/one-col-template/normal.png';
		$img        = '<img src="%s" alt="" width="100" height="100" />';
		$ys_customizer->add_image_label_radio(
			[
				'id'          => 'ys_design_one_col_thumbnail_type',
				'default'     => 'normal',
				'label'       => 'アイキャッチ画像表示タイプ',
				'description' => 'アイキャッチ画像の表示タイプ',
				'choices'     => [
					'normal' => sprintf( $img, $center ),
					'full'   => sprintf( $img, $row1 ),
				],
			]
		);
		/**
		 * コンテンツタイプ
		 */
		$ys_customizer->add_radio(
			[
				'id'          => 'ys_design_one_col_content_type',
				'default'     => 'normal',
				'label'       => 'コンテンツタイプ',
				'description' => 'コンテンツ領域の横幅設定',
				'choices'     => [
					'normal' => 'ノーマル',
					'wide'   => 'ワイド',
				],
			]
		);
	}


	/**
	 * デザイン -> アイコンフォント設定
	 */
	private function design_icon_font() {
		$ys_customizer = new Customize_Control( $this->_wp_customize );
		$ys_customizer->add_section(
			[
				'section'     => 'ys_customizer_section_icon_fonts',
				'title'       => 'アイコンフォント設定',
				'description' => 'アイコンフォントに関する設定',
				'panel'       => 'ys_customizer_panel_design',
			]
		);
		/**
		 * アイコンフォント読み込み方式
		 */
		$ys_customizer->add_radio(
			[
				'id'          => 'ys_enqueue_icon_font_type',
				'default'     => 'js',
				'transport'   => 'postMessage',
				'label'       => 'アイコンフォント（Font Awesome）読み込み方式',
				'description' => 'Font Awesome読み込み方式を設定できます。',
				'choices'     => [
					'light' => '軽量版（テーマに必要な最低限のアイコンのみ読み込みます）',
					'js'    => 'JavaScript',
					'css'   => 'CSS',
					'kit'   => 'Font Awesome Kits(「Font Awesome Kits URL」の入力必須)',
					'none'  => '読み込まない(※表示が崩れる場合があります。プラグイン等でFont Awesomeを読み込む場合の設定)',
				],
			]
		);
		/**
		 * Font Awesome Kits設定
		 */
		$ys_customizer->add_url(
			[
				'id'          => 'ys_enqueue_icon_font_kit_url',
				'default'     => '',
				'transport'   => 'postMessage',
				'label'       => 'Font Awesome Kits URL',
				'description' => 'Font Awesome Kitsを使う場合のURL設定',
			]
		);
	}

	/**
	 * デザイン -> 投稿ページ
	 */
	private function design_post() {
		$ys_customizer = new Customize_Control( $this->_wp_customize );
		$ys_customizer->add_section(
			[
				'section' => 'ys_customizer_section_post',
				'title'   => '投稿ページ設定',
				'panel'   => 'ys_customizer_panel_design',
			]
		);
		/**
		 * 表示カラム数
		 */
		$assets_url = $this->get_assets_dir_uri();
		$col1       = $assets_url . '/design/column-type/col-1.png';
		$col2       = $assets_url . '/design/column-type/col-2.png';
		$img        = '<img src="%s" alt="" width="100" height="100" />';
		$ys_customizer->add_image_label_radio(
			[
				'id'          => 'ys_post_layout',
				'default'     => '2col',
				'label'       => 'レイアウト',
				'description' => '投稿ページの表示レイアウト<br>※デフォルトテンプレートの表示レイアウト',
				'section'     => 'ys_customizer_section_post',
				'choices'     => [
					'2col' => sprintf( $img, $col2 ),
					'1col' => sprintf( $img, $col1 ),
				],
			]
		);
		/**
		 * 記事上部表示設定
		 */
		$ys_customizer->add_label(
			[
				'id'      => 'ys_above_post_label',
				'label'   => '記事上部設定',
				'section' => 'ys_customizer_section_post',
			]
		);
		$ys_customizer->add_checkbox(
			[
				'id'          => 'ys_show_post_thumbnail',
				'default'     => 1,
				'label'       => 'アイキャッチ画像を表示する',
				'description' => '※投稿内の先頭にアイキャッチ画像を配置している場合、こちらの設定を無効にすることにより画像が2枚連続で表示されないようにします。（他ブログサービスからの引っ越してきた場合に役立つかもしれません）',
				'section'     => 'ys_customizer_section_post',
			]
		);
		/**
		 * 投稿日時を表示する
		 */
		$ys_customizer->add_checkbox(
			[
				'id'      => 'ys_show_post_publish_date',
				'default' => 1,
				'label'   => '投稿日・更新日を表示する',
				'section' => 'ys_customizer_section_post',
			]
		);
		/**
		 * 記事下表示設定
		 */
		$ys_customizer->add_label(
			[
				'id'          => 'ys_below_post_label',
				'label'       => '記事下表示設定',
				'description' => '※シェアボタンの表示・非表示は[SNS設定]→[SNSシェアボタン設定]から行って下さい',
				'section'     => 'ys_customizer_section_post',
			]
		);
		/**
		 * カテゴリー・タグ情報を表示する
		 */
		$ys_customizer->add_checkbox(
			[
				'id'      => 'ys_show_post_category',
				'default' => 1,
				'label'   => 'カテゴリー・タグ情報を表示する',
				'section' => 'ys_customizer_section_post',
			]
		);
		/**
		 * ブログフォローボックスを表示する
		 */
		$ys_customizer->add_checkbox(
			[
				'id'      => 'ys_show_post_follow_box',
				'default' => 1,
				'label'   => 'ブログフォローボックスを表示する',
				'section' => 'ys_customizer_section_post',
			]
		);
		/**
		 * 著者情報を表示する
		 */
		$ys_customizer->add_checkbox(
			[
				'id'      => 'ys_show_post_author',
				'default' => 1,
				'label'   => '著者情報を表示する',
				'section' => 'ys_customizer_section_post',
			]
		);
		/**
		 * 記事下に関連記事を出力する
		 */
		$ys_customizer->add_checkbox(
			[
				'id'      => 'ys_show_post_related',
				'default' => 1,
				'label'   => '記事下に関連記事を表示する',
				'section' => 'ys_customizer_section_post',
			]
		);
		/**
		 * 次の記事・前の記事のリンクを出力しない
		 */
		$ys_customizer->add_checkbox(
			[
				'id'      => 'ys_show_post_paging',
				'default' => 1,
				'label'   => '次の記事・前の記事のリンクを表示する',
				'section' => 'ys_customizer_section_post',
			]
		);
	}

	/**
	 * デザイン -> 固定ページ
	 */
	private function design_page() {
		$ys_customizer = new Customize_Control( $this->_wp_customize );
		$ys_customizer->add_section(
			[
				'section' => 'ys_customizer_section_page',
				'title'   => '固定ページ設定',
				'panel'   => 'ys_customizer_panel_design',
			]
		);
		/**
		 * 表示カラム数
		 */
		$assets_url = $this->get_assets_dir_uri();
		$col1       = $assets_url . '/design/column-type/col-1.png';
		$col2       = $assets_url . '/design/column-type/col-2.png';
		$img        = '<img src="%s" alt="" width="100" height="100" />';
		$ys_customizer->add_image_label_radio(
			[
				'id'          => 'ys_page_layout',
				'default'     => '2col',
				'label'       => 'レイアウト',
				'description' => '固定ページの表示レイアウト<br>※デフォルトテンプレートの表示レイアウト',
				'section'     => 'ys_customizer_section_page',
				'choices'     => [
					'2col' => sprintf( $img, $col2 ),
					'1col' => sprintf( $img, $col1 ),
				],
			]
		);
		/**
		 * 記事上部表示設定
		 */
		$ys_customizer->add_label(
			[
				'id'      => 'ys_above_page_label',
				'label'   => '記事上部設定',
				'section' => 'ys_customizer_section_page',
			]
		);
		/**
		 * アイキャッチ画像を表示する
		 */
		$ys_customizer->add_checkbox(
			[
				'id'          => 'ys_show_page_thumbnail',
				'default'     => 1,
				'label'       => 'アイキャッチ画像を表示する',
				'description' => '※投稿内の先頭にアイキャッチ画像を配置している場合、こちらの設定を無効にすることにより画像が2枚連続で表示されないようにします。（他ブログサービスからの引っ越してきた場合に役立つかもしれません）',
				'section'     => 'ys_customizer_section_page',
			]
		);
		/**
		 * 投稿日時を表示する
		 */
		$ys_customizer->add_checkbox(
			[
				'id'      => 'ys_show_page_publish_date',
				'default' => 1,
				'label'   => '投稿日・更新日を表示する',
				'section' => 'ys_customizer_section_page',
			]
		);
		/**
		 * 記事下表示設定
		 */
		$ys_customizer->add_label(
			[
				'id'          => 'ys_below_page_label',
				'label'       => '記事下表示設定',
				'description' => '※シェアボタンの表示・非表示は[SNS設定]→[SNSシェアボタン設定]から行って下さい',
				'section'     => 'ys_customizer_section_page',
			]
		);

		/**
		 * 著者情報を表示する
		 */
		$ys_customizer->add_checkbox(
			[
				'id'      => 'ys_show_page_author',
				'default' => 1,
				'label'   => '著者情報を表示する',
				'section' => 'ys_customizer_section_page',
			]
		);
	}

	/**
	 * デザイン -> アーカイブ
	 */
	private function design_archive() {
		$ys_customizer = new Customize_Control( $this->_wp_customize );
		$ys_customizer->add_section(
			[
				'section' => 'ys_customizer_section_archive',
				'title'   => 'アーカイブページ設定',
				'panel'   => 'ys_customizer_panel_design',
			]
		);
		/**
		 * 表示カラム数
		 */
		$assets_url = $this->get_assets_dir_uri();
		$col1       = $assets_url . '/design/column-type/col-1.png';
		$col2       = $assets_url . '/design/column-type/col-2.png';
		$img        = '<img src="%s" alt="" width="100" height="100" />';
		$ys_customizer->add_image_label_radio(
			[
				'id'          => 'ys_archive_layout',
				'default'     => '2col',
				'label'       => 'レイアウト',
				'description' => 'アーカイブページの表示レイアウト',
				'section'     => 'ys_customizer_section_archive',
				'choices'     => [
					'2col' => sprintf( $img, $col2 ),
					'1col' => sprintf( $img, $col1 ),
				],
			]
		);
		/**
		 * 一覧タイプ
		 */
		$assets_url = $this->get_assets_dir_uri();
		$list       = $assets_url . '/design/archive/list.png';
		$card       = $assets_url . '/design/archive/card.png';
		$img        = '<img src="%s" alt="" width="100" height="100" />';
		$ys_customizer->add_image_label_radio(
			[
				'id'          => 'ys_archive_type',
				'default'     => 'list',
				'label'       => '一覧タイプ',
				'description' => '記事一覧の表示タイプ',
				'section'     => 'ys_customizer_section_archive',
				'choices'     => [
					'list' => sprintf( $img, $list ),
					'card' => sprintf( $img, $card ),
				],
			]
		);
		/**
		 * 投稿日を表示する
		 */
		$ys_customizer->add_label(
			[
				'id'      => 'ys_show_archive_publish_date_label',
				'label'   => '投稿日の表示',
				'section' => 'ys_customizer_section_archive',
			]
		);
		$ys_customizer->add_checkbox(
			[
				'id'      => 'ys_show_archive_publish_date',
				'default' => 1,
				'label'   => '投稿日・更新日を表示する',
				'section' => 'ys_customizer_section_archive',
			]
		);
		/**
		 * 抜粋文字数
		 */
		$ys_customizer->add_number(
			[
				'id'      => 'ys_option_excerpt_length',
				'default' => 110,
				'label'   => '投稿抜粋の文字数',
				'section' => 'ys_customizer_section_archive',
			]
		);
	}

	/**
	 * 拡張機能用パネル追加
	 */
	private function ystandard_extension() {
		$this->_wp_customize->add_panel(
			'ys_customizer_panel_extension',
			[
				'title'       => '[ys]拡張機能設定',
				'priority'    => 2000,
				'description' => 'yStandard専用プラグイン等による拡張機能の設定',
			]
		);

		apply_filters( 'ys_customizer_add_extension', $this->_wp_customize );
	}

	/**
	 * カスタマイザー用画像アセットURL取得
	 *
	 * @return string
	 */
	public static function get_assets_dir_uri() {
		return get_template_directory_uri() . '/assets/images/customizer';
	}
}

new Customizer();
