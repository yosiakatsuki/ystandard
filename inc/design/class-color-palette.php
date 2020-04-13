<?php
/**
 * ブロックエディターカラーパレット関連クラス
 *
 * @package ystandard
 * @author  yosiakatsuki
 * @license GPL-2.0+
 */

namespace ystandard;

/**
 * Class Color_Palette
 * @package ystandard
 */
class Color_Palette {

	/**
	 * Color_Palette constructor.
	 */
	public function __construct() {
		add_action( 'after_setup_theme', [ $this, 'add_theme_support' ] );
		add_filter( Enqueue_Utility::FILTER_INLINE_CSS, [ $this, 'add_color_palette_css' ] );
		add_filter( Admin::BLOCK_EDITOR_ASSETS_HOOK, [ $this, 'add_color_palette_css_block' ] );

		add_action( 'customize_register', [ $this, 'customize_register' ] );
	}

	/**
	 * カラーパレット用CSS追加
	 *
	 * @param string $css CSS.
	 *
	 * @return string
	 */
	public function add_color_palette_css( $css ) {
		return $css . self::get_editor_color_palette_css();
	}

	/**
	 * 編集画面用カラーパレットCSS追加
	 *
	 * @param string $css CSS.
	 *
	 * @return string
	 */
	public function add_color_palette_css_block( $css ) {
		return $css . self::get_editor_color_palette_css( '.editor-styles-wrapper' );
	}

	/**
	 * Add Theme Support
	 */
	public function add_theme_support() {
		/**
		 * ブロックエディター色設定
		 */
		add_theme_support( 'editor-color-palette', self::get_color_palette( false ) );
	}

	/**
	 * ブロックエディター色設定
	 *
	 * @param string $prefix プレフィックス.
	 *
	 * @return string
	 */
	public static function get_editor_color_palette_css( $prefix = '' ) {
		$color  = self::get_color_palette( false );
		$css    = '';
		$prefix = empty( $prefix ) ? '' : $prefix . ' ';
		foreach ( $color as $value ) {
			// Background-color.
			$css .= "
			${prefix}.has-{$value['slug']}-background-color,
			${prefix}.has-background.has-{$value['slug']}-background-color,
			${prefix}.has-background.has-{$value['slug']}-background-color:hover {
				background-color:{$value['color']};
			}";
			/**
			 * Text Color
			 */
			$css .= "
			${prefix}.has-{$value['slug']}-color,
			${prefix}.has-{$value['slug']}-color:hover,
			${prefix}.has-text-color.has-{$value['slug']}-color,
			${prefix}.has-text-color.has-{$value['slug']}-color:hover,
			${prefix}.has-text-color.has-{$value['slug']}-color:visited,
			${prefix}.has-text-color.has-{$value['slug']}-color:focus,
			${prefix}.has-inline-color.has-{$value['slug']}-color {
				color:{$value['color']};
			}";
		}

		return $css;
	}


	/**
	 * 色設定の定義
	 *
	 * @param bool $all ユーザー定義追加.
	 *
	 * @return array
	 */
	public static function get_color_palette( $all = true ) {
		$color = [
			[
				'name'        => '青',
				'slug'        => 'ys-blue',
				'color'       => Option::get_option( 'ys-color-palette-ys-blue', '#82B9E3' ),
				'default'     => '#82B9E3',
				'description' => '',
			],
			[
				'name'        => '赤',
				'slug'        => 'ys-red',
				'color'       => Option::get_option( 'ys-color-palette-ys-red', '#D53939' ),
				'default'     => '#D53939',
				'description' => '',
			],
			[
				'name'        => '緑',
				'slug'        => 'ys-green',
				'color'       => Option::get_option( 'ys-color-palette-ys-green', '#92C892' ),
				'default'     => '#92C892',
				'description' => '',
			],
			[
				'name'        => '黄',
				'slug'        => 'ys-yellow',
				'color'       => Option::get_option( 'ys-color-palette-ys-yellow', '#F5EC84' ),
				'default'     => '#F5EC84',
				'description' => '',
			],
			[
				'name'        => 'オレンジ',
				'slug'        => 'ys-orange',
				'color'       => Option::get_option( 'ys-color-palette-ys-orange', '#EB962D' ),
				'default'     => '#EB962D',
				'description' => '',
			],
			[
				'name'        => '紫',
				'slug'        => 'ys-purple',
				'color'       => Option::get_option( 'ys-color-palette-ys-purple', '#B67AC2' ),
				'default'     => '#B67AC2',
				'description' => '',
			],
			[
				'name'        => '灰色',
				'slug'        => 'ys-gray',
				'color'       => Option::get_option( 'ys-color-palette-ys-gray', '#757575' ),
				'default'     => '#757575',
				'description' => '',
			],
			[
				'name'        => '薄灰色',
				'slug'        => 'ys-light-gray',
				'color'       => Option::get_option( 'ys-color-palette-ys-light-gray', '#F1F1F3' ),
				'default'     => '#F1F1F3',
				'description' => '',
			],
			[
				'name'        => '黒',
				'slug'        => 'ys-black',
				'color'       => Option::get_option( 'ys-color-palette-ys-black', '#000000' ),
				'default'     => '#000000',
				'description' => '',
			],
			[
				'name'        => '白',
				'slug'        => 'ys-white',
				'color'       => Option::get_option( 'ys-color-palette-ys-white', '#ffffff' ),
				'default'     => '#ffffff',
				'description' => '',
			],
		];

		/**
		 * ユーザー定義情報の追加
		 */
		for ( $i = 1; $i <= 3; $i ++ ) {
			$option_name = 'ys-color-palette-ys-user-' . $i;
			if ( $all || Option::get_option( $option_name, '#ffffff' ) !== Option::get_default( $option_name, '#ffffff' ) ) {
				$color[] = [
					'name'        => 'ユーザー定義' . $i,
					'slug'        => 'ys-user-' . $i,
					'color'       => Option::get_option( $option_name, '#ffffff' ),
					'default'     => '#ffffff',
					'description' => 'よく使う色を設定しておくと便利です。',
				];
			}
		}

		return apply_filters( 'ys_editor_color_palette', $color );
	}

	/**
	 * カスタマイザー追加
	 *
	 * @param \WP_Customize_Manager $wp_customize カスタマイザー.
	 */
	public function customize_register( $wp_customize ) {
		$customizer = new Customize_Control( $wp_customize );
		$customizer->add_section(
			[
				'section'     => 'ys_color_palette',
				'title'       => 'カラーパレット（ブロック）',
				'description' => 'ブロックで使用できる文字色・背景色の設定を変更できます。',
				'priority'    => 2000,
				'panel'       => Design::PANEL_NAME,
			]
		);
		/**
		 * カラーパレット設定の追加
		 */
		$list = self::get_color_palette();
		foreach ( $list as $item ) {
			if ( isset( $item['name'] ) && isset( $item['slug'] ) && isset( $item['color'] ) ) {
				$dscr    = '';
				$default = '#ffffff';
				// 説明文.
				if ( isset( $item['description'] ) ) {
					$dscr = $item['description'];
				}
				// 初期値.
				if ( isset( $item['default'] ) ) {
					$default = $item['default'];
				}
				// 設定追加.
				$customizer->add_color(
					[
						'id'          => 'ys-color-palette-' . $item['slug'],
						'default'     => $default,
						'label'       => $item['name'],
						'description' => $dscr,
						'transport'   => 'postMessage',
					]
				);
			}
		}

	}
}

new Color_Palette();
