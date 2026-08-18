<?php
/**
 * Class BlockEditorSpacingSizeTest
 *
 * @package ystandard
 */

/**
 * Class BlockEditorSpacingSizeTest
 */
class BlockEditorSpacingSizeTest extends WP_UnitTestCase {

	/**
	 * テスト終了処理.
	 */
	public function tear_down() {
		for ( $i = 1; $i <= \ystandard\Block_Editor_Spacing_Size::USER_SPACING_SIZE_LIMIT; $i ++ ) {
			foreach ( [ 'label', 'value' ] as $field ) {
				delete_option( \ystandard\Block_Editor_Spacing_Size::get_option_name( $i, $field ) );
			}
		}
		WP_Theme_JSON_Resolver::clean_cached_data();
		parent::tear_down();
	}

	/**
	 * 余白サイズを正規化できることを確認.
	 *
	 * @dataProvider spacing_size_provider
	 *
	 * @param mixed  $input 入力値.
	 * @param string $expected 期待値.
	 */
	public function test_spacing_size_is_normalized( $input, $expected ) {
		$this->assertSame( $expected, \ystandard\Block_Editor_Spacing_Size::normalize_spacing_size( $input ) );
	}

	/**
	 * 余白サイズのデータ.
	 *
	 * @return array
	 */
	public function spacing_size_provider() {
		return [
			'number'              => [ '24', '24px' ],
			'zero'                => [ '0', '0px' ],
			'length'              => [ '1.5rem', '1.5rem' ],
			'percentage'          => [ '10%', '10%' ],
			'calc'                => [ 'calc(1rem + 1vw)', 'calc(1rem + 1vw)' ],
			'clamp'               => [ 'clamp(1rem, 2vw, 3rem)', 'clamp(1rem, 2vw, 3rem)' ],
			'min'                 => [ 'min(4vw, 40px)', 'min(4vw, 40px)' ],
			'max'                 => [ 'max(1rem, 20px)', 'max(1rem, 20px)' ],
			'nested-var'          => [ 'calc(var(--wp--style--block-gap) * 2)', 'calc(var(--wp--style--block-gap) * 2)' ],
			'css-declaration'      => [ '16px; color: red', '' ],
			'comment'              => [ '16px/* comment */', '' ],
			'url'                  => [ 'url(https://example.com)', '' ],
			'invalid-parentheses'  => [ 'calc(1rem + 1vw', '' ],
			'unsupported-function' => [ 'round(1rem, 1px)', '' ],
			'direct-var'           => [ 'var(--wp--style--block-gap)', '' ],
			'negative'             => [ '-1rem', '' ],
		];
	}

	/**
	 * 有効な余白サイズを設定番号順で生成することを確認.
	 */
	public function test_user_spacing_sizes_are_generated_in_setting_order() {
		update_option( \ystandard\Block_Editor_Spacing_Size::get_option_name( 1, 'label' ), 'セクション余白' );
		update_option( \ystandard\Block_Editor_Spacing_Size::get_option_name( 1, 'value' ), '40' );
		update_option( \ystandard\Block_Editor_Spacing_Size::get_option_name( 2, 'label' ), 'カード余白' );
		update_option( \ystandard\Block_Editor_Spacing_Size::get_option_name( 2, 'value' ), 'clamp(1rem, 2vw, 2rem)' );
		update_option( \ystandard\Block_Editor_Spacing_Size::get_option_name( 3, 'label' ), '値なし' );
		update_option( \ystandard\Block_Editor_Spacing_Size::get_option_name( 4, 'value' ), '20px' );
		update_option( \ystandard\Block_Editor_Spacing_Size::get_option_name( 5, 'label' ), '不正値' );
		update_option( \ystandard\Block_Editor_Spacing_Size::get_option_name( 5, 'value' ), '-10px' );

		$spacing_sizes = \ystandard\Block_Editor_Spacing_Size::get_user_spacing_sizes();

		$this->assertSame( [ 'ystd-spacing-preset-1', 'ystd-spacing-preset-2' ], array_column( $spacing_sizes, 'slug' ) );
		$this->assertSame( 'セクション余白', $spacing_sizes[0]['name'] );
		$this->assertSame( '40px', $spacing_sizes[0]['size'] );
		$this->assertSame( 'clamp(1rem, 2vw, 2rem)', $spacing_sizes[1]['size'] );
	}

	/**
	 * ユーザー定義余白サイズを6件まで生成することを確認.
	 */
	public function test_user_spacing_sizes_support_six_presets() {
		for ( $i = 1; $i <= \ystandard\Block_Editor_Spacing_Size::USER_SPACING_SIZE_LIMIT; $i ++ ) {
			update_option( \ystandard\Block_Editor_Spacing_Size::get_option_name( $i, 'label' ), '設定' . $i );
			update_option( \ystandard\Block_Editor_Spacing_Size::get_option_name( $i, 'value' ), (string) ( 10 * $i ) );
		}

		$spacing_sizes = \ystandard\Block_Editor_Spacing_Size::get_user_spacing_sizes();

		$this->assertCount( 6, $spacing_sizes );
		$this->assertSame(
			[
				'ystd-spacing-preset-1',
				'ystd-spacing-preset-2',
				'ystd-spacing-preset-3',
				'ystd-spacing-preset-4',
				'ystd-spacing-preset-5',
				'ystd-spacing-preset-6',
			],
			array_column( $spacing_sizes, 'slug' )
		);
	}

	/**
	 * ユーザー設定を既存custom originの先頭へ追加することを確認.
	 */
	public function test_user_spacing_sizes_are_prepended_to_custom_theme_json() {
		update_option( \ystandard\Block_Editor_Spacing_Size::get_option_name( 1, 'label' ), '追加余白' );
		update_option( \ystandard\Block_Editor_Spacing_Size::get_option_name( 1, 'value' ), '32px' );

		$theme_json = new WP_Theme_JSON_Data(
			[
				'version'  => 3,
				'settings' => [
					'spacing' => [
						'spacingSizes' => [
							[
								'name' => '既存余白',
								'slug' => 'existing-spacing',
								'size' => '1rem',
							],
							[
								'name' => '古い管理対象',
								'slug' => 'ystd-spacing-preset-1',
								'size' => '10px',
							],
						],
					],
				],
			],
			'custom'
		);
		$spacing_size = ( new ReflectionClass( \ystandard\Block_Editor_Spacing_Size::class ) )->newInstanceWithoutConstructor();
		$data         = $spacing_size->add_user_spacing_sizes_to_theme_json( $theme_json )->get_data();
		$presets      = $data['settings']['spacing']['spacingSizes']['custom'];

		$this->assertSame( [ 'ystd-spacing-preset-1', 'existing-spacing' ], array_column( $presets, 'slug' ) );
		$this->assertSame( '32px', $presets[0]['size'] );
		$this->assertSame( '既存余白', $presets[1]['name'] );
	}

	/**
	 * Global Stylesがユーザー定義余白サイズのCSSを生成することを確認.
	 */
	public function test_global_styles_generates_user_spacing_size_css() {
		update_option( \ystandard\Block_Editor_Spacing_Size::get_option_name( 1, 'label' ), '追加余白' );
		update_option( \ystandard\Block_Editor_Spacing_Size::get_option_name( 1, 'value' ), '32px' );
		WP_Theme_JSON_Resolver::clean_cached_data();

		$custom_spacing_sizes = wp_get_global_settings( [ 'spacing', 'spacingSizes', 'custom' ] );
		$theme_spacing_sizes  = wp_get_global_settings( [ 'spacing', 'spacingSizes', 'theme' ] );
		$stylesheet           = wp_get_global_stylesheet( [ 'variables', 'presets' ] );

		$this->assertSame( 'ystd-spacing-preset-1', $custom_spacing_sizes[0]['slug'] );
		$this->assertContains( 'ys-static-10', array_column( $theme_spacing_sizes, 'slug' ) );
		$this->assertStringContainsString( '--wp--preset--spacing--ystd-spacing-preset-1', $stylesheet );
	}
}
