<?php
/**
 * Header 関連
 *
 * @package ystandard
 * @author  yosiakatsuki
 * @license GPL-2.0+
 */

namespace ystandard;

use ystandard\utils\CSS;

defined( 'ABSPATH' ) || die();

/**
 * Class Header
 *
 * @package ystandard
 */
class Header {

	/**
	 * Header constructor.
	 */
	public function __construct() {
		add_action( 'customize_register', [ $this, 'register_title_tagline' ] );
		add_action( 'customize_register', [ $this, 'register_header_design' ] );
		add_filter( 'ys_get_inline_css', [ $this, 'add_inline_css' ] );
		add_filter( 'ys_get_css_custom_properties_args', [ $this, 'add_css_var' ] );
	}


	/**
	 * ヘッダーロゴ取得
	 *
	 * @return string
	 */
	public static function get_header_logo() {
		if ( has_custom_logo() && ! Option::get_option_by_bool( 'ys_logo_hidden', false ) ) {
			$logo = get_custom_logo();
		} else {
			$logo = sprintf(
				'<a href="%s" class="custom-logo-link" rel="home">%s</a>',
				esc_url( home_url( '/' ) ),
				get_bloginfo( 'name', 'display' )
			);
		}

		return apply_filters( 'ys_get_header_logo', $logo );
	}

	/**
	 * サイトキャッチフレーズ取得
	 */
	public static function get_blog_description() {
		if ( Option::get_option_by_bool( 'ys_wp_hidden_blogdescription', false ) ) {
			return '';
		}

		$description = apply_filters( 'ys_the_blog_description', get_bloginfo( 'description', 'display' ) );

		if ( empty( $description ) ) {
			return '';
		}

		return sprintf(
			'<p class="site-description">%s</p>',
			$description
		);
	}

	/**
	 * ヘッダー検索フォームを表示するか
	 *
	 * @return bool
	 */
	public static function is_active_header_search_form() {

		return Option::get_option_by_bool( 'ys_show_header_search_form', true );
	}

	/**
	 * 固定ヘッダーか
	 *
	 * @return bool
	 */
	public static function is_header_fixed() {
		return Option::get_option_by_bool( 'ys_header_fixed', false );
	}

	/**
	 * ヘッダータイプ
	 *
	 * @return string
	 */
	public static function get_header_type() {
		return Option::get_option( 'ys_design_header_type', 'row1' );
	}

	/**
	 * ヘッダーボックスシャドウ取得
	 *
	 * @return mixed|string
	 */
	private function get_header_shadow() {
		$option = Option::get_option( 'ys_header_box_shadow', 'none' );
		$props  = [
			'none'  => 'none',
			'small' => '0 0 4px rgba(0,0,0,0.1)',
			'large' => '0 0 12px rgba(0,0,0,0.1)',
		];
		if ( ! isset( $props[ $option ] ) ) {
			return 'none';
		}

		return $props[ $option ];
	}

	/**
	 * フッターメイン
	 *
	 * @param array $css_vars CSS.
	 *
	 * @return array
	 */
	public function add_css_var( $css_vars ) {

		// ヘッダー背景色・検索フォームカバー色.
		$header_bg = Option::get_option( 'ys_color_header_bg', '' );
		if ( $header_bg ) {
			$css_vars = array_merge(
				$css_vars,
				Enqueue_Utility::get_css_var(
					'--ystd--global-nav--search--cover--background',
					$header_bg
				),
				Enqueue_Utility::get_css_var(
					'--ystd--header--background',
					$header_bg
				),
			);
		}
		// ヘッダー文字色
		$text_color = Option::get_option( 'ys_color_header_font', '' );
		if ( $text_color ) {
			$css_vars = array_merge(
				$css_vars,
				Enqueue_Utility::get_css_var(
					'--ystd--header--text-color',
					$text_color
				),
			);
		}
		// ヘッダー概要文の文字色.
		$description_color = Option::get_option( 'ys_color_header_dscr_font', '' );
		if ( $description_color ) {
			$css_vars = array_merge(
				$css_vars,
				Enqueue_Utility::get_css_var(
					'--ystd--header--description--text-color',
					$description_color
				),
			);
		}
		// ヘッダーボックスシャドウ
		$css_vars = array_merge(
			$css_vars,
			Enqueue_Utility::get_css_var(
				'--ystd--header--shadow',
				$this->get_header_shadow()
			),
		);

		return array_merge(
			$css_vars,
			$this->get_fixed_sidebar_pos()
		);
	}

	/**
	 * 固定サイドバーの位置作成
	 *
	 * @return array
	 */
	private function get_fixed_sidebar_pos() {

		$sidebar_top = Option::get_option_by_int( 'ys_header_fixed_height_pc', 0 );
		$sidebar_top = 0 < $sidebar_top ? ( $sidebar_top + 50 ) . 'px' : '2em';
		if ( ! Option::get_option_by_bool( 'ys_header_fixed', false ) ) {
			$sidebar_top = '2em';
		}

		return Enqueue_Utility::get_css_var(
			'fixed-sidebar-top',
			$sidebar_top
		);
	}

	/**
	 * インラインCSS
	 *
	 * @param string $css CSS.
	 *
	 * @return string
	 */
	public function add_inline_css( $css ) {

		// ロゴ関連のCSS.
		$css .= $this->get_logo_css();
		// ヘッダーシャドウ.
		$css .= $this->get_header_shadow_css();
		// 固定ヘッダー.
		$css .= self::get_fixed_header_css();
		// ヘッダー高さ.
		$css .= self::get_header_height_css();
		// ヘッダータイプ別のCSS.
		$css .= self::get_header_type_css();
		// ヘッダータイプ「center」用のCSS.
		$css .= self::get_type_center_header_css();

		return $css;
	}

	/**
	 * ロゴ関連のCSS取得
	 *
	 * @return string
	 */
	private function get_logo_css() {
		$css = '';
		/**
		 * ロゴ画像の幅設定
		 */
		if ( 0 < Option::get_option_by_int( 'ys_logo_width_sp', 0 ) ) {
			$css .= sprintf(
				'.site-title img{width:%spx;}',
				Option::get_option_by_int( 'ys_logo_width_sp', 0 )
			);
		}
		if ( 0 < Option::get_option_by_int( 'ys_logo_width_pc', 0 ) ) {
			$_css = sprintf(
				'.site-title img{width:%spx;}',
				Option::get_option_by_int( 'ys_logo_width_pc', 0 )
			);
			// モバイル以上サイズ.
			$css .= CSS::add_media_query_over_mobile( $_css );
		}

		return $css;
	}

	/**
	 * 影ありヘッダー用CSS
	 */
	private function get_header_shadow_css() {
		if ( 'none' === $this->get_header_shadow() || Option::get_option_by_bool( 'ys_header_fixed', false ) ) {
			return '';
		}

		return '.site-header {z-index:var(--ystd--z-index--header)}';
	}

	/**
	 * ヘッダー高さ指定.
	 *
	 * @return string
	 */
	public static function get_header_height_css() {
		$css    = '';
		$pc     = Option::get_option_by_int( 'ys_header_fixed_height_pc', 0 );
		$tablet = Option::get_option_by_int( 'ys_header_fixed_height_tablet', 0 );
		$mobile = Option::get_option_by_int( 'ys_header_fixed_height_mobile', 0 );
		if ( 0 < $pc || 0 < $tablet || 0 < $mobile ) {
			$css = '.site-header {height:var(--ys-site-header-height,auto);}';
		}
		if ( 0 < $mobile ) {
			$css .= CSS::add_media_query_mobile(
				":root {--ys-site-header-height:{$mobile}px;}"
			);
		}
		if ( 0 < $tablet ) {
			$css .= CSS::add_media_query_over_mobile(
				":root {--ys-site-header-height:{$tablet}px;}"
			);
		}
		if ( 0 < $pc ) {
			$css .= CSS::add_media_query_desktop(
				":root {--ys-site-header-height:{$pc}px;}"
			);
		}

		return $css;
	}

	/**
	 * 固定ヘッダー用CSS
	 */
	public static function get_fixed_header_css() {
		if ( ! Option::get_option_by_bool( 'ys_header_fixed', false ) ) {
			return '';
		}
		$css    = '
		.has-fixed-header .site-header {
			position: fixed;
			top:0;
			left:0;
			width:100%;
			z-index:var(--ystd--z-index--header);
		}';
		$pc     = Option::get_option_by_int( 'ys_header_fixed_height_pc', 0 );
		$tablet = Option::get_option_by_int( 'ys_header_fixed_height_tablet', 0 );
		$mobile = Option::get_option_by_int( 'ys_header_fixed_height_mobile', 0 );
		if ( 0 < $pc || 0 < $tablet || 0 < $mobile ) {
			$css .= 'body.has-fixed-header {
				padding-top:var(--ys-site-header-height,0);
			}';
		}

		return $css;
	}

	/**
	 * ヘッダータイプ別のCSS取得
	 */
	public static function get_header_type_css() {
		// ドロワーメニューの開始サイズ.
		$drawer_start = (int) Drawer_Menu::get_drawer_menu_start() + 1;
		// CSS作成.
		$css = "
		@media (min-width: {$drawer_start}px) {
			:where(body:not(.header-type--row1)) {
				--ystd--header-type-center--padding-x:calc((100% - var(--ystd--container--size))/2);
			}
			:where(body:not(.header-type--row1) .header-container) {
				max-width: 100%;
			}
			:where(body:not(.header-type--row1) .site-header__content)  {
				display: block;
			}
			:where(body:not(.header-type--row1) .site-branding),
			:where(body:not(.header-type--row1) .global-nav) {
				padding-right:var(--ystd--header-type-center--padding-x);
				padding-left:var(--ystd--header-type-center--padding-x);
			}
			:where(body:not(.header-type--row1) .site-branding) {
				padding-bottom:0.5em;
			}
		}
		";

		return $css;
	}

	/**
	 * タイプ「center」用のCSS
	 */
	public static function get_type_center_header_css() {
		if ( 'center' !== self::get_header_type() ) {
			return '';
		}
		// ドロワーメニューの開始サイズ.
		$drawer_start = (int) Drawer_Menu::get_drawer_menu_start() + 1;
		// CSS作成.
		$css = "
		@media (min-width: {$drawer_start}px) {
			:where(.header-type--center .site-header__content)  {
				text-align: center;
			}
		}";

		return $css;
	}

	/**
	 * サイト基本設定の追加
	 *
	 * @param \WP_Customize_Manager $wp_customize カスタマイザー.
	 */
	public function register_title_tagline( $wp_customize ) {
		$customizer = new Customize_Control( $wp_customize );

		$customizer->set_section_description(
			'title_tagline',
			'サイトロゴやキャッチフレーズの設定' . Admin::manual_link( 'manual/basic-settings' )
		);

		$customizer->set_refresh( 'custom_logo' );
		$customizer->set_post_message( 'blogname' );
		$customizer->set_post_message( 'blogdescription' );

		if ( isset( $wp_customize->selective_refresh ) ) {
			$wp_customize->selective_refresh->add_partial(
				'blogname',
				[
					'selector'            => '.site-title a',
					'container_inclusive' => false,
					'render_callback'     => function () {
						bloginfo( 'name' );
					},
				]
			);
			$wp_customize->selective_refresh->add_partial(
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
		/**
		 * ロゴ
		 */
		$customizer->add_section_label(
			'ロゴ',
			[
				'section'  => 'title_tagline',
				'priority' => 1,
			]
		);
		$customizer->add_checkbox(
			[
				'id'          => 'ys_logo_hidden',
				'default'     => 0,
				'label'       => 'ロゴを非表示にする',
				'description' => 'サイトヘッダーにロゴ画像を表示しない場合はチェックをつけてください',
				'section'     => 'title_tagline',
				'priority'    => 9,
			]
		);
		/**
		 * 幅指定
		 */
		$customizer->add_number(
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

		$customizer->add_number(
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
		$customizer->add_section_label(
			'タイトル・キャッチフレーズ',
			[
				'section'  => 'title_tagline',
				'priority' => 9,
			]
		);
		$customizer->add_text(
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
		$customizer->add_checkbox(
			[
				'id'          => 'ys_wp_hidden_blogdescription',
				'default'     => 0,
				'label'       => 'キャッチフレーズを非表示にする',
				'description' => 'サイトタイトル・ロゴの下にキャッチフレーズを表示したくない場合はチェックを付けて下さい',
				'section'     => 'title_tagline',
				'priority'    => 20,
			]
		);
		/**
		 * サイトアイコン・apple touch icon設定追加
		 */
		$customizer->add_section_label(
			'サイトアイコン',
			[
				'section'  => 'title_tagline',
				'priority' => 20,
			]
		);
		$wp_customize->get_control( 'site_icon' )->description = 'ファビコン用の画像を設定して下さい。縦横512px以上推奨です。';
		$wp_customize->add_setting(
			'ys_apple_touch_icon',
			[
				'type'       => 'option',
				'capability' => 'manage_options',
				'transport'  => 'postMessage',
			]
		);
		$wp_customize->add_control(
			new \WP_Customize_Site_Icon_Control(
				$wp_customize,
				'ys_apple_touch_icon',
				[
					'label'       => 'apple touch icon',
					'description' => 'apple touch icon用の画像を設定して下さい。縦横512spx以上推奨です。',
					'section'     => 'title_tagline',
					'priority'    => 61,
					'height'      => 512,
					'width'       => 512,
				]
			)
		);
	}

	/**
	 * サイトヘッダー設定
	 *
	 * @param \WP_Customize_Manager $wp_customize カスタマイザー.
	 */
	public function register_header_design( $wp_customize ) {
		$customizer = new Customize_Control( $wp_customize );
		/**
		 * セクション追加
		 */
		$customizer->add_section(
			[
				'section'     => 'ys_customizer_section_header_design',
				'title'       => 'サイトヘッダー',
				'description' => 'サイトヘッダー部分のデザイン設定' . Admin::manual_link( 'manual/site-header' ),
				'priority'    => 50,
				'panel'       => Design::PANEL_NAME,
			]
		);
		$customizer->add_section_label( 'ヘッダータイプ' );
		/**
		 * ヘッダータイプ
		 */
		$assets_url = Customizer::get_assets_dir_uri();
		$row1       = $assets_url . '/design/header/1row.png';
		$center     = $assets_url . '/design/header/center.png';
		$row2       = $assets_url . '/design/header/2row.png';
		$img        = '<img src="%s" alt="" width="100" height="100" />';
		$customizer->add_image_label_radio(
			[
				'id'          => 'ys_design_header_type',
				'default'     => 'row1',
				'label'       => '表示タイプ',
				'description' => 'ヘッダーの表示タイプ',
				'section'     => 'ys_customizer_section_header_design',
				'choices'     => [
					'row1'   => sprintf( $img, $row1 ),
					'center' => sprintf( $img, $center ),
					'row2'   => sprintf( $img, $row2 ),
				],
			]
		);
		$customizer->add_section_label( 'ヘッダーデザイン' );
		// ヘッダー背景色.
		$customizer->add_color(
			[
				'id'      => 'ys_color_header_bg',
				'default' => '',
				'label'   => '背景色',
			]
		);
		// サイトタイトル文字色.
		$customizer->add_color(
			[
				'id'      => 'ys_color_header_font',
				'default' => '',
				'label'   => '文字色',
			]
		);
		// サイト概要の文字色.
		$customizer->add_color(
			[
				'id'      => 'ys_color_header_dscr_font',
				'default' => '',
				'label'   => '概要文の文字色',
			]
		);
		// ボックスシャドウ.
		$customizer->add_select(
			[
				'id'      => 'ys_header_box_shadow',
				'default' => 'none',
				'label'   => 'ヘッダーに影をつける',
				'choices' => [
					'none'  => '影なし',
					'small' => '小さめ',
					'large' => '大きめ',
				],
			]
		);
		// 検索フォーム.
		$customizer->add_section_label( '検索フォーム' );
		$customizer->add_label(
			[
				'id'          => 'ys_show_header_search_form_label',
				'label'       => '検索フォーム表示',
				'description' => 'ヘッダーに検索フォームを表示します。<br>モバイルではスライドメニュー内にフォームが表示され、PCでは検索フォーム表示ボタンがヘッダーに追加されます。',
			]
		);
		// スライドメニューに検索フォームを出力する.
		$customizer->add_checkbox(
			[
				'id'      => 'ys_show_header_search_form',
				'default' => 1,
				'label'   => '検索フォームを表示する',
			]
		);

		/**
		 * ヘッダー固定表示
		 */
		$customizer->add_section_label(
			'ヘッダー固定表示',
			[
				'description' => Admin::manual_link( 'fixed-header' ),
			]
		);

		$customizer->add_checkbox(
			[
				'id'      => 'ys_header_fixed',
				'default' => 0,
				'label'   => 'ヘッダーを画面上部に固定する',
			]
		);
		/**
		 * ヘッダー固定表示
		 */
		$customizer->add_label(
			[
				'id'          => 'ys_header_fixed_height_label',
				'label'       => 'ヘッダー高さ',
				'description' => 'ヘッダーの固定表示をする場合、ヘッダー高さを指定すると表示のガタつきを抑えられます。<br><br>プレビュー画面左上に表示された「ヘッダー高さ」の数字を参考に以下の設定に入力してください。',

			]
		);
		/**
		 * ヘッダー高さ(PC)
		 */
		$customizer->add_number(
			[
				'id'      => 'ys_header_fixed_height_pc',
				'default' => 0,
				'label'   => '高さ(PC)',
			]
		);
		/**
		 * ヘッダー高さ(タブレット)
		 */
		$customizer->add_number(
			[
				'id'      => 'ys_header_fixed_height_tablet',
				'default' => 0,
				'label'   => '高さ(タブレット)',
			]
		);
		/**
		 * ヘッダー高さ(モバイル)
		 */
		$customizer->add_number(
			[
				'id'      => 'ys_header_fixed_height_mobile',
				'default' => 0,
				'label'   => '高さ(モバイル)',
			]
		);
	}

}

new Header();
