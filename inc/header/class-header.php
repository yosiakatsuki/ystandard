<?php
/**
 * Header 関連
 *
 * @package ystandard
 * @author  yosiakatsuki
 * @license GPL-2.0+
 */

namespace ystandard;

use ystandard\helper\Style_Sheet;

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
		add_filter( Enqueue_Utility::FILTER_INLINE_CSS, [ $this, 'add_inline_css' ] );
		add_filter( Enqueue_Utility::FILTER_CSS_VARS, [ $this, 'add_css_var' ] );
		add_action( 'wp_enqueue_scripts', [ $this, 'enqueue_script_amp' ] );
		add_action( 'wp_footer', [ $this, 'amp_mobile_nav' ] );
	}

	/**
	 * AMPページでScriptエンキュー
	 */
	public function enqueue_script_amp() {
		if ( ! AMP::is_amp() ) {
			return;
		}
		wp_enqueue_script(
			'amp-sidebar',
			'https://cdn.ampproject.org/v0/amp-sidebar-0.1.js',
			[],
			null
		);
		Enqueue_Utility::add_async( 'amp-sidebar' );
		Enqueue_Utility::add_custom_element( 'amp-sidebar', 'amp-sidebar' );
	}

	/**
	 * AMP用モバイルナビゲーション
	 */
	public function amp_mobile_nav() {
		if ( ! AMP::is_amp() ) {
			return;
		}

		ys_get_template_part( 'template-parts/header/amp-nav' );
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

		if ( AMP::is_amp() ) {
			return false;
		}

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
		$site_cover    = Enqueue_Utility::get_css_var(
			'site-cover',
			Option::get_option( 'ys_color_header_bg', '#ffffff' )
		);
		$header_bg     = Enqueue_Utility::get_css_var(
			'header-bg',
			Option::get_option( 'ys_color_header_bg', '#ffffff' )
		);
		$header_color  = Enqueue_Utility::get_css_var(
			'header-text',
			Option::get_option( 'ys_color_header_font', '#222222' )
		);
		$header_dscr   = Enqueue_Utility::get_css_var(
			'header-dscr',
			Option::get_option( 'ys_color_header_dscr_font', '#656565' )
		);
		$header_shadow = Enqueue_Utility::get_css_var(
			'header-shadow',
			$this->get_header_shadow()
		);

		return array_merge(
			$css_vars,
			$site_cover,
			$header_bg,
			$header_color,
			$header_dscr,
			$header_shadow,
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

		$css .= $this->get_logo_css();
		$css .= $this->get_header_shadow_css();
		$css .= self::get_fixed_header_css();
		$css .= self::get_header_height_css();

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
			$css .= Style_Sheet::add_media_query(
				sprintf(
					'.site-title img{width:%spx;}',
					Option::get_option_by_int( 'ys_logo_width_pc', 0 )
				),
				'md'
			);
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

		return '.site-header {z-index:var(--z-index-header)}';
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
			$css = '.site-header {
				height:var(--ys-site-header-height,auto);
			}';
		}
		if ( 0 < $mobile ) {
			$css .= Style_Sheet::add_media_query(
				":root {
					--ys-site-header-height:{$mobile}px;
				}",
				'',
				'sm'
			);
		}
		if ( 0 < $tablet ) {
			$css .= Style_Sheet::add_media_query(
				":root {
					--ys-site-header-height:{$tablet}px;
				}",
				'sm'
			);
		}
		if ( 0 < $pc ) {
			$css .= Style_Sheet::add_media_query(
				":root {
					--ys-site-header-height:{$pc}px;
				}",
				'md'
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
			z-index:var(--z-index-header);
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
				'default' => '#ffffff',
				'label'   => '背景色',
			]
		);
		// サイトタイトル文字色.
		$customizer->add_color(
			[
				'id'      => 'ys_color_header_font',
				'default' => '#222222',
				'label'   => '文字色',
			]
		);
		// サイト概要の文字色.
		$customizer->add_color(
			[
				'id'      => 'ys_color_header_dscr_font',
				'default' => '#656565',
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
