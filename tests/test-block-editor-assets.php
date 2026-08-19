<?php
/**
 * Class BlockEditorAssetsTest
 *
 * @package ystandard
 */

/**
 * Class BlockEditorAssetsTest
 */
class BlockEditorAssetsTest extends WP_UnitTestCase {

	/**
	 * セットアップ.
	 */
	public function set_up() {
		parent::set_up();

		$GLOBALS['wp_styles'] = null;
		wp_styles();
	}

	/**
	 * テスト後の処理.
	 */
	public function tear_down() {
		remove_filter( 'wp_theme_json_data_user', [ $this, 'add_font_library_font' ] );
		delete_option( 'ys_design_font_type' );
		delete_option( 'ys_design_font_weight' );
		delete_option( 'ys_site_line_height' );
		delete_option( 'ys_heading_line_height' );
		delete_option( 'ys_site_letter_spacing' );
		delete_option( 'ys_heading_letter_spacing' );
		WP_Theme_JSON_Resolver::clean_cached_data();
		set_current_screen( 'front' );

		parent::tear_down();
	}

	/**
	 * 編集コンテンツ用フックへスタイルを登録していることを確認.
	 */
	public function test_register_editor_content_assets_hook() {
		$assets = $this->get_block_editor_assets_instance();

		$this->assertSame( 11, has_action( 'enqueue_block_assets', [ $assets, 'enqueue_block_editor_assets' ] ) );
		$this->assertFalse( has_action( 'enqueue_block_editor_assets', [ $assets, 'enqueue_block_editor_assets' ] ) );
	}

	/**
	 * WordPress標準のカラーパレットが無効であることを確認.
	 */
	public function test_default_color_palette_is_disabled() {
		WP_Theme_JSON_Resolver::clean_cached_data();

		$default_palette_enabled = wp_get_global_settings( [ 'color', 'defaultPalette' ] );
		$theme_palette           = wp_get_global_settings( [ 'color', 'palette', 'theme' ] );

		$this->assertFalse( $default_palette_enabled );
		$this->assertNotEmpty( $theme_palette );
		$this->assertFalse( get_theme_support( 'editor-color-palette' ) );
	}

	/**
	 * theme.jsonの全体・見出し文字設定がCSSカスタムプロパティを参照することを確認.
	 */
	public function test_global_typography_styles_use_custom_properties() {
		WP_Theme_JSON_Resolver::clean_cached_data();
		$stylesheet = wp_get_global_stylesheet( [ 'styles' ] );

		$this->assertSame(
			'var(--ystd--line-height, 1.7)',
			wp_get_global_styles( [ 'typography', 'lineHeight' ], [ 'origin' => 'base' ] )
		);
		$this->assertSame(
			'var(--ystd--letter-spacing, 0.05em)',
			wp_get_global_styles( [ 'typography', 'letterSpacing' ], [ 'origin' => 'base' ] )
		);
		$this->assertSame(
			'var(--ystd--headline--line-height, 1.3)',
			wp_get_global_styles( [ 'elements', 'heading', 'typography', 'lineHeight' ], [ 'origin' => 'base' ] )
		);
		$this->assertSame(
			'var(--ystd--headline--letter-spacing, 0.05em)',
			wp_get_global_styles( [ 'elements', 'heading', 'typography', 'letterSpacing' ], [ 'origin' => 'base' ] )
		);
		$this->assertSame(
			'var(--ystd--headline--font-weight, 700)',
			wp_get_global_styles( [ 'elements', 'heading', 'typography', 'fontWeight' ], [ 'origin' => 'base' ] )
		);
		$this->assertStringContainsString( '--ystd--headline--font-weight', $stylesheet );

		foreach ( range( 1, 6 ) as $level ) {
			$this->assertSame(
				"var(--ystd--headline--font-weight--h{$level})",
				wp_get_global_styles( [ 'elements', "h{$level}", 'typography', 'fontWeight' ], [ 'origin' => 'base' ] )
			);
			$this->assertStringContainsString( "--ystd--headline--font-weight--h{$level}", $stylesheet );
		}
	}

	/**
	 * 編集コンテンツへフォント設定を追加できることを確認.
	 */
	public function test_enqueue_font_settings_for_editor_content() {
		add_filter( 'wp_theme_json_data_user', [ $this, 'add_font_library_font' ] );
		update_option( 'ys_design_font_type', 'font-library-test-font' );
		update_option( 'ys_design_font_weight', '700' );
		update_option( 'ys_site_line_height', '1.8' );
		update_option( 'ys_heading_line_height', '1.4' );
		update_option( 'ys_site_letter_spacing', '0.08' );
		update_option( 'ys_heading_letter_spacing', '0.02' );
		WP_Theme_JSON_Resolver::clean_cached_data();
		set_current_screen( 'post' );

		do_action( 'enqueue_block_assets' );

		$inline_css = implode( '', wp_styles()->registered['ys-block-editor-assets']->extra['after'] );
		$this->assertTrue( wp_style_is( 'ys-block-editor-assets', 'enqueued' ) );
		$this->assertStringStartsWith( '.editor-styles-wrapper{ ', $inline_css );
		$this->assertStringContainsString( '--ystd--font-family: "Test Font", sans-serif;', $inline_css );
		$this->assertStringContainsString( '--ystd--font-weight--normal: 700;', $inline_css );
		$this->assertStringContainsString( '--ystd--line-height: 1.8;', $inline_css );
		$this->assertStringContainsString( '--ystd--headline--line-height: 1.4;', $inline_css );
		$this->assertStringContainsString( '--ystd--letter-spacing: 0.08em;', $inline_css );
		$this->assertStringContainsString( '--ystd--headline--letter-spacing: 0.02em;', $inline_css );
	}

	/**
	 * フロントエンドでは編集画面用CSSを読み込まないことを確認.
	 */
	public function test_do_not_enqueue_editor_assets_on_frontend() {
		set_current_screen( 'front' );

		do_action( 'enqueue_block_assets' );

		$this->assertFalse( wp_style_is( 'ys-block-editor-assets', 'enqueued' ) );
	}

	/**
	 * Block_Editor_Assetsインスタンスを取得.
	 *
	 * @return \ystandard\Block_Editor_Assets
	 */
	private function get_block_editor_assets_instance() {
		global $wp_filter;

		$callbacks = $wp_filter['enqueue_block_assets']->callbacks[11];
		foreach ( $callbacks as $callback ) {
			$function = $callback['function'];
			if (
				is_array( $function ) &&
				$function[0] instanceof \ystandard\Block_Editor_Assets &&
				'enqueue_block_editor_assets' === $function[1]
			) {
				return $function[0];
			}
		}

		$this->fail( 'Block_Editor_Assetsインスタンスを取得できません。' );
	}

	/**
	 * Font Libraryのテスト用フォントを追加.
	 *
	 * @param WP_Theme_JSON_Data $theme_json Theme JSONデータ.
	 *
	 * @return WP_Theme_JSON_Data
	 */
	public function add_font_library_font( $theme_json ) {
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
										'fontWeight' => '700',
										'src'        => [ 'file:./test-font-700.woff2' ],
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
