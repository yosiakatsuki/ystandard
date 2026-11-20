<?php
/**
 * ボタンスタイルの設定のテスト.
 *
 * @package ystandard
 */

use ystandard\Button_Style;
use ystandard\Customizer;

/**
 * ボタンスタイルの登録とCSS変数への変換を確認する.
 */
class Button_Style_Test extends WP_UnitTestCase {
	/**
	 * すべての設定がトップレベルのセクションに登録される.
	 */
	public function test_button_controls_are_registered() {
		$wp_customize = new WP_Customize_Manager();
		$wp_customize->register_controls();
		// テスト環境では、コアのコントロールクラスが読み込まれてから登録する.
		if ( ! class_exists( \ystandard\Color_Palette_Control::class ) ) {
			require get_template_directory() . '/inc/customizer/class-color-palette-control.php';
		}
		// 見出しのクラスもコアのクラスが必要になる.
		if ( ! class_exists( \ystandard\Section_Label_Control::class ) ) {
			require get_template_directory() . '/inc/customizer/class-section-label-control.php';
		}

		$styles = static function ( $styles ) {
			$styles[] = [
				'slug'  => 'toolbox-ease',
				'label' => 'Toolbox',
				'value' => 'cubic-bezier(0.2, 0, 0, 1)',
			];
			return $styles;
		};
		add_filter( 'ys_button_animation_styles', $styles );
		try {
			$button_style = ( new ReflectionClass( Button_Style::class ) )->newInstanceWithoutConstructor();
			$button_style->customize_register( $wp_customize );
		} finally {
			remove_filter( 'ys_button_animation_styles', $styles );
		}

		$section = $wp_customize->get_section( Button_Style::SECTION_NAME );
		$this->assertSame( '', $section->panel );
		$this->assertSame( 1120, $section->priority );
		$this->assertGreaterThan( Customizer::get_priority( 'ys_block_editor' ), $section->priority );
		$this->assertLessThan( Customizer::get_priority( 'ys_site_background' ), $section->priority );

		$style_fields = [
			'font_size'        => 'text',
			'text_color'       => 'ys-color-palette-control',
			'background_color' => 'ys-color-palette-control',
			'padding_y'        => 'text',
			'padding_x'        => 'text',
			'border_width'     => 'text',
			'border_color'     => 'ys-color-palette-control',
			'border_radius'    => 'text',
			'min_width'        => 'text',
			'min_height'       => 'text',
		];

		// ホバー時は色の項目だけを設定できる.
		$hover_fields = array_intersect_key( $style_fields, array_flip( [ 'text_color', 'background_color', 'border_color' ] ) );
		$states       = [
			'normal' => $style_fields,
			'hover'  => $hover_fields,
		];

		$control_ids = array_keys( $wp_customize->controls() );
		foreach ( $states as $state => $fields ) {
			foreach ( $fields as $field => $type ) {
				$id      = "ys_button_{$state}_{$field}";
				$setting = $wp_customize->get_setting( $id );
				$this->assertSame( 'option', $setting->type );
				$this->assertSame( '', $setting->default );
				$this->assertSame( Button_Style::SECTION_NAME, $wp_customize->get_control( $id )->section );
				$this->assertSame( $type, $wp_customize->get_control( $id )->type );
				// 長さの欄では、入力時と保存後に同じ値を使う.
				if ( 'text' === $type ) {
					$this->assertSame( '12.5px', $setting->sanitize( '12.5' ) );
					$this->assertSame( '1.25em', $setting->sanitize( '1.25em' ) );
					$this->assertSame( '', $setting->sanitize( '' ) );
				}
			}
		}
		$this->assertSame(
			[ 'ys_button_normal_border_color', 'ys_button_normal_border_radius', 'ys_button_normal_min_width' ],
			array_slice( $control_ids, array_search( 'ys_button_normal_border_color', $control_ids, true ), 3 )
		);
		foreach ( [ 'font_size', 'padding_y', 'padding_x', 'border_width', 'border_radius', 'min_width', 'min_height' ] as $field ) {
			$this->assertNull( $wp_customize->get_control( "ys_button_hover_{$field}" ) );
		}

		$this->assertSame( 'text', $wp_customize->get_control( 'ys_button_animation_properties' )->type );
		$this->assertSame( 'number', $wp_customize->get_control( 'ys_button_animation_duration' )->type );
		$this->assertSame( 'Toolbox', $wp_customize->get_control( 'ys_button_animation_style' )->choices['toolbox-ease'] );
	}

	/**
	 * 有効な保存値がCSS変数として反映され、外した拡張は初期値に戻る.
	 */
	public function test_css_variables_use_saved_styles_and_easing() {
		update_option( 'ys_button_normal_padding_x', '2' );
		update_option( 'ys_button_normal_padding_y', '0' );
		update_option( 'ys_button_normal_min_width', '80' );
		update_option( 'ys_button_normal_min_height', 'calc(2rem + 8px)' );
		update_option( 'ys_button_normal_font_size', '1.25em' );
		update_option( 'ys_button_normal_border_radius', '8' );
		update_option( 'ys_button_normal_background_color', '#336699' );
		update_option( 'ys_button_hover_border_color', '#4488cc' );
		// ホバー時の大きさの設定は、保存値が残っていても出力しない.
		update_option( 'ys_button_hover_border_radius', '50%' );
		update_option( 'ys_button_animation_properties', 'font-size, box-shadow' );
		update_option( 'ys_button_animation_duration', '0' );
		update_option( 'ys_button_animation_style', 'custom-easing' );

		$styles = static function ( $styles ) {
			$styles[] = [
				'slug'  => 'custom-easing',
				'label' => 'Custom easing',
				'value' => 'cubic-bezier(0.2, 0, 0, 1)',
			];
			return $styles;
		};
		add_filter( 'ys_button_animation_styles', $styles );
		try {
			$css = \ystandard\Enqueue_Styles::get_css_custom_properties();
			$this->assertStringContainsString( '--ystd--button--spacing--padding--y: 0px;', $css );
			$this->assertStringContainsString( '--ystd--button--spacing--padding--x: 2px;', $css );
			$this->assertStringContainsString( '--ystd--button--dimensions--min-width: 80px;', $css );
			$this->assertStringContainsString( '--ystd--button--dimensions--min-height: calc(2rem + 8px);', $css );
			$this->assertStringContainsString( '--ystd--button--typography--font-size: 1.25em;', $css );
			$this->assertStringContainsString( '--ystd--button--border--radius: 8px;', $css );
			$this->assertStringContainsString( '--ystd--button--color--background: #336699;', $css );
			$this->assertStringContainsString( '--ystd--button--hover--border--color: #4488cc;', $css );
			// ホバー時の未設定項目は、CSS側で通常時の値から作るため出力しない.
			$this->assertStringNotContainsString( '--ystd--button--hover--color--background:', $css );
			$this->assertStringNotContainsString( '--ystd--button--hover--border--radius:', $css );
			$this->assertStringContainsString( '--ystd--button--transition--property: font-size, box-shadow;', $css );
			$this->assertStringContainsString( '--ystd--button--transition--duration: 0ms;', $css );
			$this->assertStringContainsString( '--ystd--button--transition--timing-function: cubic-bezier(0.2, 0, 0, 1);', $css );
		} finally {
			remove_filter( 'ys_button_animation_styles', $styles );
		}

		$this->assertStringNotContainsString( '--ystd--button--transition--timing-function:', \ystandard\Enqueue_Styles::get_css_custom_properties() );
	}

	/**
	 * theme.jsonのボタンのスタイルが、ボタン用のCSS変数を参照して出力される.
	 */
	public function test_theme_json_button_styles_use_css_variables() {
		$css = wp_get_global_stylesheet( [ 'styles' ] );
		$this->assertStringContainsString( 'background-color: var(--ystd--button--color--background);', $css );
		$this->assertStringContainsString( 'padding-top: var(--ystd--button--spacing--padding--y);', $css );
		$this->assertStringContainsString( 'padding-left: var(--ystd--button--spacing--padding--x);', $css );
		$this->assertStringContainsString( 'font-family: var(--ystd--button--typography--font-family);', $css );
		// ホバー時は、ホバー用の変数を参照する.
		$this->assertStringContainsString( 'background-color: var(--ystd--button--hover--color--background);', $css );
		$this->assertStringContainsString( 'padding-top: var(--ystd--button--hover--spacing--padding--y);', $css );
	}

	/**
	 * アウトラインのボタンで、コアの初期値ではなくテーマの変数が出力される.
	 */
	public function test_outline_variation_uses_css_variables() {
		do_blocks( '<!-- wp:button {"className":"is-style-outline"} --><div class="wp-block-button is-style-outline"><a class="wp-block-button__link wp-element-button">Test</a></div><!-- /wp:button -->' );
		$css = implode( '', (array) wp_styles()->get_data( 'block-style-variation-styles', 'after' ) );
		$this->assertStringContainsString( 'border-width: var(--ystd--button--border--width);', $css );
		$this->assertStringContainsString( 'border-color: var(--ystd--button--outline--border--color);', $css );
		$this->assertStringContainsString( 'color: var(--ystd--button--outline--color--text);', $css );
		$this->assertStringContainsString( 'padding-top: var(--ystd--button--spacing--padding--y);', $css );
		// コアの初期値（枠線2px、余白0.667em）が残っていない.
		$this->assertStringNotContainsString( '2px', $css );
		$this->assertStringNotContainsString( '0.667em', $css );
	}

	/**
	 * 保存値にCSS宣言や負数を含む入力を反映しない.
	 */
	public function test_invalid_values_are_ignored() {
		update_option( 'ys_button_normal_min_height', '-20' );
		update_option( 'ys_button_normal_background_color', 'red; color: black' );
		update_option( 'ys_button_animation_properties', 'opacity; color: red' );

		$button_style = ( new ReflectionClass( Button_Style::class ) )->newInstanceWithoutConstructor();
		$this->assertSame( [], $button_style->add_css_vars( [] ) );
		$this->assertSame( '', Button_Style::sanitize_transition_properties( 'none, opacity' ) );
		$this->assertSame( 'none', Button_Style::sanitize_transition_properties( 'none' ) );
	}
}
