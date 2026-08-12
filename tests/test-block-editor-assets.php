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
	 * 編集コンテンツへフォント設定を追加できることを確認.
	 */
	public function test_enqueue_font_settings_for_editor_content() {
		add_filter( 'wp_theme_json_data_user', [ $this, 'add_font_library_font' ] );
		update_option( 'ys_design_font_type', 'font-library-test-font' );
		update_option( 'ys_design_font_weight', '700' );
		WP_Theme_JSON_Resolver::clean_cached_data();
		set_current_screen( 'post' );

		do_action( 'enqueue_block_assets' );

		$inline_css = implode( '', wp_styles()->registered['ys-block-editor-assets']->extra['after'] );
		$this->assertTrue( wp_style_is( 'ys-block-editor-assets', 'enqueued' ) );
		$this->assertStringStartsWith( '.editor-styles-wrapper{ ', $inline_css );
		$this->assertStringContainsString( '--ystd--font-family: "Test Font", sans-serif;', $inline_css );
		$this->assertStringContainsString( '--ystd--font-weight--normal: 700;', $inline_css );
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
