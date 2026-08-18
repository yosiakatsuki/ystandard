<?php
/**
 * Class BlockEditorFontSizeTest
 *
 * @package ystandard
 */

/**
 * Class BlockEditorFontSizeTest
 */
class BlockEditorFontSizeTest extends WP_UnitTestCase {

	/**
	 * テスト終了処理.
	 */
	public function tear_down() {
		for ( $i = 1; $i <= \ystandard\Block_Editor_Font_Size::USER_FONT_SIZE_LIMIT; $i ++ ) {
			foreach ( [ 'label', 'type', 'static', 'min', 'max', 'unit' ] as $field ) {
				delete_option( \ystandard\Block_Editor_Font_Size::get_option_name( $i, $field ) );
			}
		}
		WP_Theme_JSON_Resolver::clean_cached_data();
		parent::tear_down();
	}

	/**
	 * 固定値を正規化できることを確認.
	 *
	 * @dataProvider static_size_provider
	 *
	 * @param mixed  $input 入力値.
	 * @param string $expected 期待値.
	 */
	public function test_static_size_is_normalized( $input, $expected ) {
		$this->assertSame( $expected, \ystandard\Block_Editor_Font_Size::normalize_static_size( $input ) );
	}

	/**
	 * 固定値のデータ.
	 *
	 * @return array
	 */
	public function static_size_provider() {
		return [
			'number'              => [ '16', '16px' ],
			'length'              => [ '1.25rem', '1.25rem' ],
			'calc'                => [ 'calc(1rem + 0.5vw)', 'calc(1rem + 0.5vw)' ],
			'clamp'               => [ 'clamp(1rem, 2vw, 1.5rem)', 'clamp(1rem, 2vw, 1.5rem)' ],
			'css-declaration'      => [ '16px; color: red', '' ],
			'comment'              => [ '16px/* comment */', '' ],
			'url'                  => [ 'url(https://example.com)', '' ],
			'invalid-parentheses'  => [ 'calc(1rem + 1vw', '' ],
			'unsupported-function' => [ 'min(1rem, 2rem)', '' ],
			'negative'             => [ '-1rem', '' ],
		];
	}

	/**
	 * 固定値とfluidを設定番号順で生成することを確認.
	 */
	public function test_user_font_sizes_are_generated_in_setting_order() {
		update_option( \ystandard\Block_Editor_Font_Size::get_option_name( 1, 'label' ), '本文大' );
		update_option( \ystandard\Block_Editor_Font_Size::get_option_name( 1, 'type' ), 'static' );
		update_option( \ystandard\Block_Editor_Font_Size::get_option_name( 1, 'static' ), '18' );
		update_option( \ystandard\Block_Editor_Font_Size::get_option_name( 2, 'label' ), '見出し小' );
		update_option( \ystandard\Block_Editor_Font_Size::get_option_name( 2, 'type' ), 'fluid' );
		update_option( \ystandard\Block_Editor_Font_Size::get_option_name( 2, 'min' ), '1.2' );
		update_option( \ystandard\Block_Editor_Font_Size::get_option_name( 2, 'max' ), '1.8' );
		update_option( \ystandard\Block_Editor_Font_Size::get_option_name( 2, 'unit' ), 'rem' );
		update_option( \ystandard\Block_Editor_Font_Size::get_option_name( 3, 'label' ), '未完成' );

		$font_sizes = \ystandard\Block_Editor_Font_Size::get_user_font_sizes();

		$this->assertSame( [ 'ystd-font-size-preset-1', 'ystd-font-size-preset-2' ], array_column( $font_sizes, 'slug' ) );
		$this->assertSame( '18px', $font_sizes[0]['size'] );
		$this->assertFalse( $font_sizes[0]['fluid'] );
		$this->assertSame( '1.8rem', $font_sizes[1]['size'] );
		$this->assertSame( [ 'min' => '1.2rem', 'max' => '1.8rem' ], $font_sizes[1]['fluid'] );
	}

	/**
	 * ユーザー定義文字サイズを6件まで生成することを確認.
	 */
	public function test_user_font_sizes_support_six_presets() {
		for ( $i = 1; $i <= \ystandard\Block_Editor_Font_Size::USER_FONT_SIZE_LIMIT; $i ++ ) {
			update_option( \ystandard\Block_Editor_Font_Size::get_option_name( $i, 'label' ), '設定' . $i );
			update_option( \ystandard\Block_Editor_Font_Size::get_option_name( $i, 'static' ), (string) ( 15 + $i ) );
		}

		$font_sizes = \ystandard\Block_Editor_Font_Size::get_user_font_sizes();

		$this->assertCount( 6, $font_sizes );
		$this->assertSame(
			[
				'ystd-font-size-preset-1',
				'ystd-font-size-preset-2',
				'ystd-font-size-preset-3',
				'ystd-font-size-preset-4',
				'ystd-font-size-preset-5',
				'ystd-font-size-preset-6',
			],
			array_column( $font_sizes, 'slug' )
		);
	}

	/**
	 * 不正なfluid設定をプリセットにしないことを確認.
	 *
	 * @dataProvider invalid_fluid_provider
	 *
	 * @param string $min 最小値.
	 * @param string $max 最大値.
	 * @param string $unit 単位.
	 */
	public function test_invalid_fluid_font_size_is_not_generated( $min, $max, $unit ) {
		update_option( \ystandard\Block_Editor_Font_Size::get_option_name( 1, 'label' ), '不正な設定' );
		update_option( \ystandard\Block_Editor_Font_Size::get_option_name( 1, 'type' ), 'fluid' );
		update_option( \ystandard\Block_Editor_Font_Size::get_option_name( 1, 'min' ), $min );
		update_option( \ystandard\Block_Editor_Font_Size::get_option_name( 1, 'max' ), $max );
		update_option( \ystandard\Block_Editor_Font_Size::get_option_name( 1, 'unit' ), $unit );

		$this->assertSame( [], \ystandard\Block_Editor_Font_Size::get_user_font_sizes() );
	}

	/**
	 * 不正なfluid設定のデータ.
	 *
	 * @return array
	 */
	public function invalid_fluid_provider() {
		return [
			'px-decimal'    => [ '16.5', '20', 'px' ],
			'rem-step'      => [ '1.25', '2', 'rem' ],
			'reversed'      => [ '2', '1', 'rem' ],
			'over-maximum'  => [ '1', '1000', 'px' ],
			'invalid-unit'  => [ '1', '2', 'em' ],
			'missing-value' => [ '', '2', 'rem' ],
		];
	}

	/**
	 * ユーザー設定をテーマ標準プリセットの先頭へ追加することを確認.
	 */
	public function test_user_font_sizes_are_prepended_to_theme_json() {
		update_option( \ystandard\Block_Editor_Font_Size::get_option_name( 1, 'label' ), '追加サイズ' );
		update_option( \ystandard\Block_Editor_Font_Size::get_option_name( 1, 'static' ), '20px' );

		$theme_json = new WP_Theme_JSON_Data(
			[
				'version'  => 3,
				'settings' => [
					'typography' => [
						'fontSizes' => [
							[
								'name' => '既存サイズ',
								'slug' => 'existing-size',
								'size' => '1rem',
							],
							[
								'name' => '古い管理対象',
								'slug' => 'ystd-font-size-preset-1',
								'size' => '10px',
							],
						],
					],
				],
			],
			'theme'
		);
		$font_size  = ( new ReflectionClass( \ystandard\Block_Editor_Font_Size::class ) )->newInstanceWithoutConstructor();
		$data       = $font_size->add_user_font_sizes_to_theme_json( $theme_json )->get_data();
		$presets    = $data['settings']['typography']['fontSizes']['theme'];

		$this->assertSame( [ 'ystd-font-size-preset-1', 'existing-size' ], array_column( $presets, 'slug' ) );
		$this->assertSame( '20px', $presets[0]['size'] );
		$this->assertSame( '既存サイズ', $presets[1]['name'] );
	}

	/**
	 * Global Stylesがユーザー定義文字サイズのCSSを生成することを確認.
	 */
	public function test_global_styles_generates_user_font_size_css() {
		update_option( \ystandard\Block_Editor_Font_Size::get_option_name( 1, 'label' ), '追加サイズ' );
		update_option( \ystandard\Block_Editor_Font_Size::get_option_name( 1, 'static' ), '20px' );
		WP_Theme_JSON_Resolver::clean_cached_data();

		$font_sizes = wp_get_global_settings( [ 'typography', 'fontSizes', 'theme' ] );
		$stylesheet = wp_get_global_stylesheet( [ 'variables', 'presets' ] );

		$this->assertSame( 'ystd-font-size-preset-1', $font_sizes[0]['slug'] );
		$this->assertStringContainsString( '--wp--preset--font-size--ystd-font-size-preset-1', $stylesheet );
		$this->assertStringContainsString( '.has-ystd-font-size-preset-1-font-size', $stylesheet );
		foreach ( [ 'x-small', 'small', 'normal', 'medium', 'large', 'x-large', 'xx-large' ] as $theme_slug ) {
			$this->assertStringContainsString( '--wp--preset--font-size--' . $theme_slug, $stylesheet );
			$this->assertStringContainsString( '.has-' . $theme_slug . '-font-size', $stylesheet );
		}
	}
}
