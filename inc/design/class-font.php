<?php
/**
 * フォント関連管理 クラス
 *
 * @package ystandard
 * @author  yosiakatsuki
 * @license GPL-2.0+
 */

namespace ystandard;

defined( 'ABSPATH' ) || die();

/**
 * Class Font
 *
 * @package ystandard
 */
class Font {

	/**
	 * Font constructor.
	 */
	public function __construct() {
		add_filter( 'ys_css_vars', [ $this, 'add_css_vars' ] );
		add_action( 'customize_register', [ $this, 'customize_register' ] );
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
				'section'     => 'ys_section_font',
				'title'       => 'フォント・文字色',
				'description' => 'フォント・文字色の設定' . Admin::manual_link( 'manual/font' ),
				'priority'    => 10,
				'panel'       => Design::PANEL_NAME,
			]
		);
		/**
		 * フォント種類
		 */
		$customizer->add_radio(
			[
				'id'          => 'ys_design_font_type',
				'default'     => 'meihiragino',
				'label'       => '表示フォントタイプ',
				'description' => '文字のフォントを変更できます',
				'choices'     => [
					'meihiragino' => $this->get_font_label( 'meihiragino' ),
					'yugo'        => $this->get_font_label( 'yugo' ),
					'serif'       => $this->get_font_label( 'serif' ),
				],
			]
		);
		/**
		 * 文字色
		 */
		$customizer->add_color(
			[
				'id'      => 'ys_color_site_text',
				'default' => '#222222',
				'label'   => '文字色',
			]
		);
		/**
		 * グレー文字色
		 */
		$customizer->add_color(
			[
				'id'          => 'ys_color_site_gray',
				'default'     => '#656565',
				'label'       => 'グレー文字色',
				'description' => '少し薄めの色で表示される部分の色設定',
			]
		);
	}

	/**
	 * フォント選択肢のラベルを取得
	 *
	 * @param string $type タイプ名.
	 *
	 * @return string
	 */
	private function get_font_label( $type ) {
		$fonts = self::get_usable_fonts();

		return $fonts[ $type ]['label'];
	}

	/**
	 * フォントCSS
	 *
	 * @param array $css_vars CSS.
	 *
	 * @return array
	 */
	public function add_css_vars( $css_vars ) {
		$font_family = 'sans-serif';
		$font        = self::get_usable_fonts();

		$option = Option::get_option( 'ys_design_font_type', 'meihiragino' );
		if ( isset( $font[ $option ] ) ) {
			$font_family = $font[ $option ]['family'];
		}

		return array_merge(
			$css_vars,
			Enqueue_Utility::get_css_var( 'font-family', $font_family ),
			Enqueue_Utility::get_css_var(
				'font-color',
				Option::get_option( 'ys_color_site_text', '#222222' ),
				Option::get_default( 'ys_color_site_text', '#222222' )
			),
			Enqueue_Utility::get_css_var(
				'font-gray',
				Option::get_option( 'ys_color_site_gray', '#656565' ),
				Option::get_default( 'ys_color_site_gray', '#656565' )
			)
		);
	}

	/**
	 * 選べるフォントのリスト取得
	 *
	 * @return array
	 */
	public static function get_usable_fonts() {
		return apply_filters(
			'ys_usable_fonts',
			[
				'meihiragino' => [
					'family' => '"Helvetica neue", Arial, "Hiragino Sans", "Hiragino Kaku Gothic ProN", Meiryo, sans-serif',
					'label'  => 'メイリオ・ヒラギノ角ゴシック',
				],
				'yugo'        => [
					'family' => 'Avenir, "Segoe UI", YuGothic, "Yu Gothic Medium", sans-serif',
					'label'  => '游ゴシック',
				],
				'serif'       => [
					'family' => 'serif',
					'label'  => '明朝体',
				],
			]
		);
	}
}

new Font();
