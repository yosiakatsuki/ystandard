<?php
/**
 * フォント関連管理 クラス
 *
 * @package ystandard
 * @author  yosiakatsuki
 * @license GPL-2.0+
 */

namespace ystandard;

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
		add_action( 'customize_register', [ $this, 'customize_register' ] );
		add_filter( 'ys_css_vars', [ $this, 'add_css_vars' ] );
		add_filter( Enqueue_Styles::INLINE_CSS_HOOK, [ $this, 'add_font_sizes_css' ] );
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
				'title'       => 'フォント設定',
				'description' => 'フォントの設定',
				'priority'    => '10',
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
	 * @param string $css CSS
	 *
	 * @return array
	 */
	public function add_css_vars( $css_vars ) {
		$font_family = 'sans-serif';
		$font        = self::get_usable_fonts();

		$option = Option::get_option( 'ys_design_font_type', 'meihiragino' );
		if ( isset( $font[ $option ] ) ) {
			$font_family = $font[ $option ];
		}

		return array_merge(
			$css_vars,
			Css_Vars::get_css_var( 'font-family', $font_family )
		);
	}

	/**
	 * フォントサイズ選択肢用CSS追加
	 *
	 * @param string $css CSS.
	 *
	 * @return string
	 */
	public function add_font_sizes_css( $css ) {

		return $css . self::get_editor_font_size_css();
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
					'label'  => 'メイリオ・ヒラギノ',
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

	/**
	 * ブロックエディター文字サイズ設定
	 *
	 * @return array
	 */
	public static function get_editor_font_sizes() {
		$size = [
			[
				'name'      => __( '極小', 'ystandard' ),
				'shortName' => __( 'x-small', 'ystandard' ),
				'size'      => 12,
				'slug'      => 'x-small',
			],
			[
				'name'      => __( '小', 'ystandard' ),
				'shortName' => __( 'small', 'ystandard' ),
				'size'      => 14,
				'slug'      => 'small',
			],
			[
				'name'      => __( '標準', 'ystandard' ),
				'shortName' => __( 'normal', 'ystandard' ),
				'size'      => 16,
				'slug'      => 'normal',
			],
			[
				'name'      => __( '中', 'ystandard' ),
				'shortName' => __( 'medium', 'ystandard' ),
				'size'      => 18,
				'slug'      => 'medium',
			],
			[
				'name'      => __( '大', 'ystandard' ),
				'shortName' => __( 'large', 'ystandard' ),
				'size'      => 20,
				'slug'      => 'large',
			],
			[
				'name'      => __( '極大', 'ystandard' ),
				'shortName' => __( 'x-large', 'ystandard' ),
				'size'      => 22,
				'slug'      => 'x-large',
			],
			[
				'name'      => __( '巨大', 'ystandard' ),
				'shortName' => __( 'xx-large', 'ystandard' ),
				'size'      => 26,
				'slug'      => 'xx-large',
			],
		];

		return apply_filters( 'ys_editor_font_sizes', $size );
	}

	/**
	 * ブロックエディターフォントサイズ指定CSS
	 *
	 * @param string $prefix プレフィックス.
	 *
	 * @return string
	 */
	public static function get_editor_font_size_css( $prefix = '' ) {
		$size   = self::get_editor_font_sizes();
		$css    = '';
		$prefix = empty( $prefix ) ? '' : $prefix . ' ';
		foreach ( $size as $value ) {
			$css .= "${prefix}.has-{$value['slug']}-font-size{font-size:{$value['size']}px;}";
		}

		return $css;
	}

}

new Font();
