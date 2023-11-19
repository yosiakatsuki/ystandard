<?php
/**
 * 色関連 クラス
 *
 * @package ystandard
 * @author  yosiakatsuki
 * @license GPL-2.0+
 */

namespace ystandard;

defined( 'ABSPATH' ) || die();

/**
 * Class Color
 *
 * @package ystandard
 */
class Color {
	/**
	 * リンクカラーデフォルト
	 */
	const LINK_DEFAULT = '#2980b9';
	/**
	 * リンクカラー(hover)デフォルト
	 */
	const LINK_HOVER_DEFAULT = '#409ad5';

	/**
	 * アクション・フィルターの登録
	 */
	public function register() {
		add_action( 'customize_register', [ $this, 'customize_register' ] );
		add_filter( 'ys_css_vars', [ $this, 'add_css_vars' ] );
	}

	/**
	 * フォントCSS
	 *
	 * @param array $css_vars CSS.
	 *
	 * @return array
	 */
	public function add_css_vars( $css_vars ) {

		$link       = [];
		$link_hover = [];

		if ( self::LINK_DEFAULT !== Option::get_option( 'ys_color_link', self::LINK_DEFAULT ) ) {
			$link = Enqueue_Utility::get_css_var(
				'link-text',
				Option::get_option( 'ys_color_link', self::LINK_DEFAULT )
			);
		}
		if ( self::LINK_HOVER_DEFAULT !== Option::get_option( 'ys_color_link_hover', self::LINK_HOVER_DEFAULT ) ) {
			$link_hover = Enqueue_Utility::get_css_var(
				'link-text-hover',
				Option::get_option( 'ys_color_link_hover', self::LINK_DEFAULT )
			);
		}

		return array_merge(
			$css_vars,
			$link,
			$link_hover
		);
	}

	/**
	 * カスタマイザー追加
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
				'section'     => 'ys_section_link_color',
				'title'       => 'リンクカラー',
				'description' => 'リンクカラーの設定' . Admin::manual_link( 'manual/link-color' ),
				'priority'    => 20,
				'panel'       => Design::PANEL_NAME,
			]
		);
		// リンク色.
		$customizer->add_color(
			[
				'id'      => 'ys_color_link',
				'default' => self::LINK_DEFAULT,
				'label'   => 'リンク色',
			]
		);
		// ホバー色.
		$customizer->add_color(
			[
				'id'      => 'ys_color_link_hover',
				'default' => self::LINK_HOVER_DEFAULT,
				'label'   => 'リンク色(マウスホバー)',
			]
		);
	}
}

$class_color = new Color();
$class_color->register();
