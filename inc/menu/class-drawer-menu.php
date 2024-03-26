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
	 * メニュー展開サイズ.
	 */
	const DRAWER_MENU_START_SIZE = 768;
	/**
	 * メニュー展開サイズ取得用フィルター.
	 */
	const DRAWER_MENU_START_SIZE_FILTER = 'ys_get_drawer_menu_start';

	/**
	 * Drawer_Menu constructor.
	 */
	public function __construct() {
		add_action( 'customize_register', [ $this, 'customize_register' ] );
		add_filter( Enqueue_Utility::FILTER_INLINE_CSS, [ $this, 'inline_css' ] );
		add_filter( self::DRAWER_MENU_START_SIZE_FILTER, [ __CLASS__, 'get_drawer_menu_start' ] );
		add_filter( Enqueue_Utility::FILTER_CSS_VARS, [ $this, 'css_vars' ] );
		add_action( 'wp_footer', [ $this, 'drawer_nav' ] );
		add_action( 'init', [ $this, 'set_drawer_nav_search_form' ] );
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
		if ( AMP::is_amp() ) {
			$attr[] = 'on="tap:mobile-menu.' . $type . '"';
		}

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

		$mobile_nav_bg    = [];
		$mobile_nav_color = [];
		$mobile_nav_open  = [];
		$mobile_nav_close = [];

		$mobile_nav_bg_option = Option::get_option( 'ys_color_nav_bg_sp', '' );
		if ( $mobile_nav_bg_option ) {
			$mobile_nav_bg = Enqueue_Utility::get_css_var(
				'mobile-nav-bg',
				$mobile_nav_bg_option
			);
		}
		$mobile_nav_color_option = Option::get_option( 'ys_color_nav_font_sp', '' );
		if ( $mobile_nav_color_option ) {
			$mobile_nav_color = Enqueue_Utility::get_css_var(
				'mobile-nav-text',
				$mobile_nav_color_option
			);
		}

		$mobile_nav_open_option = Option::get_option( 'ys_color_nav_btn_sp_open', '' );
		if ( $mobile_nav_open_option ) {
			$mobile_nav_open = Enqueue_Utility::get_css_var(
				'mobile-nav-open',
				$mobile_nav_open_option
			);
		}

		$mobile_nav_close_option = Option::get_option( 'ys_color_nav_btn_sp', '' );
		if ( $mobile_nav_close_option ) {
			$mobile_nav_close = Enqueue_Utility::get_css_var(
				'mobile-nav-close',
				$mobile_nav_close_option
			);
		}

		return array_merge(
			$css_vars,
			$mobile_nav_bg,
			$mobile_nav_color,
			$mobile_nav_open,
			$mobile_nav_close
		);
	}

	/**
	 * 開閉式グローバルナビゲーション用
	 *
	 * @param string $css CSS.
	 *
	 * @return string
	 */
	public function inline_css( $css ) {

		$breakpoint = apply_filters(
			self::DRAWER_MENU_START_SIZE_FILTER,
			self::DRAWER_MENU_START_SIZE
		);

		$drawer_menu_css = <<<CSS
		@media (min-width: {$breakpoint}px) {
			:where(.global-nav) {
				display: var(--ystd--global-nav--display);
			}
			:where(.global-nav__toggle) {
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
	public static function get_drawer_menu_start() {
		$size = Option::get_option_by_int( 'ys_drawer_menu_start', self::DRAWER_MENU_START_SIZE );
		if ( Option::get_option_by_bool( 'ys_always_drawer_menu', false ) ) {
			$size = 9999;
		}

		return $size;
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
				'section'     => 'ys_customizer_section_drawer_menu',
				'title'       => 'ドロワーメニュー',
				'description' => 'ドロワーメニュー設定' . Admin::manual_link( 'manual/drawer-menu' ),
				'priority'    => 70,
				'panel'       => Design::PANEL_NAME,
			]
		);

		$customizer->add_section_label(
			'ドロワーメニュー表示設定',
			[
				'description' => 'グローバルメニューの折りたたみ表示に関する設定',
			]
		);
		$customizer->add_number(
			[
				'id'          => 'ys_drawer_menu_start',
				'default'     => self::DRAWER_MENU_START_SIZE,
				'label'       => 'ドロワーメニュー開始サイズ(px)',
				'description' => '設定した画面サイズ以下でドロワーメニュー表示になります。',
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
				'label'       => '常にドロワーメニュー表示にする',
				'description' => 'チェックをつけるとグローバルメニューが常にドロワーメニューになります。',
			]
		);
		$customizer->add_checkbox(
			[
				'id'      => 'ys_always_drawer_menu',
				'label'   => '常にドロワーメニュー表示にする',
				'default' => 0,
			]
		);

		$customizer->add_section_label( '色設定' );
		// ナビゲーション背景色（SP）.
		$customizer->add_color(
			[
				'id'      => 'ys_color_nav_bg_sp',
				'default' => '',
				'label'   => 'メニュー背景色',
			]
		);
		// ナビゲーション文字色（SP）.
		$customizer->add_color(
			[
				'id'      => 'ys_color_nav_font_sp',
				'default' => '',
				'label'   => 'メニュー文字色',
			]
		);
		// ナビゲーションボタン色（SP）.
		$customizer->add_color(
			[
				'id'      => 'ys_color_nav_btn_sp_open',
				'default' => '',
				'label'   => 'メニュー開閉ボタン色：開く',
			]
		);
		// ナビゲーションボタン色（SP）.
		$customizer->add_color(
			[
				'id'      => 'ys_color_nav_btn_sp',
				'default' => '',
				'label'   => 'メニュー開閉ボタン色：閉じる',
			]
		);
	}
}

new Drawer_Menu();
