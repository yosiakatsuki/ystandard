<?php
/**
 * 編集画面へ配信するカラーパレットのテスト
 *
 * @package ystandard
 */

/**
 * Class BlockEditorColorPaletteTest
 */
class BlockEditorColorPaletteTest extends WP_UnitTestCase {

	/**
	 * 使用中テーマのGlobal Styles投稿ID.
	 *
	 * @var int
	 */
	private $global_styles_id;

	/**
	 * テストの準備.
	 */
	public function set_up() {
		parent::set_up();
		// 実際の編集画面と同様に、編集用アセットの登録先を初期化する.
		wp_styles();
		wp_scripts();
		wp_set_current_user( self::factory()->user->create( [ 'role' => 'administrator' ] ) );
		update_option( 'ys-color-palette-ys-user-1', '#07689f80' );
		WP_Theme_JSON_Resolver::clean_cached_data();
		$this->global_styles_id = WP_Theme_JSON_Resolver::get_user_global_styles_post_id();
	}

	/**
	 * テスト後の処理.
	 */
	public function tear_down() {
		for ( $i = 1; $i <= \ystandard\Block_Editor_Color_Palette::USER_COLOR_LIMIT; $i ++ ) {
			delete_option( 'ys-color-palette-ys-user-' . $i );
		}
		WP_Theme_JSON_Resolver::clean_cached_data();
		wp_set_current_user( 0 );
		parent::tear_down();
	}

	/**
	 * テーマのプリセットに追加色を混ぜないことを確認.
	 */
	public function test_site_editor_theme_settings_exclude_customizer_colors() {
		$request = new WP_REST_Request( 'GET', '/wp/v2/global-styles/themes/' . get_stylesheet() );
		$request->set_param( 'context', 'edit' );
		$response = rest_do_request( $request );
		$data     = $response->get_data();

		$this->assertSame( 200, $response->get_status() );
		$palette = array_column( $data['settings']['color']['palette']['theme'], null, 'slug' );
		$this->assertArrayNotHasKey( 'ys-user-1', $palette );
		$this->assertArrayHasKey( 'ys-blue', $palette );
	}

	/**
	 * パーツ・パターン編集が取得するユーザー設定の「カスタム」に追加色が含まれることを確認.
	 */
	public function test_site_editor_user_settings_include_customizer_colors() {
		$response = $this->get_global_styles_response();
		$data     = $response->get_data();

		$this->assertSame( 200, $response->get_status() );
		$this->assertIsArray( $data['settings'] );
		$palette = array_column( $data['settings']['color']['palette']['custom'], null, 'slug' );
		$this->assertSame( '#07689f80', $palette['ys-user-1']['color'] );
		$this->assertSame( '色設定 1', $palette['ys-user-1']['name'] );
		$this->assertArrayNotHasKey( 'theme', $data['settings']['color']['palette'] );
	}

	/**
	 * サイトエディターの初期読み込みにも同じカスタム色が含まれることを確認.
	 */
	public function test_site_editor_preload_includes_customizer_colors() {
		$path      = '/wp/v2/global-styles/' . $this->global_styles_id . '?context=edit';
		$preloaded = rest_preload_api_request( [], $path );
		$this->assertIsArray( $preloaded[ $path ]['body']['settings'] );
		$palette   = array_column( $preloaded[ $path ]['body']['settings']['color']['palette']['custom'] ?? [], 'color', 'slug' );

		$this->assertSame( [ 'ys-user-1' => '#07689f80' ], $palette );
	}

	/**
	 * 投稿・パーツ・パターン編集へ同じパレットを渡すことを確認.
	 *
	 * @dataProvider editor_context_provider
	 *
	 * @param string $post_type 投稿タイプ.
	 * @param string $name      編集画面のコンテキスト名.
	 */
	public function test_editor_contexts_receive_customizer_colors( $post_type, $name ) {
		$post_id = self::factory()->post->create( [ 'post_type' => $post_type ] );
		$context = new WP_Block_Editor_Context(
			[
				'name' => $name,
				'post' => get_post( $post_id ),
			]
		);
		$settings = get_block_editor_settings( [], $context );
		$palettes = $settings['__experimentalFeatures']['color']['palette'];
		$palette  = array_column( $palettes['custom'], null, 'slug' );
		$theme    = array_column( $palettes['theme'], null, 'slug' );

		$this->assertArrayHasKey( 'ys-user-1', $palette );
		$this->assertSame( '#07689f80', $palette['ys-user-1']['color'] );
		$this->assertArrayHasKey( 'ys-blue', $theme );
		$this->assertArrayNotHasKey( 'ys-user-1', $theme );
	}

	/**
	 * 編集画面の組み合わせ.
	 *
	 * @return array
	 */
	public function editor_context_provider() {
		return [
			'post'          => [ 'post', 'core/edit-post' ],
			'template-part' => [ 'wp_template_part', 'core/edit-site' ],
			'pattern'       => [ 'wp_block', 'core/edit-site' ],
		];
	}

	/**
	 * サイトエディターで保存したカスタム色を上書きしないことを確認.
	 */
	public function test_global_styles_custom_colors_are_preserved() {
		wp_update_post(
			[
				'ID'           => $this->global_styles_id,
				'post_content' => wp_slash(
					wp_json_encode(
						[
							'version'                   => 3,
							'isGlobalStylesUserThemeJSON' => true,
							'settings'                  => [
								'color'      => [
									'custom'  => false,
									'palette' => [
										[
											'name'  => 'サイトエディターの色',
											'slug'  => 'editor-custom-color',
											'color' => '#abcdef',
										],
										[
											'name'  => '古い追加色',
											'slug'  => 'ys-user-1',
											'color' => '#111111',
										],
										[
											'name'  => '削除済みの追加色',
											'slug'  => 'ys-user-2',
											'color' => '#222222',
										],
									],
								],
								'typography' => [ 'customFontSize' => false ],
							],
							'styles'                    => [ 'color' => [ 'text' => '#123456' ] ],
						]
					)
				),
			]
		);
		$stored_content = get_post( $this->global_styles_id )->post_content;
		WP_Theme_JSON_Resolver::clean_cached_data();
		$response = $this->get_global_styles_response();
		$data     = $response->get_data();

		$this->assertSame( 200, $response->get_status() );
		$palette = array_column( $data['settings']['color']['palette']['custom'], 'color', 'slug' );
		$this->assertSame( [ 'editor-custom-color' => '#abcdef', 'ys-user-1' => '#07689f80' ], $palette );
		$this->assertFalse( $data['settings']['color']['custom'] );
		$this->assertFalse( $data['settings']['typography']['customFontSize'] );
		$this->assertSame( '#123456', $data['styles']['color']['text'] );
		$this->assertSame( wp_get_global_settings( [ 'color', 'palette', 'custom' ] ), $data['settings']['color']['palette']['custom'] );
		$this->assertSame( $stored_content, get_post( $this->global_styles_id )->post_content );

		update_option( 'ys-color-palette-ys-user-1', '#ffffff' );
		$data    = $this->get_global_styles_response()->get_data();
		$palette = array_column( $data['settings']['color']['palette']['custom'], 'color', 'slug' );
		$this->assertSame( '#ffffff', $palette['ys-user-1'] );
		$this->assertCount( 2, $palette );

		update_option( 'ys-color-palette-ys-user-1', '' );
		$data    = $this->get_global_styles_response()->get_data();
		$palette = array_column( $data['settings']['color']['palette']['custom'], 'color', 'slug' );
		$this->assertSame( [ 'editor-custom-color' => '#abcdef' ], $palette );
		$this->assertSame( $stored_content, get_post( $this->global_styles_id )->post_content );
	}

	/**
	 * カスタマイザーで色を消した後に古いプリセットが残らないことを確認.
	 */
	public function test_unset_customizer_color_is_removed() {
		update_option( 'ys-color-palette-ys-user-1', '' );
		WP_Theme_JSON_Resolver::clean_cached_data();
		$palette = array_column( wp_get_global_settings( [ 'color', 'palette', 'custom' ] ), null, 'slug' );
		$data    = $this->get_global_styles_response()->get_data();

		$this->assertArrayNotHasKey( 'ys-user-1', $palette );
		$this->assertEquals( new stdClass(), $data['settings'] );
	}

	/**
	 * 他のテーマのユーザー設定に追加色を混ぜないことを確認.
	 */
	public function test_other_theme_global_styles_are_unchanged() {
		$post_id = self::factory()->post->create(
			[
				'post_type'    => 'wp_global_styles',
				'post_status'  => 'publish',
				'post_content' => '{"version":3,"isGlobalStylesUserThemeJSON":true}',
			]
		);
		wp_set_object_terms( $post_id, 'another-theme', 'wp_theme' );
		$request = new WP_REST_Request( 'GET', '/wp/v2/global-styles/' . $post_id );
		$request->set_param( 'context', 'edit' );
		$response = rest_do_request( $request );

		$this->assertSame( 200, $response->get_status() );
		$this->assertEquals( new stdClass(), $response->get_data()['settings'] );
	}

	/**
	 * settingsを要求しない取得では、不要なフィールドを増やさないことを確認.
	 */
	public function test_global_styles_fields_filter_is_preserved() {
		$response = $this->get_global_styles_response( [ '_fields' => 'styles' ] );

		$this->assertSame( 200, $response->get_status() );
		$this->assertArrayNotHasKey( 'settings', $response->get_data() );
	}

	/**
	 * 認証していない利用者へ編集用の色設定を返さないことを確認.
	 */
	public function test_global_styles_permission_check_is_preserved() {
		wp_set_current_user( 0 );
		$response = $this->get_global_styles_response();

		$this->assertSame( 401, $response->get_status() );
		$this->assertArrayNotHasKey( 'settings', $response->get_data() );
	}

	/**
	 * サイトエディターと同じGlobal Stylesのユーザー設定を取得.
	 *
	 * @param array $params 追加の取得条件.
	 * @return WP_REST_Response
	 */
	private function get_global_styles_response( $params = [] ) {
		$request = new WP_REST_Request( 'GET', '/wp/v2/global-styles/' . $this->global_styles_id );
		$request->set_query_params( array_merge( [ 'context' => 'edit' ], $params ) );

		return rest_do_request( $request );
	}
}
