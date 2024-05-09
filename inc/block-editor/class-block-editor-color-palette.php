<?php
/**
 * ブロックエディター カラーパレット
 *
 * @package ystandard
 * @author  yosiakatsuki
 * @license GPL-2.0+
 */

namespace ystandard;

use ystandard\helper\Style_Sheet;
use ystandard\utils\CSS;

defined( 'ABSPATH' ) || die();

/**
 * Class Block_Editor_Color_Pallet
 *
 * @package ystandard
 */
class Block_Editor_Color_Palette {

	/**
	 * Block_Editor_Color_Pallet constructor.
	 */
	public function __construct() {
		add_action( 'after_setup_theme', [ $this, 'add_theme_support' ] );
		add_filter(
			Enqueue_Utility::FILTER_BLOCKS_INLINE_CSS,
			[ $this, 'enqueue_color_palette_css' ]
		);
		add_filter(
			Block_Editor_Assets::BLOCK_EDITOR_ASSETS_HOOK,
			[ $this, 'enqueue_block_editor_color_palette_css' ]
		);
		add_action( 'customize_register', [ $this, 'customize_register' ] );
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
	 * フロント用カラーパレットCSS
	 *
	 * @param string $css CSS.
	 *
	 * @return string
	 */
	public function enqueue_color_palette_css( $css ) {
		add_filter( 'ys_is_enqueue_color_pallet', '__return_true' );

		return $css . self::get_color_palette_css( '.ystd' );
	}

	/**
	 * 編集画面用カラーパレットCSS
	 *
	 * @param string $css CSS.
	 *
	 * @return string
	 */
	public function enqueue_block_editor_color_palette_css( $css ) {
		add_filter( 'ys_is_enqueue_block_editor_color_pallet', '__return_true' );

		return $css . self::get_color_palette_css( '.editor-styles-wrapper' );
	}

	/**
	 * カラーパレット用CSS取得
	 *
	 * @param string $prefix Prefix.
	 *
	 * @return string
	 */
	public static function get_color_palette_css( $prefix ) {
		$palette = get_theme_support( 'editor-color-palette' );
		if ( empty( $palette ) ) {
			return '';
		}
		$types = self::get_color_palette_css_types();
		$css   = [];
		foreach ( $palette[0] as $color ) {
			foreach ( $types as $value ) {
				$css[] = self::create_color_palette_css(
					$color['slug'],
					$color['color'],
					[
						'palette'     => $value['palette'],
						'conditional' => $value['conditional'],
						'property'    => $value['property'],
						'state'       => '',
					],
					$prefix
				);

				if ( $value['state'] ) {
					foreach ( $value['state'] as $state ) {
						$css[] = self::create_color_palette_css(
							$color['slug'],
							$color['color'],
							[
								'palette'     => $value['palette'],
								'conditional' => $value['conditional'],
								'property'    => $value['property'],
								'state'       => $state,
							],
							$prefix
						);
					}
				}
			}
		}

		return CSS::minify( implode( ' ', $css ) );
	}

	/**
	 * カラーパレット用CSS作成
	 *
	 * @param string $slug Slug.
	 * @param string $color Color.
	 * @param array $args Args.
	 * @param string $prefix CSS Prefix.
	 *
	 * @return string
	 */
	public static function create_color_palette_css( $slug, $color, $args, $prefix = '' ) {
		$color_name  = $slug;
		$color_class = "has-{$color_name}-" . $args['palette'];
		$property    = $args['property'];
		$state       = isset( $args['state'] ) && ! empty( $args['state'] ) ? ':' . $args['state'] : '';

		// 色指定.
		$color_class_section = "{$prefix} .{$color_class}{$state}";
		// 条件付き.
		if ( is_array( $args['conditional'] ) ) {
			foreach ( $args['conditional'] as $conditional ) {
				$conditional_class           = 'has-' . $conditional;
				$conditional_class_section[] = "{$prefix} .{$conditional_class}.{$color_class}{$state}";
			}
			$conditional_class_section = implode( ',', $conditional_class_section );
		} else {
			$conditional_class         = 'has-' . $args['conditional'];
			$conditional_class_section = "{$prefix} .{$conditional_class}.{$color_class}{$state}";
		}

		return "
			{$color_class_section},
			{$conditional_class_section}{
				{$property}:{$color};
			}
		";
	}

	/**
	 * カラーパレット用CSSを出力するタイプ
	 *
	 * @return array[]
	 */
	public static function get_color_palette_css_types() {
		return apply_filters(
			'ys_get_color_palette_css_types',
			[
				'color'            => [
					'property'    => 'color',
					'conditional' => [ 'text-color', 'inline-color' ],
					'palette'     => 'color',
					'state'       => [ 'hover' ],
				],
				'background-color' => [
					'property'    => 'background-color',
					'conditional' => 'background',
					'palette'     => 'background-color',
					'state'       => false,
				],
				'border-color'     => [
					'property'    => 'border-color',
					'conditional' => 'border',
					'palette'     => 'border-color',
					'state'       => false,
				],
				'fill'             => [
					'property'    => 'fill',
					'conditional' => 'fill-color',
					'palette'     => 'fill',
					'state'       => false,
				],
			]
		);
	}

	/**
	 * theme.jsonのカラーパレット取得
	 *
	 * @return array
	 */
	public static function get_color_palette() {
		$color_palette = self::get_color_palette_from_theme_json();

		return apply_filters( 'ys_editor_color_palette', $color_palette );

	}

	/**
	 * TODO:カスタマイザーの色定義をteheme.jsonの情報にマージする
	 * wp_theme_json_data_themeを使う
	 *
	 * @param bool $all ユーザー定義追加.
	 *
	 * @return array
	 */
	public static function get_color_palette_temp( $all = true ) {
		$color_palette = [];
		$theme_palette = self::get_color_palette_from_theme_json();

		foreach ( $theme_palette as $default ) {
			// カスタマイザーでの変更内容をマージ
			$color           = Option::get_option(
				"ys-color-palette-{$default['slug']}",
				$default['default']
			);
			$color_palette[] = array_merge(
				$default,
				[ 'color' => $color ]
			);
		}
		/**
		 * ユーザー定義情報の追加
		 */
		for ( $i = 1; $i <= 3; $i ++ ) {
			$option_name    = 'ys-color-palette-ys-user-' . $i;
			$option_value   = Option::get_option( $option_name, '#ffffff' );
			$option_default = Option::get_default( $option_name, '#ffffff' );
			if ( $all || $option_value !== $option_default ) {
				$name = sprintf(
				/* translators: %s: User Setting No. */
					_x( 'User Color %s', 'color-palette', 'ystandard' ),
					$i
				);
				// 追加.
				$color_palette[] = [
					'name'        => $name,
					'slug'        => 'ys-user-' . $i,
					'color'       => Option::get_option( $option_name, '#ffffff' ),
					'default'     => '#ffffff',
					'description' => _x( 'よく使う色を設定しておくと便利です。', 'color-palette', 'ystandard' ),
				];
			}
		}

		return apply_filters( 'ys_editor_color_palette', $color_palette );

	}

	/**
	 * theme.jsonからカラーパレット取得
	 *
	 * @return array
	 */
	public static function get_color_palette_from_theme_json() {
		$theme_json    = \WP_Theme_JSON_Resolver::get_merged_data();
		$settings      = $theme_json->get_settings();
		$color_palette = [];
		// defaultとthemeに分かれているのでマージ.
		if ( ! empty( $settings['color']['palette'] ) ) {
			foreach ( $settings['color']['palette'] as $colors ) {
				$color_palette = array_merge( $color_palette, $colors );
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
		$customizer->add_section(
			[
				'section'     => 'ys_color_palette',
				'title'       => 'カラーパレット',
				'description' => 'ブロックで使用できる文字色・背景色の設定を変更できます。' . Admin::manual_link( 'manual/block-editor-color' ),
				'priority'    => 10,
				'panel'       => Block_Editor::PANEL_NAME,
			]
		);
		do_action( 'ys_customizer_color_palette', $wp_customize, $customizer );
		if ( apply_filters( 'ys_customizer_custom_color_palette', false ) ) {
			return;
		}
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

new Block_Editor_Color_Palette();
