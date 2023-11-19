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
	 * Global_Nav constructor.
	 */
	public function __construct() {
		add_action( 'customize_register', [ $this, 'customize_register' ] );
		add_filter( Enqueue_Utility::FILTER_CSS_VARS, [ $this, 'css_vars' ] );

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
	public function css_vars( $css_vars ) {

		$bold   = Enqueue_Utility::get_css_var(
			'global-nav-bold',
			Option::get_option( 'ys_global_nav_bold', 'normal' )
		);
		$margin = Enqueue_Utility::get_css_var(
			'global-nav-margin',
			Option::get_option( 'ys_header_nav_margin', 1.5 ) . 'em'
		);

		return array_merge(
			$css_vars,
			$bold,
			$margin
		);
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

	}
}

new Global_Nav();
