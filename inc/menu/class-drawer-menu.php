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
	}

	/**
	 * メニュー開閉ボタン取得
	 *
	 * @param string $type 開閉タイプ.
	 *
	 * @return string
	 */
	public static function get_toggle_button( $type = 'toggle' ) {
		$icon = Icon::get_icon( 'menu' );
		$attr = [
			'id="global-nav__toggle"',
			'class="global-nav__toggle"',
			'data-label-open="menu"',
			'data-label-close="close"',
		];
		if ( AMP::is_amp() ) {
			$attr[] = 'on="tap:mobile-menu.' . $type . '"';
		}

		return sprintf(
			'<button %s>%s</button>',
			trim( implode( ' ', $attr ) ),
			$icon
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

		$toggle_top = Option::get_option_by_int( 'ys_drawer_menu_toggle_top', 0 );
		if ( 0 !== $toggle_top ) {
			$css_vars['mobile-nav-toggle-top'] = "${toggle_top}px";
		}
		$mobile_nav_bg    = Enqueue_Utility::get_css_var(
			'mobile-nav-bg',
			Option::get_option( 'ys_color_nav_bg_sp', '#000000' )
		);
		$mobile_nav_color = Enqueue_Utility::get_css_var(
			'mobile-nav-text',
			Option::get_option( 'ys_color_nav_font_sp', '#ffffff' )
		);
		$mobile_nav_open  = Enqueue_Utility::get_css_var(
			'mobile-nav-open',
			Option::get_option( 'ys_color_nav_btn_sp_open', '#222222' )
		);
		$mobile_nav_close = Enqueue_Utility::get_css_var(
			'mobile-nav-close',
			Option::get_option( 'ys_color_nav_btn_sp', '#ffffff' )
		);

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

		$global_nav_css = Filesystem::file_get_contents(
			get_template_directory() . '/css/drawer-menu.css'
		);
		$breakpoint     = apply_filters(
			self::DRAWER_MENU_START_SIZE_FILTER,
			self::DRAWER_MENU_START_SIZE
		);

		$css .= str_replace(
			'{{mobaile-nav-breakpoint}}',
			$breakpoint . 'px',
			$global_nav_css
		);

		return $css;
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
				'description' => '設定した画面サイズ以下でドロワーメニュー表示になります。(600~1440)',
				'input_attrs' => [
					'min'  => 600,
					'max'  => 1440,
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
		$customizer->add_section_label( 'ドロワーメニュー開閉ボタン設定' );
		$customizer->add_number(
			[
				'id'          => 'ys_drawer_menu_toggle_top',
				'default'     => 0,
				'label'       => 'メニュー開閉ボタンの縦位置調整(px)',
				'description' => 'メニュー開閉ボタンの上下位置を微調整できます。',

				'input_attrs' => [
					'min'  => - 100,
					'max'  => 100,
					'step' => 1,
				],
			]
		);
		$customizer->add_section_label( '色設定' );
		// ナビゲーション背景色（SP）.
		$customizer->add_color(
			[
				'id'      => 'ys_color_nav_bg_sp',
				'default' => '#000000',
				'label'   => 'メニュー背景色',
			]
		);
		// ナビゲーション文字色（SP）.
		$customizer->add_color(
			[
				'id'      => 'ys_color_nav_font_sp',
				'default' => '#ffffff',
				'label'   => 'メニュー文字色',
			]
		);
		// ナビゲーションボタン色（SP）.
		$customizer->add_color(
			[
				'id'      => 'ys_color_nav_btn_sp_open',
				'default' => '#222222',
				'label'   => 'メニュー開閉ボタン色：開く',
			]
		);
		// ナビゲーションボタン色（SP）.
		$customizer->add_color(
			[
				'id'      => 'ys_color_nav_btn_sp',
				'default' => '#ffffff',
				'label'   => 'メニュー開閉ボタン色：閉じる',
			]
		);
	}
}

new Drawer_Menu();
