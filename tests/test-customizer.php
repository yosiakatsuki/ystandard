<?php
/**
 * Class CustomizerTest
 *
 * @package ystandard
 */

/**
 * Class CustomizerTest
 */
class CustomizerTest extends WP_UnitTestCase {

	/**
	 * テスト終了処理.
	 */
	public function tear_down() {
		for ( $i = 1; $i <= \ystandard\Block_Editor_Color_Palette::USER_COLOR_LIMIT; $i ++ ) {
			delete_option( 'ys-color-palette-ys-user-' . $i );
		}
		WP_Theme_JSON_Resolver::clean_cached_data();
		parent::tear_down();
	}

	/**
	 * Test: get_priority
	 */
	function test_get_priority() {
		$priority = \ystandard\Customizer::get_priority( 'ys_seo' );
		$this->assertSame( $priority, 1110 );

		$priority = \ystandard\Customizer::get_priority( 'ys_none' );
		$this->assertSame( $priority, 1000 );
	}

	/**
	 * ブロックエディター設定がフォント・文字色の直後に並ぶことを確認.
	 */
	public function test_block_editor_priority_is_after_typography() {
		$this->assertSame( 1101, \ystandard\Customizer::get_priority( 'ys_block_editor' ) );
		$this->assertGreaterThan(
			\ystandard\Customizer::get_priority( 'ys_site_typography' ),
			\ystandard\Customizer::get_priority( 'ys_block_editor' )
		);
		$this->assertLessThan(
			\ystandard\Customizer::get_priority( 'ys_site_background' ),
			\ystandard\Customizer::get_priority( 'ys_block_editor' )
		);
	}

	/**
	 * サイト背景がトップレベルに移動し、他のデザイン設定がパネル内に残ることを確認.
	 */
	public function test_site_background_is_top_level_section() {
		$wp_customize = new WP_Customize_Manager();

		$wp_customize->register_controls();
		$customizer_classes = [
			\ystandard\Design::class,
			\ystandard\Mobile::class,
			\ystandard\Toc::class,
			\ystandard\Site_Background::class,
		];
		foreach ( $customizer_classes as $class_name ) {
			$customizer = ( new ReflectionClass( $class_name ) )->newInstanceWithoutConstructor();
			$customizer->customize_register( $wp_customize );
		}

		$site_background = $wp_customize->get_section( 'ys_site_background' );
		$this->assertInstanceOf( WP_Customize_Section::class, $site_background );
		$this->assertSame( '[ys]サイト背景', $site_background->title );
		$this->assertSame( '', $site_background->panel );
		$this->assertSame( \ystandard\Customizer::get_priority( 'ys_site_background' ), $site_background->priority );
		$this->assertSame( 'ys_site_background', $wp_customize->get_control( 'ys_color_site_bg' )->section );

		foreach ( [ 'background_image', 'background_preset', 'background_size', 'background_repeat', 'background_attachment', 'background_position' ] as $control_id ) {
			$this->assertSame( 'ys_site_background', $wp_customize->get_control( $control_id )->section );
		}

		$this->assertInstanceOf( WP_Customize_Panel::class, $wp_customize->get_panel( 'ys_design' ) );
		$this->assertSame( 'ys_design', $wp_customize->get_section( 'ys_mobile_design' )->panel );
		$this->assertSame( 'ys_design', $wp_customize->get_section( 'ys_design_toc' )->panel );
	}

	/**
	 * ユーザー定義色を6件登録することを確認.
	 */
	public function test_color_palette_settings_are_registered() {
		$wp_customize = new WP_Customize_Manager();
		if ( ! class_exists( \ystandard\Section_Label_Control::class ) ) {
			require get_template_directory() . '/inc/customizer/class-section-label-control.php';
		}
		if ( ! class_exists( \ystandard\Color_Palette_Control::class ) ) {
			require get_template_directory() . '/inc/customizer/class-color-palette-control.php';
		}
		$block_editor = ( new ReflectionClass( \ystandard\Block_Editor::class ) )->newInstanceWithoutConstructor();
		$color_palette = ( new ReflectionClass( \ystandard\Block_Editor_Color_Palette::class ) )->newInstanceWithoutConstructor();

		$block_editor->customize_register( $wp_customize );
		$color_palette->customize_register( $wp_customize );

		$this->assertNull( $wp_customize->get_panel( 'ys_block_editor' ) );
		$this->assertSame( '[ys]ブロックエディター', $wp_customize->get_section( 'ys_block_editor' )->title );
		$section_label = $wp_customize->get_control( 'ys_color_palette_section_label' );
		$this->assertInstanceOf( \ystandard\Section_Label_Control::class, $section_label );
		$this->assertSame( '色定義', $section_label->label );
		$this->assertSame( 'ys_block_editor', $section_label->section );
		for ( $i = 1; $i <= \ystandard\Block_Editor_Color_Palette::USER_COLOR_LIMIT; $i ++ ) {
			$setting_id = 'ys-color-palette-ys-user-' . $i;
			$this->assertInstanceOf( WP_Customize_Setting::class, $wp_customize->get_setting( $setting_id ) );
			$this->assertSame( '', $wp_customize->get_setting( $setting_id )->default );
			$this->assertInstanceOf( WP_Customize_Control::class, $wp_customize->get_control( $setting_id ) );
			$this->assertSame( 'ys_block_editor', $wp_customize->get_control( $setting_id )->section );
			$this->assertSame( '色設定 ' . $i, $wp_customize->get_control( $setting_id )->label );
			$this->assertSame( '', $wp_customize->get_control( $setting_id )->description );
		}
		$this->assertNull( $wp_customize->get_setting( 'ys-color-palette-ys-blue' ) );
	}

	/**
	 * v4の保存値を引き継ぎ、ユーザー定義色を6件まで扱えることを確認.
	 */
	public function test_user_color_palette_inherits_v4_options_and_supports_six_colors() {
		update_option( 'ys-color-palette-ys-user-1', '#123456' );
		update_option( 'ys-color-palette-ys-user-2', '#ffffff' );
		update_option( 'ys-color-palette-ys-user-3', '#abcdef' );
		update_option( 'ys-color-palette-ys-user-6', '#654321' );

		$palette = \ystandard\Block_Editor_Color_Palette::get_user_color_palette( false );

		$this->assertSame( [ 'ys-user-1', 'ys-user-2', 'ys-user-3', 'ys-user-6' ], array_column( $palette, 'slug' ) );
		$this->assertSame( [ '#123456', '#ffffff', '#abcdef', '#654321' ], array_column( $palette, 'color' ) );
		$this->assertCount(
			\ystandard\Block_Editor_Color_Palette::USER_COLOR_LIMIT,
			\ystandard\Block_Editor_Color_Palette::get_user_color_palette()
		);

		WP_Theme_JSON_Resolver::clean_cached_data();
		$editor_palette = array_column(
			wp_get_global_settings( [ 'color', 'palette', 'custom' ] ),
			'color',
			'slug'
		);
		$this->assertSame( '#123456', $editor_palette['ys-user-1'] );
		$this->assertSame( '#ffffff', $editor_palette['ys-user-2'] );
		$this->assertSame( '#abcdef', $editor_palette['ys-user-3'] );
		$this->assertSame( '#654321', $editor_palette['ys-user-6'] );
	}

	/**
	 * ユーザー定義色のプリセットCSSをGlobal Stylesが生成することを確認.
	 */
	public function test_global_styles_generates_user_color_palette_css() {
		update_option( 'ys-color-palette-ys-user-1', '#07689f80' );
		WP_Theme_JSON_Resolver::clean_cached_data();

		$stylesheet = wp_get_global_stylesheet( [ 'variables', 'presets' ] );

		$this->assertStringContainsString( '--wp--preset--color--ys-user-1', $stylesheet );
		$this->assertStringContainsString( '#07689f80', $stylesheet );
		$this->assertStringContainsString( '.has-ys-user-1-color', $stylesheet );
		$this->assertStringContainsString( '.has-ys-user-1-background-color', $stylesheet );
		$this->assertStringContainsString( '.has-ys-user-1-border-color', $stylesheet );
	}

	/**
	 * カスタマイザーがGlobal Settingsのカラーパレットを使用することを確認.
	 */
	public function test_customizer_uses_global_settings_color_palette() {
		update_option( 'ys-color-palette-ys-user-1', '#07689f' );
		update_option( 'ys-color-palette-ys-user-2', '#f2b3b8' );
		WP_Theme_JSON_Resolver::clean_cached_data();

		$wp_customize = new WP_Customize_Manager();
		if ( ! class_exists( \ystandard\Section_Label_Control::class ) ) {
			require get_template_directory() . '/inc/customizer/class-section-label-control.php';
		}
		if ( ! class_exists( \ystandard\Color_Palette_Control::class ) ) {
			require get_template_directory() . '/inc/customizer/class-color-palette-control.php';
		}
		$block_editor = ( new ReflectionClass( \ystandard\Block_Editor::class ) )->newInstanceWithoutConstructor();
		$color_palette = ( new ReflectionClass( \ystandard\Block_Editor_Color_Palette::class ) )->newInstanceWithoutConstructor();

		$block_editor->customize_register( $wp_customize );
		$color_palette->customize_register( $wp_customize );

		$control = $wp_customize->get_control( 'ys-color-palette-ys-user-1' );
		$this->assertInstanceOf( \ystandard\Color_Palette_Control::class, $control );
		$this->assertTrue( $control->enable_alpha );
		$palette = array_column( $control->palette, null, 'slug' );
		$this->assertSame( '#07689f', $palette['ys-user-1']['color'] );
		$this->assertSame( '色設定 1', $palette['ys-user-1']['name'] );
		$this->assertSame( '#f2b3b8', $palette['ys-user-2']['color'] );
		$this->assertSame( '#ceecfd', $palette['ys-light-blue']['color'] );
		$this->assertArrayNotHasKey( 'black', $palette );
		$control->to_json();
		$this->assertTrue( $control->json['enableAlpha'] );
		$this->assertSame( $control->palette, $control->json['palette'] );
		$control->enqueue();
		$script = wp_scripts()->registered['customizer-control-ys-color-palette-control'];
		$this->assertSame( [ 'customize-controls', 'wp-components', 'wp-element' ], $script->deps );
	}

	/**
	 * カラー設定でアルファ値を含むHEXを保存できることを確認.
	 *
	 * @dataProvider color_sanitize_provider
	 *
	 * @param mixed       $input    入力値.
	 * @param string|null $expected 期待値.
	 */
	public function test_color_sanitize( $input, $expected ) {
		$this->assertSame( $expected, \ystandard\Customize_Control::sanitize_color( $input ) );
	}

	/**
	 * カラー設定のサニタイズ用データ.
	 *
	 * @return array
	 */
	public function color_sanitize_provider() {
		return [
			'empty'       => [ '', '' ],
			'hex3'        => [ '#abc', '#abc' ],
			'hex4'        => [ '#abcd', '#abcd' ],
			'hex6'        => [ '#123456', '#123456' ],
			'hex8'        => [ '#12345678', '#12345678' ],
			'uppercase'   => [ '#ABCDEF80', '#ABCDEF80' ],
			'no-hash'     => [ '123456', null ],
			'invalid-hex' => [ '#gggggg', null ],
			'invalid-len' => [ '#12345', null ],
			'not-string'  => [ 123456, null ],
		];
	}

	/**
	 * ユーザー定義色をTheme.jsonのユーザー設定に追加することを確認.
	 */
	public function test_user_color_palette_is_added_to_theme_json_user_data() {
		update_option( 'ys-color-palette-ys-user-1', '#07689f' );
		update_option( 'ys-color-palette-ys-user-2', '#f2b3b8' );

		$theme_json = new WP_Theme_JSON_Data(
			[
				'version'  => 3,
				'settings' => [
					'color' => [
						'palette' => [
							[
								'name'  => '既存色',
								'slug'  => 'existing-color',
								'color' => '#abcdef',
							],
							[
								'name'  => '古い色設定',
								'slug'  => 'ys-user-1',
								'color' => '#000000',
							],
						],
					],
				],
			],
			'custom'
		);
		$color_palette = ( new ReflectionClass( \ystandard\Block_Editor_Color_Palette::class ) )->newInstanceWithoutConstructor();
		$data          = $color_palette->add_user_color_palette_to_theme_json( $theme_json )->get_data();
		$palette       = array_column( $data['settings']['color']['palette']['custom'], null, 'slug' );

		$this->assertSame( '#abcdef', $palette['existing-color']['color'] );
		$this->assertSame( '#07689f', $palette['ys-user-1']['color'] );
		$this->assertSame( '#f2b3b8', $palette['ys-user-2']['color'] );
		$this->assertSame( '色設定 1', $palette['ys-user-1']['name'] );
		$this->assertArrayNotHasKey( 'default', $palette['ys-user-1'] );
		$this->assertCount( 3, $palette );
	}

}
