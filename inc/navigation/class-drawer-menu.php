<?php
/**
 * ドロワーメニュー
 *
 * @package ystandard
 * @author  yosiakatsuki
 * @license GPL-2.0+
 */

namespace ystandard;

use ystandard\helper\Filesystem;

defined( 'ABSPATH' ) || die();

/**
 * Class Drawer_Menu
 *
 * @package ystandard
 */
class Drawer_Menu {

	/**
	 * Panel Name.
	 */
	const PANEL_NAME = 'ys_drawer_nav';

	/**
	 * メニュー展開サイズ.
	 */
	const DRAWER_MENU_START_SIZE = 768;

	/**
	 * Drawer_Menu constructor.
	 */
	public function __construct() {
		add_action( 'customize_register', [ $this, 'customize_register' ] );
		add_filter( 'ys_get_inline_css', [ $this, 'inline_css' ] );
		add_filter( 'ys_get_css_custom_properties_args', [ $this, 'css_vars' ] );
		add_action( 'wp_footer', [ $this, 'drawer_nav' ] );
		add_action( 'init', [ $this, 'set_drawer_nav_search_form' ] );
		// 他プラグインでフィルターを使って値を取得できる。yStandard非依存のため.
		add_filter( 'ys_get_drawer_menu_start', [ __CLASS__, 'get_drawer_menu_start_option' ], 1 );
	}

	/**
	 * ドロワーメニューHTMLの出力
	 *
	 * @return void
	 */
	public function drawer_nav() {
		get_template_part( 'template-parts/navigation/drawer-nav' );
	}

	/**
	 * ドロワーメニュー検索フォーム設定
	 *
	 * @return void
	 */
	public function set_drawer_nav_search_form() {
		$drawer_nav_hook = apply_filters( 'ys_drawer_menu_search_form_hook', 'ys_before_drawer_nav_menu' );
		// フック名を消してフォームを表示しない…ということも可能.
		if ( $drawer_nav_hook ) {
			add_action(
				$drawer_nav_hook,
				function () {
					ys_get_template_part( 'template-parts/navigation/drawer-nav-search-form' );
				}
			);
		}
	}

	/**
	 * メニュー開閉ボタン取得
	 *
	 * @param array $args {
	 * 引数.
	 *
	 * @type string $type ボタンタイプ.
	 * @type string $id ID.
	 * @type string $class クラス.
	 * }
	 *
	 * @return string
	 */
	public static function get_toggle_button( $args = [] ) {
		$args  = wp_parse_args(
			$args,
			[
				'type'  => 'toggle',
				'id'    => 'global-nav__toggle',
				'class' => 'global-nav__toggle',
			]
		);
		$type  = $args['type'];
		$id    = $args['id'];
		$class = $args['class'];

		$icon = apply_filters( 'ys_get_drawer_menu_icon', Icon::get_icon( 'menu' ) );
		$attr = [
			"id=\"{$id}\"",
			"class=\"{$class}\"",
			'data-label-open="menu"',
			'data-label-close="close"',
		];

		return apply_filters(
			'ys_get_toggle_button_html',
			sprintf(
				'<button %s>%s</button>',
				trim( implode( ' ', $attr ) ),
				$icon
			)
		);
	}

	/**
	 * カスタムプロパティ指定.
	 *
	 * @param array $css_vars カスタムプロパティリスト.
	 *
	 * @return array
	 */
	public function css_vars( $css_vars ) {

		$mobile_nav_bg_option = Option::get_option( 'ys_color_nav_bg_sp', '' );
		if ( $mobile_nav_bg_option ) {
			$css_vars = array_merge(
				$css_vars,
				Enqueue_Utility::get_css_var(
					'--ystd--drawer-menu--background',
					$mobile_nav_bg_option
				)
			);
		}
		$mobile_nav_color_option = Option::get_option( 'ys_color_nav_font_sp', '' );
		if ( $mobile_nav_color_option ) {
			$css_vars = array_merge(
				$css_vars,
				Enqueue_Utility::get_css_var(
					'--ystd--drawer-menu--text-color',
					$mobile_nav_color_option
				)
			);
		}

		$mobile_nav_open_option = Option::get_option( 'ys_color_nav_btn_sp_open', '' );
		if ( $mobile_nav_open_option ) {
			$css_vars = array_merge(
				$css_vars,
				Enqueue_Utility::get_css_var(
					'--ystd--drawer-menu--button-color--open',
					$mobile_nav_open_option
				)
			);
		}

		$mobile_nav_close_option = Option::get_option( 'ys_color_nav_btn_sp', '' );
		if ( $mobile_nav_close_option ) {
			$css_vars = array_merge(
				$css_vars,
				Enqueue_Utility::get_css_var(
					'--ystd--drawer-menu--button-color--close',
					$mobile_nav_close_option
				)
			);
		}

		$font_size_option = Option::get_option( 'ys_drawer_menu_font_size', '' );
		if ( $font_size_option ) {
			if ( is_numeric( $font_size_option ) ) {
				$font_size_option .= 'px';
			}
			$css_vars = array_merge(
				$css_vars,
				Enqueue_Utility::get_css_var(
					'--ystd--drawer-menu--font-size',
					$font_size_option
				)
			);
		}

		$sub_menu_font_size_option = Option::get_option( 'ys_drawer_menu_sub_menu_font_size', '' );
		if ( $sub_menu_font_size_option ) {
			if ( is_numeric( $sub_menu_font_size_option ) ) {
				$sub_menu_font_size_option .= 'px';
			}
			$css_vars = array_merge(
				$css_vars,
				Enqueue_Utility::get_css_var(
					'--ystd--drawer-menu--sub-menu--font-size',
					$sub_menu_font_size_option
				)
			);
		}

		return $css_vars;
	}

	/**
	 * 開閉式グローバルナビゲーション用
	 *
	 * @param string $css CSS.
	 *
	 * @return string
	 */
	public function inline_css( $css ) {

		$breakpoint = (int) self::get_drawer_menu_start() + 1;

		$drawer_menu_css = <<<CSS
		@media (min-width: {$breakpoint}px) {
			:where(.global-nav) {
				display: var(--ystd--global-nav--display);
			}
			:where(.site-header .global-nav__toggle) {
				display: none;
			}
		}
CSS;

		return $css . $drawer_menu_css;
	}

	/**
	 * メニュー展開サイズ取得
	 *
	 * @return int
	 */
	public static function get_drawer_menu_start_option() {
		$size = Option::get_option_by_int( 'ys_drawer_menu_start', self::DRAWER_MENU_START_SIZE );
		if ( Option::get_option_by_bool( 'ys_always_drawer_menu', false ) ) {
			$size = 9999;
		}

		return $size;
	}

	/**
	 * フィルターを通したメニュー展開サイズ取得
	 *
	 * @return int
	 */
	public static function get_drawer_menu_start() {
		return apply_filters( 'ys_get_drawer_menu_start', self::DRAWER_MENU_START_SIZE );
	}

	/**
	 * メニュー設定
	 *
	 * @param \WP_Customize_Manager $wp_customize カスタマイザー.
	 */
	public function customize_register( $wp_customize ) {
		$customizer = new Customize_Control( $wp_customize );
		/**
		 * セクション追加
		 */
		$customizer->add_section(
			[
				'title'       => '[ys]' . __( 'ドロワーメニュー', 'ystandard' ),
				'description' => __( 'ドロワーメニュー設定', 'ystandard' ) . Admin::manual_link( 'manual/drawer-menu' ),
				'section'     => self::PANEL_NAME,
				'priority'    => Customizer::get_priority( self::PANEL_NAME ),
			]
		);

		$customizer->add_section_label(
			__( 'ドロワーメニュー表示設定', 'ystandard' ),
			[
				'description' => __( 'グローバルメニューの折りたたみ表示に関する設定', 'ystandard' ),
			]
		);
		$customizer->add_number(
			[
				'id'          => 'ys_drawer_menu_start',
				'default'     => self::DRAWER_MENU_START_SIZE,
				'label'       => __( 'ドロワーメニュー開始サイズ(px)', 'ystandard' ),
				'description' => __( '設定した画面サイズ以下でドロワーメニュー表示になります。', 'ystandard' ),
				'input_attrs' => [
					'min'  => 0,
					'max'  => 9999,
					'step' => 1,
				],
			]
		);
		$customizer->add_label(
			[
				'id'          => 'ys_always_drawer_menu_label',
				'label'       => __( '常にドロワーメニュー表示にする', 'ystandard' ),
				'description' => __( 'チェックをつけるとグローバルメニューが常にドロワーメニューになります。', 'ystandard' ),
			]
		);
		$customizer->add_checkbox(
			[
				'id'      => 'ys_always_drawer_menu',
				'label'   => __( '常にドロワーメニュー表示にする', 'ystandard' ),
				'default' => 0,
			]
		);

		$customizer->add_section_label( __( '色設定', 'ystandard' ) );
		// ナビゲーション背景色（SP）.
		$customizer->add_color(
			[
				'id'      => 'ys_color_nav_bg_sp',
				'default' => '',
				'label'   => __( 'メニュー背景色', 'ystandard' ),
			]
		);
		// ナビゲーション文字色（SP）.
		$customizer->add_color(
			[
				'id'      => 'ys_color_nav_font_sp',
				'default' => '',
				'label'   => __( 'メニュー文字色', 'ystandard' ),
			]
		);
		// ナビゲーションボタン色（SP）.
		$customizer->add_color(
			[
				'id'      => 'ys_color_nav_btn_sp_open',
				'default' => '',
				'label'   => __( 'メニュー開閉ボタン色：開く', 'ystandard' ),
			]
		);
		// ナビゲーションボタン色（SP）.
		$customizer->add_color(
			[
				'id'      => 'ys_color_nav_btn_sp',
				'default' => '',
				'label'   => __( 'メニュー開閉ボタン色：閉じる', 'ystandard' ),
			]
		);

		$customizer->add_section_label( __( '文字サイズ', 'ystandard' ) );
		$customizer->add_text(
			[
				'id'          => 'ys_drawer_menu_font_size',
				'default'     => '',
				'label'       => __( '文字サイズ', 'ystandard' ),
				'description' => __( 'ドロワーメニュー文字サイズ設定。数値のみ(単位なし)の場合、自動でpxが単位として追加されます。', 'ystandard' ),
			]
		);
		$customizer->add_text(
			[
				'id'          => 'ys_drawer_menu_sub_menu_font_size',
				'default'     => '',
				'label'       => __( 'サブメニュー文字サイズ', 'ystandard' ),
				'description' => __( 'サブメニュー（2階層目）の文字サイズ設定。数値のみ(単位なし)の場合、自動でpxが単位として追加されます。', 'ystandard' ),
			]
		);
	}
}

new Drawer_Menu();
