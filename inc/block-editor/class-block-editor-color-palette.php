<?php
/**
 * ブロックエディター カラーパレット
 *
 * @package ystandard
 * @author  yosiakatsuki
 * @license GPL-2.0+
 */

namespace ystandard;

defined( 'ABSPATH' ) || die();

/**
 * Class Block_Editor_Color_Pallet
 *
 * @package ystandard
 */
class Block_Editor_Color_Palette {

	/**
	 * ユーザー定義色の上限.
	 */
	const USER_COLOR_LIMIT = 6;

	/**
	 * Block_Editor_Color_Pallet constructor.
	 */
	public function __construct() {
		add_filter( 'wp_theme_json_data_user', [ $this, 'add_user_color_palette_to_theme_json' ] );
		add_action( 'customize_register', [ $this, 'customize_register' ] );
	}

	/**
	 * ユーザー定義色をTheme.jsonのユーザー設定に追加.
	 *
	 * @param \WP_Theme_JSON_Data $theme_json Theme.jsonデータ.
	 *
	 * @return \WP_Theme_JSON_Data
	 */
	public function add_user_color_palette_to_theme_json( $theme_json ) {
		$data             = $theme_json->get_data();
		$existing_palette = $data['settings']['color']['palette']['custom'] ?? [];
		$user_slugs       = [];

		for ( $i = 1; $i <= self::USER_COLOR_LIMIT; $i ++ ) {
			$user_slugs[] = 'ys-user-' . $i;
		}

		$filtered_palette = array_values(
			array_filter(
				$existing_palette,
				function ( $color ) use ( $user_slugs ) {
					return empty( $color['slug'] ) || ! in_array( $color['slug'], $user_slugs, true );
				}
			)
		);
		$user_palette     = array_map(
			function ( $color ) {
				return [
					'name'  => $color['name'],
					'slug'  => $color['slug'],
					'color' => $color['color'],
				];
			},
			self::get_user_color_palette( false )
		);

		if ( $existing_palette === $filtered_palette && empty( $user_palette ) ) {
			return $theme_json;
		}

		return $theme_json->update_with(
			[
				'version'  => 3,
				'settings' => [
					'color' => [
						'palette' => array_merge( $filtered_palette, $user_palette ),
					],
				],
			]
		);
	}

	/**
	 * ユーザー定義色を取得
	 *
	 * @param bool $all 未設定の色を含めるか.
	 *
	 * @return array
	 */
	public static function get_user_color_palette( $all = true ) {
		$color_palette = [];

		for ( $i = 1; $i <= self::USER_COLOR_LIMIT; $i ++ ) {
			$option_name    = 'ys-color-palette-ys-user-' . $i;
			$option_value   = Option::get_option( $option_name, '' );
			$option_default = Option::get_default( $option_name, '' );
			if ( $all || $option_value !== $option_default ) {
				$name = sprintf(
					/* translators: %s: User Setting No. */
					_x( '色設定 %s', 'color-palette', 'ystandard' ),
					$i
				);
				$color_palette[] = [
					'name'    => $name,
					'slug'    => 'ys-user-' . $i,
					'color'   => $option_value,
					'default' => '',
				];
			}
		}

		return $color_palette;
	}

	/**
	 * カスタマイザー追加
	 *
	 * @param \WP_Customize_Manager $wp_customize カスタマイザー.
	 */
	public function customize_register( $wp_customize ) {
		$customizer = new Customize_Control( $wp_customize );
		$customizer->add_section_label(
			esc_html__( '色定義', 'ystandard' ),
			[
				'id'          => 'ys_color_palette_section_label',
				'section'     => Block_Editor::SECTION_NAME,
				'description' => esc_html__( 'ブロックエディターのカラーパレットに追加する色を設定できます。', 'ystandard' ),
			]
		);

		foreach ( self::get_user_color_palette() as $item ) {
			$customizer->add_color(
				[
					'id'        => 'ys-color-palette-' . $item['slug'],
					'section'   => Block_Editor::SECTION_NAME,
					'default'   => $item['default'],
					'label'     => $item['name'],
					'transport' => 'postMessage',
				]
			);
		}
	}

}

new Block_Editor_Color_Palette();
