<?php
/**
 * 投稿設定メタのテスト.
 *
 * @package ystandard
 */

/**
 * Class PostMetaTest
 */
class PostMetaTest extends WP_UnitTestCase {

	/**
	 * セットアップ.
	 */
	public function set_up() {
		parent::set_up();

		$post_meta = new \ystandard\Post_Meta();
		$post_meta->register_post_meta();
	}

	/**
	 * 投稿設定メタがREST対応のオブジェクトとして登録されることを確認する.
	 */
	public function test_register_post_settings_meta() {
		$registered = get_registered_meta_keys( 'post', 'post' );

		$this->assertArrayHasKey( \ystandard\Post_Meta::META_KEY, $registered );
		$this->assertSame( 'object', $registered[ \ystandard\Post_Meta::META_KEY ]['type'] );
		$this->assertTrue( $registered[ \ystandard\Post_Meta::META_KEY ]['single'] );
		$this->assertIsArray( $registered[ \ystandard\Post_Meta::META_KEY ]['show_in_rest'] );
	}

	/**
	 * テーマ設定と空文字を保存せず、versionだけを維持することを確認する.
	 */
	public function test_sanitize_settings_compacts_default_values() {
		$actual = \ystandard\Post_Meta::sanitize_settings(
			[
				'version'         => 1,
				'toc'             => 'default',
				'share_buttons'   => 'off',
				'author'          => 'on',
				'ogp_title'       => ' ',
				'ogp_description' => "\n<strong>説明</strong>",
				'unknown'         => 'value',
			]
		);

		$this->assertSame(
			[
				'version'              => 1,
				'share_buttons_header' => 'off',
				'share_buttons_footer' => 'off',
				'author'               => 'on',
				'ogp_description'      => '説明',
			],
			$actual
		);
	}

	/**
	 * 新形式がない場合に旧11キーを実行時変換することを確認する.
	 */
	public function test_legacy_settings_are_converted_without_migration() {
		$post_id = self::factory()->post->create();
		update_post_meta( $post_id, 'ys_hide_toc', '1' );
		update_post_meta( $post_id, 'ys_hide_share', '1' );
		update_post_meta( $post_id, 'ys_noindex', '1' );
		update_post_meta( $post_id, 'ys_ogp_title', '<b>旧タイトル</b>' );

		$this->assertSame(
			[
				'version'              => 1,
				'toc'                  => 'off',
				'share_buttons_header' => 'off',
				'share_buttons_footer' => 'off',
				'noindex'              => 'on',
				'ogp_title'            => '旧タイトル',
			],
			\ystandard\Post_Meta::get_settings( $post_id )
		);
		$this->assertSame( '', get_post_meta( $post_id, \ystandard\Post_Meta::META_KEY, true ) );
	}

	/**
	 * versionだけの新形式が旧設定より優先されることを確認する.
	 */
	public function test_new_settings_do_not_fall_back_per_property() {
		$post_id = self::factory()->post->create();
		$toc_meta_id   = add_post_meta( $post_id, 'ys_hide_toc', '1' );
		$title_meta_id = add_post_meta( $post_id, 'ys_ogp_title', '旧タイトル' );
		update_post_meta(
			$post_id,
			\ystandard\Post_Meta::META_KEY,
			[ 'version' => 1 ]
		);

		$this->assertSame( [ 'version' => 1 ], \ystandard\Post_Meta::get_settings( $post_id ) );
		$this->assertTrue( \ystandard\Post_Meta::resolve_state( 'toc', true, $post_id ) );
		$this->assertSame( '', \ystandard\Post_Meta::get_string( 'ogp_title', $post_id ) );
		$this->assertSame( '', get_post_meta( $post_id, 'ys_hide_toc', true ) );
		$this->assertSame( '', get_post_meta( $post_id, 'ys_ogp_title', true ) );
		$this->assertSame( '1', get_metadata_by_mid( 'post', $toc_meta_id )->meta_value );
		$this->assertSame( '旧タイトル', get_metadata_by_mid( 'post', $title_meta_id )->meta_value );
	}

	/**
	 * 旧キーを読む拡張機能へ新形式の値を返すことを確認する.
	 */
	public function test_legacy_meta_read_is_compatible_with_new_settings() {
		$post_id = self::factory()->post->create();
		update_post_meta(
			$post_id,
			\ystandard\Post_Meta::META_KEY,
			[
				'version'   => 1,
				'toc'       => 'off',
				'ogp_title' => '新タイトル',
			]
		);

		$this->assertSame( '1', get_post_meta( $post_id, 'ys_hide_toc', true ) );
		$this->assertSame( '新タイトル', get_post_meta( $post_id, 'ys_ogp_title', true ) );
	}

	/**
	 * 旧シェアボタン非表示設定は上下ともOFFの場合だけ有効になることを確認する.
	 */
	public function test_legacy_share_button_meta_requires_both_positions_to_be_off() {
		$post_id = self::factory()->post->create();
		update_post_meta(
			$post_id,
			\ystandard\Post_Meta::META_KEY,
			[
				'version'              => 1,
				'share_buttons_header' => 'off',
				'share_buttons_footer' => 'on',
			]
		);

		$this->assertSame( '', get_post_meta( $post_id, 'ys_hide_share', true ) );

		update_post_meta(
			$post_id,
			\ystandard\Post_Meta::META_KEY,
			[
				'version'              => 1,
				'share_buttons_header' => 'off',
				'share_buttons_footer' => 'off',
			]
		);

		$this->assertSame( '1', get_post_meta( $post_id, 'ys_hide_share', true ) );
	}

	/**
	 * 投稿単位のON・OFFがテーマ設定より優先されることを確認する.
	 */
	public function test_resolve_state_uses_explicit_post_setting() {
		$post_id = self::factory()->post->create();
		update_post_meta(
			$post_id,
			\ystandard\Post_Meta::META_KEY,
			[
				'version' => 1,
				'author'  => 'off',
				'noindex' => 'on',
			]
		);

		$this->assertFalse( \ystandard\Post_Meta::resolve_state( 'author', true, $post_id ) );
		$this->assertTrue( \ystandard\Post_Meta::resolve_state( 'noindex', false, $post_id ) );
	}

	/**
	 * 投稿専用プロパティが固定ページのRESTスキーマに含まれないことを確認する.
	 */
	public function test_page_schema_excludes_post_only_fields() {
		$post_schema = \ystandard\Post_Meta::get_rest_schema( 'post' );
		$page_schema = \ystandard\Post_Meta::get_rest_schema( 'page' );

		$this->assertArrayHasKey( 'related_posts', $post_schema['properties'] );
		$this->assertArrayHasKey( 'paging', $post_schema['properties'] );
		$this->assertArrayHasKey( 'post_thumbnail', $post_schema['properties'] );
		$this->assertArrayHasKey( 'header_taxonomy', $post_schema['properties'] );
		$this->assertArrayHasKey( 'footer_taxonomy', $post_schema['properties'] );
		$this->assertArrayNotHasKey( 'related_posts', $page_schema['properties'] );
		$this->assertArrayNotHasKey( 'paging', $page_schema['properties'] );
		$this->assertArrayHasKey( 'post_thumbnail', $page_schema['properties'] );
		$this->assertArrayNotHasKey( 'header_taxonomy', $page_schema['properties'] );
		$this->assertArrayNotHasKey( 'footer_taxonomy', $page_schema['properties'] );
		$this->assertSame( [ 'version' ], $page_schema['required'] );
	}

	/**
	 * アーカイブを持つカスタム投稿タイプで関連記事と前後記事を利用できることを確認する.
	 */
	public function test_archive_post_type_schema_includes_related_and_paging_fields() {
		register_post_type(
			'book',
			[
				'public'       => true,
				'show_in_rest' => true,
				'has_archive'  => true,
				'supports'     => [ 'editor', 'custom-fields', 'thumbnail' ],
			]
		);
		register_taxonomy_for_object_type( 'category', 'book' );

		$schema    = \ystandard\Post_Meta::get_rest_schema( 'book' );
		$available = \ystandard\Post_Meta::get_available_state_fields( 'book' );
		$settings  = \ystandard\Block_Editor_Post_Settings::get_theme_settings( 'book' );

		$this->assertArrayHasKey( 'related_posts', $schema['properties'] );
		$this->assertArrayHasKey( 'paging', $schema['properties'] );
		$this->assertArrayHasKey( 'related_posts', $settings );
		$this->assertArrayHasKey( 'paging', $settings );
		$this->assertContains( 'post_thumbnail', $available );
		$this->assertContains( 'header_taxonomy', $available );
		$this->assertContains( 'footer_taxonomy', $available );

		unregister_taxonomy_for_object_type( 'category', 'book' );
		unregister_post_type( 'book' );
	}

	/**
	 * テーマ設定の説明とシェアボタン設定が現在のUI形式で返ることを確認する.
	 */
	public function test_theme_setting_descriptions_use_current_ui_format() {
		$settings = \ystandard\Block_Editor_Post_Settings::get_theme_settings( 'post' );

		$this->assertArrayHasKey( 'share_buttons_header', $settings );
		$this->assertArrayHasKey( 'share_buttons_footer', $settings );
		$this->assertArrayHasKey( 'post_thumbnail', $settings );
		$this->assertArrayHasKey( 'header_taxonomy', $settings );
		$this->assertArrayHasKey( 'footer_taxonomy', $settings );
		$this->assertStringStartsWith( '「-」を選択した場合、', $settings['publish_date']['description'] );
		$this->assertSame( '「-」を選択した場合、noindexを出力しません。', $settings['noindex']['description'] );
		$this->assertStringNotContainsString( 'テーマ設定：', wp_json_encode( $settings ) );
	}

	/**
	 * 本文背景色は投稿タイプ別設定を優先し、未保存の場合だけ従来設定へ戻ることを確認する.
	 */
	public function test_content_background_uses_post_type_setting_before_fallback() {
		update_option( 'ys_post_use_content_bg', 1 );
		update_option( 'ys_post_content_bg', '#111111' );

		$this->assertSame( '#111111', \ystandard\Post_Content::get_content_background_color( 'book' ) );

		update_option( 'ys_book_use_content_bg', 1 );
		update_option( 'ys_book_content_bg', '#222222' );

		$this->assertSame( '#222222', \ystandard\Post_Content::get_content_background_color( 'book' ) );
	}

	/**
	 * REST APIから新形式を保存し、サニタイズと圧縮が適用されることを確認する.
	 */
	public function test_post_settings_can_be_updated_via_rest_api() {
		$post_id = self::factory()->post->create();
		$admin   = self::factory()->user->create( [ 'role' => 'administrator' ] );
		wp_set_current_user( $admin );
		do_action( 'rest_api_init' );

		$request = new WP_REST_Request( 'POST', "/wp/v2/posts/{$post_id}" );
		$request->set_param(
			'meta',
			[
				\ystandard\Post_Meta::META_KEY => [
					'version'         => 1,
					'toc'             => 'off',
					'ogp_title'       => '<b>タイトル</b>',
					'ogp_description' => '',
				],
			]
		);
		$response = rest_get_server()->dispatch( $request );

		$this->assertSame( 200, $response->get_status() );
		$this->assertSame(
			[
				'version'   => 1,
				'toc'       => 'off',
				'ogp_title' => 'タイトル',
			],
			get_post_meta( $post_id, \ystandard\Post_Meta::META_KEY, true )
		);
	}
}
