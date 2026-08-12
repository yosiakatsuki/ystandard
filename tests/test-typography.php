<?php
/**
 * Class TypographyTest
 *
 * @package ystandard
 */

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
	}

	/**
	 * Test: Font Libraryのフォントをサイトのフォントに指定できる
	 */
	function test_add_css_vars_uses_font_library_font() {
		add_filter( 'wp_theme_json_data_user', [ $this, 'add_font_library_font' ] );
		update_option( 'ys_design_font_type', 'font-library-test-font' );
		WP_Theme_JSON_Resolver::clean_cached_data();

		$css_vars = \ystandard\Typography::get_instance()->add_css_vars( [] );

		$this->assertSame( '"Test Font", sans-serif', $css_vars['--ystd--font-family'] );
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
							],
						],
					],
				],
			]
		);
	}
}
