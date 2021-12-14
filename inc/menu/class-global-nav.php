<?php
/**
 * グローバルナビゲーション
 *
 * @package ystandard
 * @author  yosiakatsuki
 * @license GPL-2.0+
 */

namespace ystandard;

use ystandard\helper\Filesystem;

defined( 'ABSPATH' ) || die();

/**
 * Class Global_Nav
 *
 * @package ystandard
 */
class Global_Nav {

	/**
	 * メニュー展開サイズ.
	 */
	const GLOBAL_NAV_EXPAND = 768;
	/**
	 * メニュー展開サイズ取得用フィルター.
	 */
	const GLOBAL_NAV_EXPAND_FILTER = 'ys_get_global_nav_expand';


	public function __construct() {
		add_action( 'customize_register', [ $this, 'customize_register' ] );
		add_filter( Enqueue_Utility::FILTER_INLINE_CSS, [ $this, 'global_nav_inline_css' ] );
		add_filter( Enqueue_Utility::FILTER_CSS_VARS, [ $this, 'global_nav_css_vars' ] );
		add_filter( self::GLOBAL_NAV_EXPAND_FILTER, [ __CLASS__, 'get_global_nav_expand' ] );
	}

	/**
	 * グローバルナビゲーションクラス
	 *
	 * @param string $class class.
	 *
	 * @return string
	 */
	public static function get_global_nav_class( $class ) {
		$class   = [ $class ];
		$bg      = Option::get_option( 'ys_color_header_bg', '#ffffff' );
		$default = Option::get_default( 'ys_color_header_bg', '#ffffff' );
		if ( $bg !== $default ) {
			$class[] = 'has-background';
		}
		if ( ! has_nav_menu( 'global' ) ) {
			$class[] = 'is-no-global-nav';
		}

		return trim( implode( ' ', $class ) );
	}

	/**
	 * グローバルナビゲーションを表示するか
	 *
	 * @return boolean
	 */
	public static function has_global_nav() {
		$result = has_nav_menu( 'global' );

		return Utility::to_bool( apply_filters( 'ys_has_global_nav', $result ) );
	}

	/**
	 * グローバルナビゲーションワーカー
	 *
	 * @return \Walker_Nav_Menu
	 */
	public static function global_nav_walker() {
		$result = new \YS_Walker_Global_Nav_Menu();

		return apply_filters( 'ys_global_nav_walker', $result );
	}

	/**
	 * カスタムプロパティ指定.
	 *
	 * @param array $css_vars カスタムプロパティリスト.
	 *
	 * @return array
	 */
	public function global_nav_css_vars( $css_vars ) {

		$toggle_top = Option::get_option_by_int( 'ys_global_nav_toggle_top', 0 );
		if ( 0 !== $toggle_top ) {
			$css_vars['mobile-nav-toggle-top'] = "${toggle_top}px";
		}
		$css_vars['global-nav-bold'] = Option::get_option(
			'ys_global_nav_bold',
			'normal'
		);

		return $css_vars;
	}

	/**
	 * 開閉式グローバルナビゲーション用
	 *
	 * @param string $css CSS.
	 *
	 * @return string
	 */
	public function global_nav_inline_css( $css ) {

		$global_nav_css = Filesystem::file_get_contents(
			get_template_directory() . '/css/global-navigation-fold.css'
		);
		$breakpoint     = apply_filters(
			self::GLOBAL_NAV_EXPAND_FILTER,
			self::GLOBAL_NAV_EXPAND
		);
		if ( Option::get_option_by_bool( 'ys_global_nav_expand_none', false ) ) {
			$breakpoint = 9999;
		}

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
	public static function get_global_nav_expand() {
		return Option::get_option_by_int( 'ys_global_nav_expand', self::GLOBAL_NAV_EXPAND );
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
				'section'     => 'ys_customizer_section_global_nav',
				'title'       => 'グローバルナビゲーション',
				'description' => 'グローバルナビゲーション設定' . Admin::manual_link( 'manual/global-nav' ),
				'priority'    => 60,
				'panel'       => Design::PANEL_NAME,
			]
		);
		$customizer->add_section_label( 'グローバルメニューデザイン' );
		$customizer->add_number(
			[
				'id'          => 'ys_header_nav_margin',
				'default'     => 1.5,
				'label'       => 'メニュー項目の間隔',
				'description' => '単位はemです。大きくすればメニュー間の余白が大きくなります。',
				'input_attrs' => [
					'min'  => 0.1,
					'max'  => 10.0,
					'step' => 0.1,
				],
			]
		);
		$customizer->add_select(
			[
				'id'          => 'ys_global_nav_bold',
				'default'     => 'normal',
				'label'       => 'メニュー文字太さ',
				'description' => 'トップレベルのメニュー文字太さ設定。',
				'choices'     => [
					'normal' => __( '通常(normal)', 'ystandard' ),
					'bold'   => __( '太字(bold)', 'ystandard' ),
					'100'    => __( '100', 'ystandard' ),
					'200'    => __( '200', 'ystandard' ),
					'300'    => __( '300', 'ystandard' ),
					'400'    => __( '400', 'ystandard' ),
					'500'    => __( '500', 'ystandard' ),
					'600'    => __( '600', 'ystandard' ),
					'700'    => __( '700', 'ystandard' ),
					'800'    => __( '800', 'ystandard' ),
					'900'    => __( '900', 'ystandard' ),
				],
			]
		);
		$customizer->add_section_label(
			'ドロワーメニュー設定',
			[
				'description' => 'グローバルメニューの折りたたみ表示に関する設定',
			]
		);
		$customizer->add_number(
			[
				'id'          => 'ys_global_nav_expand',
				'default'     => self::GLOBAL_NAV_EXPAND,
				'label'       => 'ドロワーメニュー開始サイズ(px)',
				'description' => '設定した画面サイズ以下でドロワーメニュー表示になります。',
				'input_attrs' => [
					'min'  => 600,
					'max'  => 1440,
					'step' => 1,
				],
			]
		);
		$customizer->add_label(
			[
				'id'          => 'ys_global_nav_expand_none_label',
				'label'       => '常にドロワーメニュー表示にする',
				'description' => 'チェックをつけるとグローバルメニューが常にドロワーメニューになります。',
			]
		);
		$customizer->add_checkbox(
			[
				'id'      => 'ys_global_nav_expand_none',
				'label'   => '常にドロワーメニュー表示にする',
				'default' => 0,
			]
		);
		$customizer->add_number(
			[
				'id'          => 'ys_global_nav_toggle_top',
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
	}
}

new Global_Nav();
