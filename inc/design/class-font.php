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
				'choices'     => self::get_font_choices(),
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
			array_merge(
				self::get_font_library_fonts(),
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
			)
		);
	}

	/**
	 * カスタマイザーに表示するフォント選択肢を取得
	 *
	 * @return array
	 */
	private static function get_font_choices() {
		$choices = [];
		$fonts   = self::get_usable_fonts();
		foreach ( $fonts as $key => $font ) {
			if ( empty( $font['label'] ) ) {
				continue;
			}
			$choices[ $key ] = $font['label'];
		}

		return $choices;
	}

	/**
	 * Font Libraryで選択可能なフォントを取得
	 *
	 * @return array
	 */
	private static function get_font_library_fonts() {
		if ( ! function_exists( 'wp_get_global_settings' ) ) {
			return [];
		}

		$font_families = wp_get_global_settings( [ 'typography', 'fontFamilies' ] );
		if ( empty( $font_families ) || ! is_array( $font_families ) ) {
			return [];
		}

		$result = [];
		foreach ( self::normalize_font_families( $font_families ) as $font_family ) {
			if (
				! is_array( $font_family ) ||
				empty( $font_family['slug'] ) ||
				empty( $font_family['fontFamily'] )
			) {
				continue;
			}

			$key = 'font-library-' . sanitize_key( $font_family['slug'] );
			if ( 'font-library-' === $key || isset( $result[ $key ] ) ) {
				continue;
			}

			$result[ $key ] = [
				'family' => sanitize_text_field( $font_family['fontFamily'] ),
				'label'  => self::get_font_library_font_label( $font_family ),
			];
		}

		return $result;
	}

	/**
	 * Font Libraryのフォント一覧を1次元配列に整形
	 *
	 * @param array $font_families フォント一覧.
	 *
	 * @return array
	 */
	private static function normalize_font_families( $font_families ) {
		if ( isset( $font_families['slug'] ) ) {
			return [ $font_families ];
		}

		$result = [];
		foreach ( [ 'custom', 'theme', 'default' ] as $origin ) {
			if ( empty( $font_families[ $origin ] ) || ! is_array( $font_families[ $origin ] ) ) {
				continue;
			}
			$result = array_merge( $result, $font_families[ $origin ] );
		}

		if ( empty( $result ) && isset( $font_families[0] ) ) {
			$result = $font_families;
		}

		return $result;
	}

	/**
	 * Font Libraryのフォント名を取得
	 *
	 * @param array $font_family フォント情報.
	 *
	 * @return string
	 */
	private static function get_font_library_font_label( $font_family ) {
		if ( ! empty( $font_family['name'] ) ) {
			return sanitize_text_field( $font_family['name'] );
		}

		return sanitize_text_field( $font_family['slug'] );
	}
}

new Font();
