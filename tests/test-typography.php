<?php
/**
 * Class TypographyTest
 *
 * @package ystandard
 */

require_once ABSPATH . WPINC . '/class-wp-customize-manager.php';

/**
 * Class TypographyTest
 */
class TypographyTest extends WP_UnitTestCase {

	/**
	 * テスト後の処理
	 */
	function tear_down() {
		remove_filter( 'wp_theme_json_data_user', [ $this, 'add_font_library_font' ] );
		delete_option( 'ys_design_font_type' );
		delete_option( 'ys_design_font_weight' );
		delete_option( 'ys_site_line_height' );
		delete_option( 'ys_heading_line_height' );
		delete_option( 'ys_site_letter_spacing' );
		delete_option( 'ys_heading_letter_spacing' );
		WP_Theme_JSON_Resolver::clean_cached_data();

		parent::tear_down();
	}

	/**
	 * Test: 標準フォントを取得できる
	 */
	function test_get_usable_fonts_includes_default_fonts() {
		$fonts = \ystandard\Typography::get_usable_fonts();

		$this->assertArrayHasKey( 'font-library-ystd-gothic', $fonts );
		$this->assertArrayHasKey( 'font-library-ystd-yu-gothic', $fonts );
		$this->assertArrayHasKey( 'font-library-ystd-serif', $fonts );
		$this->assertArrayNotHasKey( 'meihiragino', $fonts );
		$this->assertSame(
			'"Helvetica neue", Arial, "Hiragino Sans", "Hiragino Kaku Gothic ProN", Meiryo, sans-serif',
			$fonts['font-library-ystd-gothic']['family']
		);
	}

	/**
	 * Test: 旧フォント設定値を出力時に変換できる
	 */
	function test_add_css_vars_converts_legacy_font_type() {
		update_option( 'ys_design_font_type', 'meihiragino' );

		$css_vars = \ystandard\Typography::get_instance()->add_css_vars( [] );

		$this->assertSame( 'meihiragino', get_option( 'ys_design_font_type' ) );
		$this->assertSame(
			'"Helvetica neue", Arial, "Hiragino Sans", "Hiragino Kaku Gothic ProN", Meiryo, sans-serif',
			$css_vars['--ystd--font-family']
		);
	}

	/**
	 * Test: カスタマイザー表示時に旧フォント設定値を変換できる
	 */
	function test_customize_value_converts_legacy_font_type() {
		$legacy_font_types = [
			'meihiragino' => 'font-library-ystd-gothic',
			'yugo'        => 'font-library-ystd-yu-gothic',
			'serif'       => 'font-library-ystd-serif',
		];

		foreach ( $legacy_font_types as $legacy_font_type => $font_type ) {
			$this->assertSame(
				$font_type,
				apply_filters( 'customize_value_ys_design_font_type', $legacy_font_type )
			);
		}
	}

	/**
	 * Test: Font Libraryに追加したフォントを取得できる
	 */
	function test_get_usable_fonts_includes_font_library_fonts() {
		add_filter( 'wp_theme_json_data_user', [ $this, 'add_font_library_font' ] );
		WP_Theme_JSON_Resolver::clean_cached_data();

		$fonts = \ystandard\Typography::get_usable_fonts();

		$this->assertArrayHasKey( 'font-library-test-font', $fonts );
		$this->assertSame( 'テストフォント', $fonts['font-library-test-font']['label'] );
		$this->assertSame( '"Test Font", sans-serif', $fonts['font-library-test-font']['family'] );
		$this->assertSame( 'custom', $fonts['font-library-test-font']['origin'] );
		$this->assertSame( [ 500, 700 ], $fonts['font-library-test-font']['weights'] );
	}

	/**
	 * Test: 400を持たない追加フォントのウエイト選択肢を取得できる
	 */
	function test_get_font_weight_choices() {
		add_filter( 'wp_theme_json_data_user', [ $this, 'add_font_library_font' ] );
		WP_Theme_JSON_Resolver::clean_cached_data();

		$this->assertSame(
			[
				''  => '指定なし（400）',
				500 => '500',
				700 => '700',
			],
			\ystandard\Typography::get_font_weight_choices( 'font-library-test-font' )
		);
		$this->assertSame(
			[
				''  => '指定なし（400）',
				500 => '500',
				600 => '600',
				700 => '700',
				800 => '800',
				900 => '900',
			],
			\ystandard\Typography::get_font_weight_choices( 'font-library-variable-font' )
		);
		$this->assertSame( [], \ystandard\Typography::get_font_weight_choices( 'font-library-regular-font' ) );
		$this->assertSame( [], \ystandard\Typography::get_font_weight_choices( 'font-library-full-variable-font' ) );
		$this->assertSame( [], \ystandard\Typography::get_font_weight_choices( 'font-library-ystd-gothic' ) );
	}

	/**
	 * Test: フォントごとのウエイトコントロールをPHPで登録できる
	 */
	function test_customize_register_adds_font_weight_controls() {
		wp_set_current_user( self::factory()->user->create( [ 'role' => 'administrator' ] ) );
		add_filter( 'wp_theme_json_data_user', [ $this, 'add_font_library_font' ] );
		update_option( 'ys_design_font_type', 'font-library-test-font' );
		WP_Theme_JSON_Resolver::clean_cached_data();

		$wp_customize = new WP_Customize_Manager();
		\ystandard\Typography::get_instance()->customize_register( $wp_customize );

		$setting          = $wp_customize->get_setting( 'ys_design_font_weight' );
		$test_control     = $wp_customize->get_control( 'ys_design_font_weight__font-library-test-font' );
		$variable_control = $wp_customize->get_control( 'ys_design_font_weight__font-library-variable-font' );

		$this->assertInstanceOf( WP_Customize_Setting::class, $setting );
		$this->assertInstanceOf( WP_Customize_Control::class, $test_control );
		$this->assertSame( $setting, $test_control->settings['default'] );
		$this->assertSame(
			'選択したフォントにはウエイト400がないため、本文の標準ウエイトを選択してください。',
			$test_control->description
		);
		$this->assertSame(
			[
				''  => '指定なし（400）',
				500 => '500',
				700 => '700',
			],
			$test_control->choices
		);
		$this->assertTrue( $test_control->active() );
		$this->assertFalse( $variable_control->active() );
		$this->assertNull( $wp_customize->get_control( 'ys_design_font_weight__font-library-regular-font' ) );

		$wp_customize->set_post_value( 'ys_design_font_type', 'font-library-variable-font' );

		$this->assertFalse( $test_control->active() );
		$this->assertTrue( $variable_control->active() );
	}

	/**
	 * Test: サイトフォント・文字設定をカスタマイザーへ追加できる
	 */
	function test_customize_register_adds_text_settings() {
		$wp_customize = new WP_Customize_Manager();
		\ystandard\Typography::get_instance()->customize_register( $wp_customize );

		$section                = $wp_customize->get_section( 'ys_section_font' );
		$site_line_height       = $wp_customize->get_control( 'ys_site_line_height' );
		$heading_line_height    = $wp_customize->get_control( 'ys_heading_line_height' );
		$site_letter_spacing    = $wp_customize->get_control( 'ys_site_letter_spacing' );
		$heading_letter_spacing = $wp_customize->get_control( 'ys_heading_letter_spacing' );
		$text_label_id          = 'ys_' . substr( md5( 'テキスト設定ys_section_font' ), 0, 40 );
		$link_label_id          = 'ys_' . substr( md5( 'リンク色ys_section_font' ), 0, 40 );

		$this->assertSame( '[ys]サイトフォント・文字設定', $section->title );
		$this->assertStringStartsWith( 'サイト全体のフォント・文字色・テキスト関連の設定', $section->description );
		$this->assertInstanceOf( WP_Customize_Setting::class, $wp_customize->get_setting( $text_label_id ) );
		$this->assertNull( $wp_customize->get_setting( $link_label_id ) );
		$this->assertSame( '行高さ（全体）', $site_line_height->label );
		$this->assertSame( [ 'min' => 1, 'max' => 3, 'step' => 0.1, 'placeholder' => 1.7 ], $site_line_height->input_attrs );
		$this->assertSame( '行高さ（見出し）', $heading_line_height->label );
		$this->assertSame( [ 'min' => 1, 'max' => 3, 'step' => 0.1, 'placeholder' => 1.3 ], $heading_line_height->input_attrs );
		$this->assertSame( '文字間隔（全体）', $site_letter_spacing->label );
		$this->assertSame( [ 'min' => -0.1, 'max' => 0.5, 'step' => 0.01, 'placeholder' => 0.05 ], $site_letter_spacing->input_attrs );
		$this->assertSame( '文字間隔（見出し）', $heading_letter_spacing->label );
		$this->assertSame( [ 'min' => -0.1, 'max' => 0.5, 'step' => 0.01, 'placeholder' => 0.05 ], $heading_letter_spacing->input_attrs );
	}

	/**
	 * Test: テキスト設定を入力範囲内で保存できる
	 */
	function test_sanitize_text_settings() {
		$wp_customize = new WP_Customize_Manager();
		\ystandard\Typography::get_instance()->customize_register( $wp_customize );

		$site_line_height    = $wp_customize->get_setting( 'ys_site_line_height' );
		$site_letter_spacing = $wp_customize->get_setting( 'ys_site_letter_spacing' );

		$this->assertSame( '1.8', $site_line_height->sanitize( '1.8' ) );
		$this->assertSame( '', $site_line_height->sanitize( '3.1' ) );
		$this->assertSame( '-0.02', $site_letter_spacing->sanitize( '-0.02' ) );
		$this->assertSame( '', $site_letter_spacing->sanitize( '0.51' ) );
	}

	/**
	 * Test: 選択中のフォントで利用できるウエイトだけ保存できる
	 */
	function test_sanitize_font_weight() {
		wp_set_current_user( self::factory()->user->create( [ 'role' => 'administrator' ] ) );
		add_filter( 'wp_theme_json_data_user', [ $this, 'add_font_library_font' ] );
		update_option( 'ys_design_font_type', 'font-library-test-font' );
		WP_Theme_JSON_Resolver::clean_cached_data();

		$wp_customize = new WP_Customize_Manager();
		\ystandard\Typography::get_instance()->customize_register( $wp_customize );
		$setting = $wp_customize->get_setting( 'ys_design_font_weight' );

		$this->assertSame( '700', $setting->sanitize( '700' ) );
		$this->assertSame( '', $setting->sanitize( '600' ) );

		$wp_customize->set_post_value( 'ys_design_font_type', 'font-library-variable-font' );
		$this->assertSame( '600', $setting->sanitize( '600' ) );

		$wp_customize->set_post_value( 'ys_design_font_type', 'font-library-regular-font' );
		$this->assertSame( '', $setting->sanitize( '700' ) );
	}

	/**
	 * Test: Font Libraryのフォントをサイトのフォントに指定できる
	 */
	function test_add_css_vars_uses_font_library_font() {
		add_filter( 'wp_theme_json_data_user', [ $this, 'add_font_library_font' ] );
		update_option( 'ys_design_font_type', 'font-library-test-font' );
		update_option( 'ys_design_font_weight', '700' );
		WP_Theme_JSON_Resolver::clean_cached_data();

		$css_vars = \ystandard\Typography::get_instance()->add_css_vars( [] );

		$this->assertSame( '"Test Font", sans-serif', $css_vars['--ystd--font-family'] );
		$this->assertSame( '700', $css_vars['--ystd--font-weight--normal'] );
	}

	/**
	 * Test: 400を持つ追加フォントでは標準ウエイトを上書きしない
	 */
	function test_add_css_vars_ignores_font_weight_when_font_has_400() {
		add_filter( 'wp_theme_json_data_user', [ $this, 'add_font_library_font' ] );
		update_option( 'ys_design_font_type', 'font-library-regular-font' );
		update_option( 'ys_design_font_weight', '700' );
		WP_Theme_JSON_Resolver::clean_cached_data();

		$css_vars = \ystandard\Typography::get_instance()->add_css_vars( [] );

		$this->assertArrayNotHasKey( '--ystd--font-weight--normal', $css_vars );
	}

	/**
	 * Test: テキスト設定をCSSカスタムプロパティとして出力できる
	 */
	function test_add_css_vars_uses_text_settings() {
		update_option( 'ys_site_line_height', '1.8' );
		update_option( 'ys_heading_line_height', '1.4' );
		update_option( 'ys_site_letter_spacing', '0' );
		update_option( 'ys_heading_letter_spacing', '-0.02' );

		$css_vars = \ystandard\Typography::get_instance()->add_css_vars( [] );

		$this->assertSame( '1.8', $css_vars['--ystd--line-height'] );
		$this->assertSame( '1.4', $css_vars['--ystd--headline--line-height'] );
		$this->assertSame( '0em', $css_vars['--ystd--letter-spacing'] );
		$this->assertSame( '-0.02em', $css_vars['--ystd--headline--letter-spacing'] );
	}

	/**
	 * Font Libraryのテスト用フォントを追加
	 *
	 * @param WP_Theme_JSON_Data $theme_json Theme JSONデータ.
	 *
	 * @return WP_Theme_JSON_Data
	 */
	function add_font_library_font( $theme_json ) {
		return $theme_json->update_with(
			[
				'version'  => 3,
				'settings' => [
					'typography' => [
						'fontFamilies' => [
							[
								'fontFamily' => '"Test Font", sans-serif',
								'name'       => 'テストフォント',
								'slug'       => 'test-font',
								'fontFace'   => [
									[
										'fontFamily' => 'Test Font',
										'fontStyle'  => 'normal',
										'fontWeight' => '500',
										'src'        => [ 'file:./test-font-500.woff2' ],
									],
									[
										'fontFamily' => 'Test Font',
										'fontStyle'  => 'normal',
										'fontWeight' => '700',
										'src'        => [ 'file:./test-font-700.woff2' ],
									],
									[
										'fontFamily' => 'Test Font',
										'fontStyle'  => 'italic',
										'fontWeight' => '400',
										'src'        => [ 'file:./test-font-400-italic.woff2' ],
									],
								],
							],
							[
								'fontFamily' => '"Regular Font", sans-serif',
								'name'       => '標準ウエイトありフォント',
								'slug'       => 'regular-font',
								'fontFace'   => [
									[
										'fontFamily' => 'Regular Font',
										'fontStyle'  => 'normal',
										'fontWeight' => '400',
										'src'        => [ 'file:./regular-font-400.woff2' ],
									],
									[
										'fontFamily' => 'Regular Font',
										'fontStyle'  => 'normal',
										'fontWeight' => '700',
										'src'        => [ 'file:./regular-font-700.woff2' ],
									],
								],
							],
							[
								'fontFamily' => '"Variable Font", sans-serif',
								'name'       => '可変フォント',
								'slug'       => 'variable-font',
								'fontFace'   => [
									[
										'fontFamily' => 'Variable Font',
										'fontStyle'  => 'normal',
										'fontWeight' => '500 900',
										'src'        => [ 'file:./variable-font.woff2' ],
									],
								],
							],
							[
								'fontFamily' => '"Full Variable Font", sans-serif',
								'name'       => '400を含む可変フォント',
								'slug'       => 'full-variable-font',
								'fontFace'   => [
									[
										'fontFamily' => 'Full Variable Font',
										'fontStyle'  => 'normal',
										'fontWeight' => '100 900',
										'src'        => [ 'file:./full-variable-font.woff2' ],
									],
								],
							],
						],
					],
				],
			]
		);
	}
}
