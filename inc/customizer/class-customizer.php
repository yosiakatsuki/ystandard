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
		'ys_customizer_panel_design' => 1000,
		'ys_info_bar'                => 1000,
		'ys_advertisement'           => 1130,
		'ys_amp'                     => 1300,
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
	 * カスタマイザー追加
	 *
	 * @param \WP_Customize_Manager $wp_customize カスタマイザー.
	 */
	public function customize_register( $wp_customize ) {
		$this->_wp_customize = $wp_customize;

		/**
		 * WordPressデフォルト設定の上書き・追加
		 */
		$this->extend_wp_option();
		$this->title_tagline();
		$this->header_media();
		/**
		 * お知らせバー
		 */
		$this->info_bar();
		/**
		 * [ys]デザイン
		 */
		$this->design_site_color();
		$this->design_header();
		$this->design_breadcrumb();
		$this->design_mobile();
		$this->design_post();
		$this->design_page();
		$this->design_archive();
		$this->design_one_column_template();
		$this->design_footer();
		$this->design_icon_font();
		/**
		 * SNS設定
		 */
		$this->sns();
		/**
		 * SEO設定
		 */
		$this->seo();
		/**
		 * 高速化設定
		 */
		$this->performance();
		/**
		 * 運営支援
		 */
		$this->admin();
		$this->admin_editor();
		$this->admin_disable_color();
		/**
		 * 拡張機能
		 */
		$this->ystandard_extension();
	}

	/**
	 * WordPress標準のカスタマイザー項目の変更・追加
	 */
	private function extend_wp_option() {
		/**
		 * WP標準の設定を削除
		 */
		$this->_wp_customize->remove_setting( 'background_color' );
		$this->_wp_customize->remove_section( 'colors' );
		$this->_wp_customize->remove_control( 'display_header_text' );
		/**
		 * ブログ名などをカスタマイザーショートカット対応させる
		 */
		$this->_wp_customize->get_setting( 'custom_logo' )->transport           = 'refresh';
		$this->_wp_customize->get_setting( 'background_image' )->transport      = 'refresh';
		$this->_wp_customize->get_setting( 'background_preset' )->transport     = 'refresh';
		$this->_wp_customize->get_setting( 'background_size' )->transport       = 'refresh';
		$this->_wp_customize->get_setting( 'background_repeat' )->transport     = 'refresh';
		$this->_wp_customize->get_setting( 'background_attachment' )->transport = 'refresh';
		$this->_wp_customize->get_setting( 'blogname' )->transport              = 'postMessage';
		$this->_wp_customize->get_setting( 'blogdescription' )->transport       = 'postMessage';

		if ( isset( $this->_wp_customize->selective_refresh ) ) {
			$this->_wp_customize->selective_refresh->add_partial(
				'blogname',
				[
					'selector'            => '.site-title a',
					'container_inclusive' => false,
					'render_callback'     => function () {
						bloginfo( 'name' );
					},
				]
			);
			$this->_wp_customize->selective_refresh->add_partial(
				'blogdescription',
				[
					'selector'            => '.site-description',
					'container_inclusive' => false,
					'render_callback'     => function () {
						bloginfo( 'description' );
					},
				]
			);
		}
	}

	/**
	 * 基本設定への設定追加
	 */
	private function title_tagline() {
		/**
		 * ロゴ設定追加
		 */
		$ys_customizer = new Customize_Control( $this->_wp_customize );
		$ys_customizer->add_checkbox(
			[
				'id'          => 'ys_logo_hidden',
				'default'     => 0,
				'label'       => 'ロゴを非表示にする',
				'description' => 'サイトヘッダーにロゴ画像を表示しない場合はチェックをつけてください<br>（ロゴの指定がないと構造化データでエラーになるので、仮のロゴ画像でも良いので設定することを推奨します）',
				'section'     => 'title_tagline',
				'priority'    => 9,
			]
		);
		/**
		 * 幅指定
		 */
		$ys_customizer->add_number(
			[
				'id'          => 'ys_logo_width_pc',
				'default'     => 0,
				'label'       => 'ロゴの表示幅(PC・タブレット)',
				'description' => 'PC・タブレット表示のロゴ表示幅を指定できます。指定しない場合は0にしてください。',
				'section'     => 'title_tagline',
				'priority'    => 9,
				'input_attrs' => [
					'min'  => 0,
					'max'  => 1000,
					'size' => 20,
				],
			]
		);

		$ys_customizer->add_number(
			[
				'id'          => 'ys_logo_width_sp',
				'default'     => 0,
				'label'       => 'ロゴの表示幅(スマホ)',
				'description' => 'スマートフォン表示のロゴ表示幅を指定できます。指定しない場合は0にしてください。',
				'section'     => 'title_tagline',
				'priority'    => 9,
				'input_attrs' => [
					'min'  => 0,
					'max'  => 1000,
					'size' => 20,
				],
			]
		);
		/**
		 * Titleタグの区切り文字
		 */
		$ys_customizer->add_text(
			[
				'id'          => 'ys_title_separate',
				'default'     => '',
				'transport'   => 'postMessage',
				'label'       => 'titleタグの区切り文字',
				'description' => '※区切り文字の前後に半角空白が自動で挿入されます',
				'section'     => 'title_tagline',
				'priority'    => 20,
			]
		);
		/**
		 * 概要表示・デスクリプション
		 */
		$ys_customizer->add_checkbox(
			[
				'id'          => 'ys_wp_hidden_blogdescription',
				'default'     => 0,
				'label'       => 'キャッチフレーズを非表示にする',
				'description' => 'サイトタイトル・ロゴの下にキャッチフレーズを表示したくない場合はチェックを付けて下さい',
				'section'     => 'title_tagline',
				'priority'    => 20,
			]
		);
		$ys_customizer->add_plain_textarea(
			[
				'id'          => 'ys_wp_site_description',
				'default'     => '',
				'transport'   => 'postMessage',
				'label'       => 'TOPページのmeta description',
				'description' => '※HTMLタグ・改行は削除されます',
				'section'     => 'title_tagline',
				'priority'    => 21,
			]
		);

		/**
		 * Apple touch icon設定追加
		 */
		// サイトアイコンの説明を変更.
		$this->_wp_customize->get_control( 'site_icon' )->description = sprintf(
			'ファビコン用の画像を設定して下さい。縦横%spx以上である必要があります。',
			'<strong>512</strong>'
		);

		$this->_wp_customize->add_setting(
			'ys_apple_touch_icon',
			[
				'type'       => 'option',
				'capability' => 'manage_options',
				'transport'  => 'postMessage',
			]
		);

		$this->_wp_customize->add_control(
			new \WP_Customize_Site_Icon_Control(
				$this->_wp_customize,
				'ys_apple_touch_icon',
				[
					'label'       => 'apple touch icon',
					'description' => sprintf(
						'apple touch icon用の画像を設定して下さい。縦横%spx以上である必要があります。',
						'<strong>512</strong>'
					),
					'section'     => 'title_tagline',
					'priority'    => 61,
					'height'      => 512,
					'width'       => 512,
				]
			)
		);
	}

	/**
	 * ヘッダーメディア設定追加
	 */
	private function header_media() {
		/**
		 * 既存設定をrefreshに
		 */
		$this->_wp_customize->get_setting( 'header_video' )->transport          = 'refresh';
		$this->_wp_customize->get_setting( 'external_header_video' )->transport = 'refresh';
		$this->_wp_customize->get_setting( 'header_image_data' )->transport     = 'refresh';
		/**
		 * YS_Customizer
		 */
		$ys_customizer = new Customize_Control( $this->_wp_customize );
		/**
		 * ヘッダーメディアショートコード
		 */
		$ys_customizer->add_text(
			[
				'id'          => 'ys_wp_header_media_shortcode',
				'default'     => '',
				'label'       => '[ys]ヘッダーメディア用ショートコード',
				'description' => 'ヘッダー画像をプラグイン等のショートコードで出力する場合、ショートコードを入力してください。',
				'section'     => 'header_image',
				'priority'    => 0,
			]
		);
		/**
		 * ヘッダーメディアに動画を使う場合、メニューを動画の上に重ねるか
		 */
		$ys_customizer->add_label(
			[
				'id'       => 'ys_wp_header_media_full_label',
				'label'    => '[ys]ヘッダーメディアにサイトヘッダーを重ねる（PC）',
				'section'  => 'header_image',
				'priority' => 20,
			]
		);
		$ys_customizer->add_checkbox(
			[
				'id'          => 'ys_wp_header_media_full',
				'default'     => 0,
				'label'       => '[ys]ヘッダーメディアにサイトヘッダーを重ねる（PC）',
				'description' => 'PC表示でヘッダーメディア（画像・動画）の上にサイトヘッダーを重ねる。',
				'section'     => 'header_image',
				'priority'    => 21,
			]
		);
		/**
		 * メニュー表示タイプ
		 */
		$ys_customizer->add_radio(
			[
				'id'          => 'ys_wp_header_media_full_type',
				'default'     => 'dark',
				'label'       => '[ys]ヘッダーメディアにサイトヘッダーを重ねる（PC）：サイトヘッダー表示タイプ',
				'description' => 'PC表示でヘッダーメディア（画像・動画）の上にサイトヘッダーを重ねる場合のサイトヘッダー表示タイプ',
				'section'     => 'header_image',
				'priority'    => 22,
				'choices'     => [
					'dark'  => 'ダーク',
					'light' => 'ライト',
				],
			]
		);
		/**
		 * メニュー不透明度
		 */
		$ys_customizer->add_number(
			[
				'id'          => 'ys_wp_header_media_full_opacity',
				'default'     => 50,
				'label'       => '[ys]ヘッダーメディアにサイトヘッダーを重ねる（PC）：サイトヘッダー不透明度',
				'description' => 'サイトヘッダーの不透明度を設定します。0~100の間で入力して下さい。（数値が小さいほど透明になります。）',
				'section'     => 'header_image',
				'priority'    => 23,
				'input_attrs' => [
					'min'  => 0,
					'max'  => 100,
					'size' => 20,
				],
			]
		);
		/**
		 * カスタムヘッダーの全ページ表示
		 */
		$ys_customizer->add_label(
			[
				'id'       => 'ys_wp_header_media_all_page_label',
				'label'    => '[ys]ヘッダーメディアを全ページ表示する',
				'section'  => 'header_image',
				'priority' => 24,
			]
		);
		$ys_customizer->add_checkbox(
			[
				'id'          => 'ys_wp_header_media_all_page',
				'default'     => 0,
				'label'       => '[ys]ヘッダーメディアを全ページ表示する',
				'description' => 'TOPページ以外の全ページでヘッダーメディアを表示する。',
				'section'     => 'header_image',
				'priority'    => 24,
			]
		);
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
	 * デザイン -> サイト背景色
	 */
	private function design_site_color() {
		/**
		 * サイト全体の色
		 */
		$ys_customizer = new Customize_Control( $this->_wp_customize );
		$ys_customizer->add_section(
			[
				'section' => 'ys_color_site',
				'title'   => 'サイト背景色',
				'panel'   => 'ys_customizer_panel_design',
			]
		);
		// サイト背景色.
		$ys_customizer->add_color(
			[
				'id'      => 'ys_color_site_bg',
				'default' => Color::get_site_bg_default(),
				'label'   => 'サイト背景色',
			]
		);
	}

	/**
	 * デザイン -> ヘッダー
	 */
	private function design_header() {
		$ys_customizer = new Customize_Control( $this->_wp_customize );
		/**
		 * セクション追加
		 */
		$ys_customizer->add_section(
			[
				'section'     => 'ys_customizer_section_header_design',
				'title'       => 'ヘッダー設定',
				'description' => 'ヘッダー部分のデザイン設定',
				'panel'       => 'ys_customizer_panel_design',
			]
		);
		/**
		 * ヘッダータイプ
		 */
		$assets_url = $this->get_assets_dir_uri();
		$row1       = $assets_url . '/design/header/1row.png';
		$center     = $assets_url . '/design/header/center.png';
		$row2       = $assets_url . '/design/header/2row.png';
		$img        = '<img src="%s" alt="" width="100" height="100" />';
		$ys_customizer->add_image_label_radio(
			[
				'id'          => 'ys_design_header_type',
				'default'     => 'row1',
				'label'       => 'ヘッダータイプ',
				'description' => 'ヘッダーの表示タイプ',
				'section'     => 'ys_customizer_section_header_design',
				'choices'     => [
					'row1'   => sprintf( $img, $row1 ),
					'center' => sprintf( $img, $center ),
					'row2'   => sprintf( $img, $row2 ),
				],
			]
		);
		// ヘッダー背景色.
		$ys_customizer->add_color(
			[
				'id'      => 'ys_color_header_bg',
				'default' => '#ffffff',
				'label'   => 'ヘッダー背景色',
			]
		);
		// サイトタイトル文字色.
		$ys_customizer->add_color(
			[
				'id'      => 'ys_color_header_font',
				'default' => '#222222',
				'label'   => 'サイトタイトル・メニューテキストの文字色',
			]
		);
		// サイト概要の文字色.
		$ys_customizer->add_color(
			[
				'id'      => 'ys_color_header_dscr_font',
				'default' => '#757575',
				'label'   => 'サイト概要の文字色',
			]
		);
		/**
		 * ヘッダー固定表示
		 */
		$ys_customizer->add_label(
			[
				'id'          => 'ys_header_fixed_label',
				'label'       => '固定ヘッダー設定',
				'description' => '画面上部にヘッダーを固定表示するための設定',

			]
		);
		$ys_customizer->add_checkbox(
			[
				'id'      => 'ys_header_fixed',
				'default' => 0,
				'label'   => 'ヘッダーを画面上部に固定する',
			]
		);
		/**
		 * ヘッダー固定表示
		 */
		$ys_customizer->add_label(
			[
				'id'          => 'ys_header_fixed_height_label',
				'label'       => 'ヘッダー高さ設定',
				'description' => '※ヘッダーの固定表示をする場合、ヘッダー高さの指定が必要になります。<br><br>プレビュー画面左上に表示された「ヘッダー高さ」の数字を参考に以下の設定に入力してください。',

			]
		);
		/**
		 * ヘッダー高さ(PC)
		 */
		$ys_customizer->add_number(
			[
				'id'      => 'ys_header_fixed_height_pc',
				'default' => 0,
				'label'   => 'ヘッダー高さ(PC)',
			]
		);
		/**
		 * ヘッダー高さ(タブレット)
		 */
		$ys_customizer->add_number(
			[
				'id'      => 'ys_header_fixed_height_tablet',
				'default' => 0,
				'label'   => 'ヘッダー高さ(タブレット)',
			]
		);
		/**
		 * ヘッダー高さ(モバイル)
		 */
		$ys_customizer->add_number(
			[
				'id'      => 'ys_header_fixed_height_mobile',
				'default' => 0,
				'label'   => 'ヘッダー高さ(モバイル)',
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
		$ys_customizer = new Customize_Control( $this->_wp_customize );
		$ys_customizer->add_section(
			[
				'section'     => 'ys_customizer_section_mobile_design',
				'title'       => 'モバイルページ設定',
				'description' => 'モバイルページのデザイン設定',
				'panel'       => 'ys_customizer_panel_design',
			]
		);
		// ナビゲーション色.
		$ys_customizer->add_section_label( 'モバイルメニュー' );
		// ナビゲーション背景色（SP）.
		$ys_customizer->add_color(
			[
				'id'      => 'ys_color_nav_bg_sp',
				'default' => '#000000',
				'label'   => 'モバイルメニュー背景色',
			]
		);
		// ナビゲーション文字色（SP）.
		$ys_customizer->add_color(
			[
				'id'      => 'ys_color_nav_font_sp',
				'default' => '#ffffff',
				'label'   => 'モバイルメニュー文字色',
			]
		);
		// ナビゲーションボタン色（SP）.
		$ys_customizer->add_color(
			[
				'id'      => 'ys_color_nav_btn_sp_open',
				'default' => '#222222',
				'label'   => 'モバイルメニュー ボタン色：開く',
			]
		);
		// ナビゲーションボタン色（SP）.
		$ys_customizer->add_color(
			[
				'id'      => 'ys_color_nav_btn_sp',
				'default' => '#ffffff',
				'label'   => 'モバイルメニュー ボタン色：閉じる',
			]
		);
		// 検索フォーム.
		$ys_customizer->add_label(
			[
				'id'    => 'ys_show_search_form_on_slide_menu_label',
				'label' => 'モバイルメニューに検索フォームを表示',
			]
		);
		// スライドメニューに検索フォームを出力する.
		$ys_customizer->add_checkbox(
			[
				'id'      => 'ys_show_search_form_on_slide_menu',
				'default' => 0,
				'label'   => 'モバイルメニューに検索フォームを表示する',
			]
		);

		// サイドバー.
		$ys_customizer->add_section_label( 'サイドバー表示設定' );
		// サイドバー出力.
		$ys_customizer->add_checkbox(
			[
				'id'      => 'ys_show_sidebar_mobile',
				'default' => 0,
				'label'   => 'モバイル表示でサイドバーを非表示にする',
			]
		);

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
	 * デザイン -> フッター
	 */
	private function design_footer() {
		$ys_customizer = new Customize_Control( $this->_wp_customize );
		$ys_customizer->add_section(
			[
				'section'     => 'ys_design_footer',
				'title'       => 'フッター設定',
				'description' => 'フッターの設定',
				'panel'       => 'ys_customizer_panel_design',
			]
		);
		// フッター背景色.
		$ys_customizer->add_color(
			[
				'id'      => 'ys_color_footer_bg',
				'default' => '#222222',
				'label'   => 'フッター背景色',
			]
		);
		// フッター文字色.
		$ys_customizer->add_color(
			[
				'id'      => 'ys_color_footer_font',
				'default' => '#ffffff',
				'label'   => 'フッター文字色',
			]
		);
		// フッターSNS設定ラベル.
		$ys_customizer->add_section_label(
			'フッターSNS設定',
			[
				'description' => '※SNSのURLは「[ys]SNS設定」→「フッターSNSフォローリンク設定」から設定してください。',
			]
		);
		// フッターSNSアイコン背景色タイプ.
		$ys_customizer->add_radio(
			[
				'id'      => 'ys_color_footer_sns_bg_type',
				'default' => 'light',
				'label'   => 'フッターSNSアイコン背景色',
				'choices' => [
					'light' => 'ライト',
					'dark'  => 'ダーク',
				],
			]
		);

		// フッターSNSアイコン背景色不透明度.
		$ys_customizer->add_number(
			[
				'id'          => 'ys_color_footer_sns_bg_opacity',
				'default'     => 30,
				'label'       => 'フッターSNSアイコン背景色の不透明度',
				'description' => '0~100の間で入力して下さい',
				'input_attrs' => [
					'min'  => 0,
					'max'  => 100,
					'size' => 20,
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
		/**
		 * 記事前後のウィジェット表示設定
		 */
		$ys_customizer->add_label(
			[
				'id'          => 'ys_post_content_widget_label',
				'label'       => '記事前後のウィジェット表示設定',
				'description' => '記事前後に表示するウィジェットの設定',
				'section'     => 'ys_customizer_section_post',
			]
		);
		/**
		 * 記事上ウィジェットを出力する
		 */
		$ys_customizer->add_checkbox(
			[
				'id'      => 'ys_show_post_before_content_widget',
				'default' => 0,
				'label'   => '記事上ウィジェットを出力する',
				'section' => 'ys_customizer_section_post',
			]
		);
		/**
		 * 記事上ウィジェットの優先順位
		 */
		$ys_customizer->add_number(
			[
				'id'          => 'ys_post_before_content_widget_priority',
				'default'     => 10,
				'label'       => '記事上ウィジェットの優先順位',
				'description' => '記事上ウィジェットの優先順位。1~99を目安に設定して下さい。（初期値10）数字が小さいほどコンテンツに近い位置にウィジェットが表示されます。（他プラグインで出力している内容との表示順調整用）',
				'section'     => 'ys_customizer_section_post',
			]
		);
		/**
		 * 記事下ウィジェットを出力する
		 */
		$ys_customizer->add_checkbox(
			[
				'id'      => 'ys_show_post_after_content_widget',
				'default' => 0,
				'label'   => '記事下ウィジェットを出力する',
				'section' => 'ys_customizer_section_post',
			]
		);
		/**
		 * 記事下ウィジェットの優先順位
		 */
		$ys_customizer->add_number(
			[
				'id'          => 'ys_post_after_content_widget_priority',
				'default'     => 10,
				'label'       => '記事下ウィジェットの優先順位',
				'description' => '記事下ウィジェットの優先順位。1~99を目安に設定して下さい。（初期値10）数字が小さいほどコンテンツに近い位置にウィジェットが表示されます。（他プラグインで出力している内容との表示順調整用）',
				'section'     => 'ys_customizer_section_post',
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
		 * ブログフォローボックスを表示する
		 */
		$ys_customizer->add_checkbox(
			[
				'id'      => 'ys_show_page_follow_box',
				'default' => 1,
				'label'   => 'ブログフォローボックスを表示する',
				'section' => 'ys_customizer_section_page',
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
		/**
		 * 記事前後のウィジェット表示設定
		 */
		$ys_customizer->add_label(
			[
				'id'          => 'ys_page_content_widget_label',
				'label'       => '記事前後のウィジェット表示設定',
				'description' => '記事前後に表示するウィジェットの設定',
				'section'     => 'ys_customizer_section_page',
			]
		);
		/**
		 * 記事上ウィジェットを出力する
		 */
		$ys_customizer->add_checkbox(
			[
				'id'      => 'ys_show_page_before_content_widget',
				'default' => 0,
				'label'   => '記事上ウィジェットを出力する',
				'section' => 'ys_customizer_section_page',
			]
		);
		/**
		 * 記事上ウィジェットの優先順位
		 */
		$ys_customizer->add_number(
			[
				'id'          => 'ys_page_before_content_widget_priority',
				'default'     => 10,
				'label'       => '記事上ウィジェットの優先順位',
				'description' => '記事上ウィジェットの優先順位。1~99を目安に設定して下さい。（初期値10）数字が小さいほどコンテンツに近い位置にウィジェットが表示されます。（他プラグインで出力している内容との表示順調整用）',
				'section'     => 'ys_customizer_section_page',
			]
		);
		/**
		 * 記事下ウィジェットを出力する
		 */
		$ys_customizer->add_checkbox(
			[
				'id'      => 'ys_show_page_after_content_widget',
				'default' => 0,
				'label'   => '記事下ウィジェットを出力する',
				'section' => 'ys_customizer_section_page',
			]
		);
		/**
		 * 記事下ウィジェットの優先順位
		 */
		$ys_customizer->add_number(
			[
				'id'          => 'ys_page_after_content_widget_priority',
				'default'     => 10,
				'label'       => '記事下ウィジェットの優先順位',
				'description' => '記事下ウィジェットの優先順位。1~99を目安に設定して下さい。（初期値10）数字が小さいほどコンテンツに近い位置にウィジェットが表示されます。（他プラグインで出力している内容との表示順調整用）',
				'section'     => 'ys_customizer_section_page',
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
	 * SNS
	 */
	private function sns() {
		$this->_wp_customize->add_panel(
			'ys_customizer_panel_sns',
			[
				'title'    => '[ys]SNS設定',
				'priority' => 1100,
			]
		);
		/**
		 * OGP設定
		 */
		$ys_customizer = new Customize_Control( $this->_wp_customize );
		$ys_customizer->add_section(
			[
				'section' => 'ys_customizer_section_ogp',
				'title'   => 'OGP設定',
				'panel'   => 'ys_customizer_panel_sns',
			]
		);

		/**
		 * OGP metaタグを出力する
		 */
		$ys_customizer->add_label(
			[
				'id'    => 'ys_ogp_enable_label',
				'label' => 'OGP metaタグ',
			]
		);
		$ys_customizer->add_checkbox(
			[
				'id'        => 'ys_ogp_enable',
				'default'   => 1,
				'transport' => 'postMessage',
				'label'     => 'OGPのmetaタグを出力する',
			]
		);
		/**
		 * Facebook app_id
		 */
		$ys_customizer->add_text(
			[
				'id'          => 'ys_ogp_fb_app_id',
				'default'     => '',
				'transport'   => 'postMessage',
				'description' => '',
				'label'       => 'Facebook app_id',
				'input_attrs' => [
					'placeholder' => '000000000000000',
					'maxlength'   => 15,
				],
			]
		);
		/**
		 * OGPデフォルト画像
		 */
		$ys_customizer->add_image(
			[
				'id'          => 'ys_ogp_default_image',
				'transport'   => 'postMessage',
				'label'       => 'OGPデフォルト画像',
				'description' => 'トップページ・アーカイブページ・投稿にアイキャッチ画像が無かった場合のデフォルト画像を指定して下さい。<br>おすすめサイズ：横1200px - 縦630px ',
			]
		);
		/**
		 * Twitter Cards
		 */
		$ys_customizer = new Customize_Control( $this->_wp_customize );
		$ys_customizer->add_section(
			[
				'section' => 'ys_customizer_section_twitter_cards',
				'title'   => 'Twitterカード設定',
				'panel'   => 'ys_customizer_panel_sns',
			]
		);
		/**
		 * Twitterカードのmetaタグを出力する
		 */
		$ys_customizer->add_label(
			[
				'id'    => 'ys_twittercard_enable_label',
				'label' => 'Twitterカードmetaタグ',
			]
		);
		$ys_customizer->add_checkbox(
			[
				'id'        => 'ys_twittercard_enable',
				'default'   => 1,
				'transport' => 'postMessage',
				'label'     => 'Twitterカードのmetaタグを出力する',
			]
		);
		/**
		 * ユーザー名
		 */
		$ys_customizer->add_text(
			[
				'id'          => 'ys_twittercard_user',
				'default'     => '',
				'transport'   => 'postMessage',
				'label'       => 'Twitterカードのユーザー名',
				'description' => '「@」なしのTwitterユーザー名を入力して下さい。<br>例：Twitterユーザー名…「@yosiakatsuki」<br>→入力…「yosiakatsuki」',
				'input_attrs' => [
					'placeholder' => 'username',
				],
			]
		);
		/**
		 * カードタイプ
		 */
		$ys_customizer->add_radio(
			[
				'id'        => 'ys_twittercard_type',
				'default'   => 'summary_large_image',
				'transport' => 'postMessage',
				'label'     => 'カードタイプ',
				'choices'   => [
					'summary_large_image' => 'Summary Card with Large Image',
					'summary'             => 'Summary Card',
				],
			]
		);
		/**
		 * SNS Share Button
		 */
		$ys_customizer = new Customize_Control( $this->_wp_customize );
		$ys_customizer->add_section(
			[
				'section'     => 'ys_customizer_section_sns_share_button',
				'title'       => 'SNSシェアボタン設定',
				'description' => '記事詳細ページに表示するSNSシェアボタンの設定',
				'panel'       => 'ys_customizer_panel_sns',
			]
		);
		/**
		 * シェアボタン表示設定
		 */
		$ys_customizer->add_label(
			[
				'id'    => 'ys_sns_share_button_label',
				'label' => '表示するSNSシェアボタン',
			]
		);
		/**
		 * Twitter
		 */
		$ys_customizer->add_checkbox(
			[
				'id'      => 'ys_sns_share_button_twitter',
				'default' => 1,
				'label'   => 'Twitter',
			]
		);
		/**
		 * Facebook
		 */
		$ys_customizer->add_checkbox(
			[
				'id'      => 'ys_sns_share_button_facebook',
				'default' => 1,
				'label'   => 'Facebook',
			]
		);
		/**
		 * はてブ
		 */
		$ys_customizer->add_checkbox(
			[
				'id'      => 'ys_sns_share_button_hatenabookmark',
				'default' => 1,
				'label'   => 'はてなブックマーク',
			]
		);
		/**
		 * Pocket
		 */
		$ys_customizer->add_checkbox(
			[
				'id'      => 'ys_sns_share_button_pocket',
				'default' => 1,
				'label'   => 'Pocket',
			]
		);
		/**
		 * LINE
		 */
		$ys_customizer->add_checkbox(
			[
				'id'      => 'ys_sns_share_button_line',
				'default' => 1,
				'label'   => 'LINE',
			]
		);
		/**
		 * Feedly
		 */
		$ys_customizer->add_checkbox(
			[
				'id'      => 'ys_sns_share_button_feedly',
				'default' => 1,
				'label'   => 'Feedly',
			]
		);
		/**
		 * RSS
		 */
		$ys_customizer->add_checkbox(
			[
				'id'      => 'ys_sns_share_button_rss',
				'default' => 1,
				'label'   => 'RSS',
			]
		);
		/**
		 * シェアボタン表示位置
		 */
		$ys_customizer->add_label(
			[
				'id'    => 'ys_sns_share_on_label',
				'label' => 'シェアボタンの表示位置',
			]
		);
		/**
		 * 記事上部に表示する
		 */
		$ys_customizer->add_checkbox(
			[
				'id'      => 'ys_sns_share_on_entry_header',
				'default' => 1,
				'label'   => '記事上部にシェアボタンを表示する',
			]
		);
		/**
		 * 記事下部に表示する
		 */
		$ys_customizer->add_checkbox(
			[
				'id'      => 'ys_sns_share_on_below_entry',
				'default' => 1,
				'label'   => '記事下部にシェアボタンを表示する',
			]
		);
		/**
		 * シェアボタン表示列数
		 */
		$ys_customizer->add_label(
			[
				'id'    => 'ys_sns_share_col_label',
				'label' => 'シェアボタンの表示列数',
			]
		);
		/**
		 * PCでの列数
		 */
		$ys_customizer->add_number(
			[
				'id'          => 'ys_sns_share_col_pc',
				'default'     => 6,
				'label'       => 'PCでの列数(1~6)',
				'input_attrs' => [
					'min' => 1,
					'max' => 6,
				],
			]
		);
		$ys_customizer->add_number(
			[
				'id'          => 'ys_sns_share_col_tablet',
				'default'     => 3,
				'label'       => 'タブレットでの列数(1~6)',
				'input_attrs' => [
					'min' => 1,
					'max' => 6,
				],
			]
		);
		$ys_customizer->add_number(
			[
				'id'          => 'ys_sns_share_col_sp',
				'default'     => 3,
				'label'       => 'スマートフォンでの列数(1~6)',
				'input_attrs' => [
					'min' => 1,
					'max' => 6,
				],
			]
		);
		/**
		 * Twitter シェアボタン
		 */
		$ys_customizer = new Customize_Control( $this->_wp_customize );
		$ys_customizer->add_section(
			[
				'section' => 'ys_customizer_section_twitter_share',
				'title'   => 'Twitterシェアボタン設定',
				'panel'   => 'ys_customizer_panel_sns',
			]
		);
		/**
		 * 投稿ユーザー（via）の設定
		 */
		$ys_customizer->add_label(
			[
				'id'    => 'ys_sns_share_tweet_via_label',
				'label' => '投稿ユーザー（via）の設定',
			]
		);
		/**
		 * Viaに設定するTwitterアカウント名
		 */
		$ys_customizer->add_text(
			[
				'id'          => 'ys_sns_share_tweet_via_account',
				'default'     => '',
				'transport'   => 'postMessage',
				'label'       => 'viaに設定するTwitterアカウント名',
				'description' => '「@」なしのTwitterユーザー名を入力して下さい。<br>例：Twitterユーザー名…「@yosiakatsuki」<br>→入力…「yosiakatsuki」<br>未入力の場合viaは設定されません。',
				'input_attrs' => [
					'placeholder' => 'username',
				],
			]
		);
		/**
		 * おすすめアカウントの設定
		 */
		$ys_customizer->add_label(
			[
				'id'    => 'ys_sns_share_tweet_related_label',
				'label' => 'おすすめアカウントの設定',
			]
		);
		/**
		 * ツイート後に表示するおすすめアカウント
		 */
		$ys_customizer->add_text(
			[
				'id'          => 'ys_sns_share_tweet_related_account',
				'default'     => '',
				'transport'   => 'postMessage',
				'label'       => 'ツイート後に表示するおすすめアカウント',
				'description' => '「@」なしのTwitterユーザー名を入力して下さい。<br>例：Twitterユーザー名…「@yosiakatsuki」<br>→入力…「yosiakatsuki」<br>複数のアカウントをおすすめ表示する場合はカンマで区切って下さい。<br>未入力の場合おすすめアカウントは設定されません。',
				'input_attrs' => [
					'placeholder' => 'username',
				],
			]
		);
		/**
		 * 購読ボタン設定
		 */
		$ys_customizer = new Customize_Control( $this->_wp_customize );
		$ys_customizer->add_section(
			[
				'section'     => 'ys_customizer_section_sns_follow',
				'title'       => 'フォローボタン設定',
				'description' => '記事下に表示されるフォローボタンのリンク先URLの設定',
				'panel'       => 'ys_customizer_panel_sns',
			]
		);
		/**
		 * SNS購読ボタン設定
		 */
		$ys_customizer->add_label(
			[
				'id'          => 'ys_subscribe_label',
				'label'       => 'フォローボタン設定',
				'description' => '※フォローボタンを表示しない場合は空白にしてください',
			]
		);
		/**
		 * Twitter
		 */
		$ys_customizer->add_url(
			[
				'id'      => 'ys_subscribe_url_twitter',
				'default' => '',
				'label'   => 'Twitter',
			]
		);
		/**
		 * Facebookページ
		 */
		$ys_customizer->add_url(
			[
				'id'      => 'ys_subscribe_url_facebook',
				'default' => '',
				'label'   => 'Facebookページ',
			]
		);
		/**
		 * Feedly
		 */
		$ys_customizer->add_url(
			[
				'id'          => 'ys_subscribe_url_feedly',
				'default'     => '',
				'label'       => 'Feedly',
				'description' => '<a href="https://feedly.com/factory.html" target="_blank">https://feedly.com/factory.html</a>で購読用URLを生成・取得してください。（出来上がったHTMLタグのhref部分）',
			]
		);
		/**
		 * フッターSNSフォローボタン設定
		 */
		$ys_customizer = new Customize_Control( $this->_wp_customize );
		$ys_customizer->add_section(
			[
				'section'     => 'ys_customizer_section_footer_sns_follow',
				'title'       => 'フッターSNSフォローリンク設定',
				'description' => 'フッターに表示するSNSフォローボタンの設定<br>リンクする各SNSのプロフィールページ等のURLを入力して下さい',
				'panel'       => 'ys_customizer_panel_sns',
			]
		);
		/**
		 * 各SNSのURL入力欄追加
		 */
		$sns = ys_get_sns_icons();
		foreach ( $sns as $key => $value ) {
			$ys_customizer->add_url(
				[
					'id'      => 'ys_follow_url_' . $key,
					'default' => '',
					'label'   => $value['label'],
				]
			);
		}
		/**
		 * SNS用JavaScriptの読み込み
		 */
		$ys_customizer = new Customize_Control( $this->_wp_customize );
		$ys_customizer->add_section(
			[
				'section'     => 'ys_customizer_section_load_script',
				'title'       => 'SNS用JavaScriptの読み込み(上級者向け)',
				'description' => 'SNS用のJavaScriptを読み込みます。<br>通常、各SNSで発行した埋め込みコードにはJavaScriptのコードも含まれるのでこの設定は不要です。<br>独自に読み込み位置などを調整する場合はご利用下さい。',
				'panel'       => 'ys_customizer_panel_sns',
			]
		);
		/**
		 * Twitter用JavaScriptを読み込む
		 */
		$ys_customizer->add_checkbox(
			[
				'id'          => 'ys_load_script_twitter',
				'default'     => 0,
				'transport'   => 'postMessage',
				'label'       => 'Twitter用JavaScriptを読み込む',
				'description' => '※Twitterのフォローボタンなどをサイト内で使用する場合、こちらにチェックを入れてください。<br>※Twitter用のJavaScriptを&lt;/body&gt;直前で読み込みます',
			]
		);
		/**
		 *  Facebook用JavaScriptを読み込む
		 */
		$ys_customizer->add_checkbox(
			[
				'id'          => 'ys_load_script_facebook',
				'default'     => 0,
				'transport'   => 'postMessage',
				'label'       => 'Facebook用JavaScriptを読み込む',
				'description' => '※FacebookのいいねボタンやPagePluginなどをサイト内で使用する場合、こちらにチェックを入れてください。<br>※Facebook用のJavaScriptを&lt;/body&gt;直前で読み込みます',
			]
		);
	}

	/**
	 * SEO
	 */
	private function seo() {
		$this->_wp_customize->add_panel(
			'ys_customizer_panel_seo',
			[
				'title'    => '[ys]SEO設定',
				'priority' => 1110,
			]
		);
		/**
		 * メタデスクリプションの作成
		 */
		$ys_customizer = new Customize_Control( $this->_wp_customize );
		$ys_customizer->add_section(
			[
				'section'  => 'ys_customizer_section_meta_description',
				'title'    => 'meta description設定',
				'priority' => 1,
				'panel'    => 'ys_customizer_panel_seo',
			]
		);
		/**
		 * SEO : meta descriptionを自動生成する
		 */
		$ys_customizer->add_checkbox(
			[
				'id'        => 'ys_option_create_meta_description',
				'default'   => 1,
				'transport' => 'postMessage',
				'label'     => 'meta descriptionを自動生成する',
			]
		);
		/**
		 * 抜粋文字数
		 */
		$ys_customizer->add_number(
			[
				'id'        => 'ys_option_meta_description_length',
				'default'   => 80,
				'transport' => 'postMessage',
				'label'     => 'meta descriptionに使用する文字数',
			]
		);
		/**
		 * アーカイブページのnoindex設定
		 */
		$ys_customizer = new Customize_Control( $this->_wp_customize );
		$ys_customizer->add_section(
			[
				'section'  => 'ys_customizer_section_noindex',
				'title'    => 'アーカイブページのnoindex設定',
				'priority' => 1,
				'panel'    => 'ys_customizer_panel_seo',
			]
		);
		/**
		 * カテゴリー一覧をnoindexにする
		 */
		$ys_customizer->add_checkbox(
			[
				'id'        => 'ys_archive_noindex_category',
				'default'   => 0,
				'transport' => 'postMessage',
				'label'     => 'カテゴリー一覧をnoindexにする',
			]
		);
		/**
		 * タグ一覧をnoindexにする
		 */
		$ys_customizer->add_checkbox(
			[
				'id'        => 'ys_archive_noindex_tag',
				'default'   => 1,
				'transport' => 'postMessage',
				'label'     => 'タグ一覧をnoindexにする',
			]
		);
		/**
		 * 投稿者一覧をnoindexにする
		 */
		$ys_customizer->add_checkbox(
			[
				'id'        => 'ys_archive_noindex_author',
				'default'   => 1,
				'transport' => 'postMessage',
				'label'     => '投稿者一覧をnoindexにする',
			]
		);
		/**
		 * 日別一覧をnoindexにする
		 */
		$ys_customizer->add_checkbox(
			[
				'id'        => 'ys_archive_noindex_date',
				'default'   => 1,
				'transport' => 'postMessage',
				'label'     => '日別一覧をnoindexにする',
			]
		);
		/**
		 * Google Analytics設定
		 */
		$ys_customizer = new Customize_Control( $this->_wp_customize );
		$ys_customizer->add_section(
			[
				'section'  => 'ys_customizer_section_google_analytics',
				'title'    => 'Google Analytics設定',
				'priority' => 1,
				'panel'    => 'ys_customizer_panel_seo',
			]
		);
		/**
		 * Google Analytics トラッキングID
		 */
		$ys_customizer->add_text(
			[
				'id'          => 'ys_ga_tracking_id',
				'default'     => '',
				'transport'   => 'postMessage',
				'label'       => 'Google Analytics トラッキングID',
				'input_attrs' => [
					'placeholder' => 'UA-00000000-0',
				],
			]
		);
		/**
		 * トラッキングコードタイプ
		 */
		$ys_customizer->add_radio(
			[
				'id'          => 'ys_ga_tracking_type',
				'default'     => 'gtag',
				'transport'   => 'postMessage',
				'label'       => 'トラッキングコードタイプ',
				'description' => 'Google Analytics トラッキングコードタイプを選択出来ます。※デフォルトはグローバル サイトタグ(gtag.js)です。',
				'choices'     => [
					'gtag'      => 'グローバル サイトタグ(gtag.js)',
					'analytics' => 'ユニバーサルアナリティクス(analytics.js)',
				],
			]
		);
		/**
		 * ログイン中はアクセス数をカウントしない
		 */
		$ys_customizer->add_checkbox(
			[
				'id'          => 'ys_ga_exclude_logged_in_user',
				'default'     => 0,
				'transport'   => 'postMessage',
				'label'       => '管理画面ログイン中はアクセス数カウントを無効にする（「購読者」ユーザーを除く）',
				'description' => 'チェックを付けた場合、ログインユーザーのアクセスではGoogle Analyticsのトラッキングコードを出力しません',
			]
		);
		/**
		 * 構造化データ 設定
		 */
		$ys_customizer = new Customize_Control( $this->_wp_customize );
		$ys_customizer->add_section(
			[
				'section'  => 'ys_customizer_section_structured_data',
				'title'    => '構造化データ 設定',
				'priority' => 1,
				'panel'    => 'ys_customizer_panel_seo',
			]
		);
		/**
		 * Publisher画像
		 */
		$ys_customizer->add_image(
			[
				'id'          => 'ys_option_structured_data_publisher_image',
				'transport'   => 'postMessage',
				'label'       => 'Publisher Logo',
				'description' => '構造化データのPublisherに使用する画像です。サイトロゴのような画像を設定すると良いかと思います。 推奨サイズ:横600px以下,縦60px以下',
			]
		);
		/**
		 * Publisher名
		 */
		$ys_customizer->add_text(
			[
				'id'          => 'ys_option_structured_data_publisher_name',
				'default'     => '',
				'transport'   => 'postMessage',
				'label'       => 'Publisher Name',
				'description' => '構造化データのPublisherに使用する名前です。空白の場合はサイトタイトルを使用します',
			]
		);
	}

	/**
	 * 高速化設定
	 */
	private function performance() {
		$this->_wp_customize->add_panel(
			'ys_customizer_panel_performance_tuning',
			[
				'title'       => '[ys]サイト高速化設定',
				'priority'    => 1120,
				'description' => 'サイト高速化を行うための設定',
			]
		);
		/**
		 * キャッシュ
		 */
		$ys_customizer = new Customize_Control( $this->_wp_customize );
		$ys_customizer->add_section(
			[
				'section'     => 'ys_customizer_performance_tuning_add_cache_query',
				'title'       => 'キャッシュ設定',
				'description' => '記事一覧作成やyStandard設定のキャッシュ機能設定',
				'panel'       => 'ys_customizer_panel_performance_tuning',
			]
		);
		/**
		 * [ys]人気ランキングの結果キャッシュ
		 */
		$ys_customizer->add_radio(
			[
				'id'          => 'ys_query_cache_ranking',
				'default'     => 'none',
				'transport'   => 'postMessage',
				'label'       => '人気記事ランキングの結果キャッシュ',
				'description' => '「[ys]人気ランキングウィジェット」・人気記事ランキング表示ショートコードの結果をキャッシュしてサーバー処理の高速化を図ります。<br>キャッシュする日数を選択して下さい。<br>※日別のランキングについてはキャッシュを作成しません。',
				'choices'     => [
					'none' => 'キャッシュしない',
					'1'    => '1日',
					'7'    => '7日',
					'30'   => '30日',
				],
			]
		);
		/**
		 * [ys]新着記事一覧の結果キャッシュ
		 */
		$ys_customizer->add_radio(
			[
				'id'          => 'ys_query_cache_recent_posts',
				'default'     => 'none',
				'transport'   => 'postMessage',
				'label'       => '新着記事一覧の結果キャッシュ',
				'description' => '「[ys]新着記事一覧ウィジェット」・新着記事一覧ショートコードの結果をキャッシュしてサーバー処理の高速化を図ります。<br>キャッシュする日数を選択して下さい。',
				'choices'     => [
					'none' => 'キャッシュしない',
					'1'    => '1日',
					'7'    => '7日',
					'30'   => '30日',
				],
			]
		);
		/**
		 * 関連記事の結果キャッシュ
		 */
		$ys_customizer->add_radio(
			[
				'id'          => 'ys_query_cache_related_posts',
				'default'     => 'none',
				'transport'   => 'postMessage',
				'label'       => '記事下エリア「関連記事」の結果キャッシュ',
				'description' => '記事下エリア「関連記事」の結果をキャッシュしてサーバー処理の高速化を図ります。<br>キャッシュする日数を選択して下さい。',
				'choices'     => [
					'none' => 'キャッシュしない',
					'1'    => '1日',
					'7'    => '7日',
					'30'   => '30日',
				],
			]
		);
		/**
		 * WordPress標準機能で読み込むCSS・JavaScriptの無効化
		 */
		$ys_customizer = new Customize_Control( $this->_wp_customize );
		$ys_customizer->add_section(
			[
				'section'     => 'ys_customizer_section_disable_wp_scripts',
				'title'       => '絵文字・oembed関連のCSS・JavaScriptの無効化',
				'description' => 'WordPress標準機能で読み込まれる絵文字・oembed関連のCSS・JavaScriptの無効化設定',
				'panel'       => 'ys_customizer_panel_performance_tuning',
			]
		);
		/**
		 * 絵文字を出力しない
		 */
		$ys_customizer->add_checkbox(
			[
				'id'      => 'ys_option_disable_wp_emoji',
				'default' => 1,
				'label'   => '絵文字関連のスタイルシート・スクリプトを無効化する',
			]
		);
		/**
		 * 絵文字を出力しない
		 */
		$ys_customizer->add_checkbox(
			[
				'id'      => 'ys_option_disable_wp_oembed',
				'default' => 1,
				'label'   => 'oembed関連のスタイルシート・スクリプトを無効化する',
			]
		);
		/**
		 * CSS読み込みのインライン化
		 */
		$ys_customizer = new Customize_Control( $this->_wp_customize );
		$ys_customizer->add_section(
			[
				'section'     => 'ys_customizer_section_optimize_load_css',
				'title'       => 'CSSインライン読み込み設定（上級者向け）',
				'description' => 'CSSの読み込み方式を変更します。',
				'panel'       => 'ys_customizer_panel_performance_tuning',
			]
		);
		/**
		 * CSSの読み込みを最適化する
		 */
		$ys_customizer->add_checkbox(
			[
				'id'          => 'ys_option_optimize_load_css',
				'default'     => 0,
				'label'       => 'CSSをインラインで読み込む',
				'description' => 'この設定をONにすると、yStandard関連のCSSが全てインラインで読み込まれます。',
			]
		);
		/**
		 * JavaScript読み込みの最適化
		 */
		$ys_customizer = new Customize_Control( $this->_wp_customize );
		$ys_customizer->add_section(
			[
				'section'     => 'ys_customizer_section_optimize_load_js',
				'title'       => 'JavaScript・jQueryの読み込み方式設定（上級者向け）',
				'description' => 'JavaScriptの読み込み方式を設定します。',
				'panel'       => 'ys_customizer_panel_performance_tuning',
			]
		);
		/**
		 * JavaScriptの読み込みを非同期化する
		 */
		$ys_customizer->add_label(
			[
				'id'    => 'ys_optimize_load_js_label',
				'label' => 'JavaScriptの読み込みを非同期化する',
			]
		);
		$ys_customizer->add_checkbox(
			[
				'id'          => 'ys_option_optimize_load_js',
				'default'     => 0,
				'label'       => 'JavaScriptの読み込みを非同期化する',
				'description' => 'この設定をONにすると、jQuery以外のJavaScriptの読み込みを非同期化します。',
			]
		);
		/**
		 * [jQueryをフッターで読み込む]
		 */
		$ys_customizer->add_label(
			[
				'id'    => 'ys_load_jquery_in_footer_label',
				'label' => 'jQueryの読み込みを最適化する',
			]
		);
		$ys_customizer->add_checkbox(
			[
				'id'          => 'ys_load_jquery_in_footer',
				'default'     => 0,
				'label'       => 'jQueryの読み込みを最適化する',
				'description' => 'jQueryをフッターで読み込み、サイトの高速化を図ります。<br>※この設定を有効にすると利用しているプラグインの動作が不安定になる恐れがあります。<br>プラグインの機能が正常に動作しなくなる場合は設定を無効化してください。',
			]
		);
		/**
		 * CDNにホストされているjQueryを読み込む
		 */
		$ys_customizer->add_url(
			[
				'id'          => 'ys_load_cdn_jquery_url',
				'default'     => '',
				'transport'   => 'postMessage',
				'label'       => 'CDN経由でjQueryを読み込む',
				'description' => '※WordPress標準のjQueryを読み込む場合は空白にしてください（デフォルト）<br>※ホストされているjQueryのURLを入力してください。',
			]
		);
		/**
		 * [jQueryを無効化する]
		 */
		$ys_customizer->add_label(
			[
				'id'    => 'ys_not_load_jquery_label',
				'label' => 'jQueryを無効化する',
			]
		);
		$ys_customizer->add_checkbox(
			[
				'id'          => 'ys_not_load_jquery',
				'default'     => 0,
				'transport'   => 'postMessage',
				'label'       => 'jQueryを無効化する',
				'description' => '※この設定を有効にするとサイト表示高速化が期待できますが、jQueryを使用している処理が動かなくなります。<br>※プラグインの動作に影響が出る恐れがありますのでご注意ください。<br>※yStandard内のJavaScriptではjQueryを使用する機能は使っていません',
			]
		);
	}


	/**
	 * サイト運営支援
	 */
	private function admin() {
		/**
		 * サイト運営支援
		 */
		$this->_wp_customize->add_panel(
			'ys_customizer_panel_admin',
			[
				'title'       => '[ys]サイト運営支援',
				'priority'    => 1500,
				'description' => 'サイト管理画面内の機能設定',
			]
		);

	}

	/**
	 * サイト運営支援 -> エディター設定
	 */
	private function admin_editor() {

		$ys_customizer = new Customize_Control( $this->_wp_customize );
		$ys_customizer->add_section(
			[
				'section'  => 'ys_customizer_section_admin_editor',
				'title'    => 'エディター設定',
				'priority' => 1,
				'panel'    => 'ys_customizer_panel_admin',
			]
		);
		/**
		 * ブロックエディター用CSSの追加
		 */
		$ys_customizer->add_label(
			[
				'id'          => 'ys_admin_enable_block_editor_style_label',
				'label'       => 'ブロックエディター用CSSの追加',
				'description' => '※ブロックエディターで有効な設定です。',
			]
		);
		$ys_customizer->add_checkbox(
			[
				'id'        => 'ys_admin_enable_block_editor_style',
				'default'   => 1,
				'transport' => 'postMessage',
				'label'     => 'ブロックエディター用CSSを追加する',
			]
		);
		/**
		 * ビジュアルエディタ用CSSの追加
		 */
		$ys_customizer->add_label(
			[
				'id'          => 'ys_admin_enable_tiny_mce_style_label',
				'label'       => 'ビジュアルエディター用CSSの追加',
				'description' => '※クラシックエディターで有効な設定です。<br>※ビジュアルエディターとテキストエディターの切り替えができなくなる等問題がある場合はチェックを外してください。',
			]
		);
		$ys_customizer->add_checkbox(
			[
				'id'        => 'ys_admin_enable_tiny_mce_style',
				'default'   => 0,
				'transport' => 'postMessage',
				'label'     => 'ビジュアルエディター用CSSを追加する',
			]
		);
	}

	/**
	 * サイト運営支援 -> 色変更機能を無効
	 */
	private function admin_disable_color() {
		/**
		 * テーマカスタマイザーでの色変更機能を無効にする
		 */
		$ys_customizer = new Customize_Control( $this->_wp_customize );
		$ys_customizer->add_section(
			[
				'section' => 'ys_customizer_section_disable_ys_color',
				'title'   => '色変更機能を無効にする(上級者向け)',
				'panel'   => 'ys_customizer_panel_admin',
			]
		);
		/**
		 * テーマカスタマイザーでの色変更機能を無効にする
		 */
		$ys_customizer->add_checkbox(
			[
				'id'          => 'ys_desabled_color_customizeser',
				'default'     => 0,
				'label'       => 'テーマカスタマイザーでの色変更機能を無効にする',
				'description' => '※ご自身でCSSを調整する場合はこちらのチェックをいれてください。<br>カスタマイザーで指定している部分のCSSコードが出力されなくなります',
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
	private function get_assets_dir_uri() {
		return get_template_directory_uri() . '/assets/images/customizer';
	}
}

new Customizer();
