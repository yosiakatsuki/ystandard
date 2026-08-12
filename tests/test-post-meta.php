<?php
/**
 * Class PostMetaTest
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

		$this->get_post_meta_instance()->register_post_meta();
	}

	/**
	 * 投稿用メタがREST APIへ登録されることを確認.
	 */
	public function test_post_meta_is_registered_for_post() {
		$registered = get_registered_meta_keys( 'post', 'post' );

		$this->assertCount( 11, array_intersect_key( $registered, \ystandard\Post_Meta::get_meta_fields() ) );
		$this->assertSame( 'boolean', $registered['ys_noindex']['type'] );
		$this->assertTrue( $registered['ys_noindex']['single'] );
		$this->assertTrue( $registered['ys_noindex']['show_in_rest'] );
		$this->assertSame( 'string', $registered['ys_ogp_title']['type'] );
	}

	/**
	 * 固定ページでは投稿専用メタを登録しないことを確認.
	 */
	public function test_post_only_meta_is_not_registered_for_page() {
		$registered = get_registered_meta_keys( 'post', 'page' );

		$this->assertArrayHasKey( 'ys_hide_ad', $registered );
		$this->assertArrayNotHasKey( 'ys_hide_related', $registered );
		$this->assertArrayNotHasKey( 'ys_hide_paging', $registered );
	}

	/**
	 * 既存値と文字列入力を登録済みコールバックでサニタイズできることを確認.
	 */
	public function test_registered_meta_sanitization() {
		$this->assertTrue( sanitize_meta( 'ys_noindex', '1', 'post', 'post' ) );
		$this->assertFalse( sanitize_meta( 'ys_noindex', 'false', 'post', 'post' ) );
		$this->assertSame( 'OGP Title', sanitize_meta( 'ys_ogp_title', '<b>OGP Title</b>', 'post', 'post' ) );
		$this->assertSame(
			'Line one Line two',
			sanitize_meta( 'ys_ogp_description', "Line one\n<strong>Line two</strong>", 'post', 'post' )
		);
	}

	/**
	 * 投稿編集権限でREST APIのメタ更新を制御することを確認.
	 */
	public function test_post_meta_authorization() {
		$post_id = self::factory()->post->create();
		$admin   = self::factory()->user->create( [ 'role' => 'administrator' ] );
		$user    = self::factory()->user->create( [ 'role' => 'subscriber' ] );

		wp_set_current_user( $admin );
		$this->assertTrue( \ystandard\Post_Meta::can_edit_post_meta( false, 'ys_noindex', $post_id ) );

		wp_set_current_user( $user );
		$this->assertFalse( \ystandard\Post_Meta::can_edit_post_meta( false, 'ys_noindex', $post_id ) );
	}

	/**
	 * REST APIから既存メタキーを更新できることを確認.
	 */
	public function test_post_meta_can_be_updated_via_rest_api() {
		$post_id = self::factory()->post->create();
		$admin   = self::factory()->user->create( [ 'role' => 'administrator' ] );
		wp_set_current_user( $admin );
		do_action( 'rest_api_init' );

		$request = new WP_REST_Request( 'POST', "/wp/v2/posts/{$post_id}" );
		$request->set_param(
			'meta',
			[
				'ys_noindex'         => true,
				'ys_ogp_title'       => '<b>OGP Title</b>',
				'ys_ogp_description' => "Line one\n<strong>Line two</strong>",
			]
		);
		$response = rest_get_server()->dispatch( $request );

		$this->assertSame( 200, $response->get_status() );
		$this->assertSame( '1', get_post_meta( $post_id, 'ys_noindex', true ) );
		$this->assertSame( 'OGP Title', get_post_meta( $post_id, 'ys_ogp_title', true ) );
		$this->assertSame( 'Line one Line two', get_post_meta( $post_id, 'ys_ogp_description', true ) );
	}

	/**
	 * ブロックエディター対象では従来メタボックスを互換用にすることを確認.
	 */
	public function test_legacy_meta_box_is_marked_for_back_compat() {
		global $wp_meta_boxes;

		$wp_meta_boxes = [];
		$this->get_post_meta_instance()->add_meta_box();
		$args = $wp_meta_boxes['post']['side']['default']['ys_post_option']['args'];

		$this->assertTrue( $args['__back_compat_meta_box'] );
	}

	/**
	 * ブロックエディター用スクリプトの依存関係を確認.
	 */
	public function test_block_editor_post_meta_script_is_enqueued() {
		$GLOBALS['wp_scripts'] = null;
		wp_scripts();
		set_current_screen( 'post' );

		try {
			do_action( 'enqueue_block_editor_assets' );
			$handle = \ystandard\Block_Editor_Post_Meta::SCRIPT_HANDLE;
			$script = wp_scripts()->registered[ $handle ];
		} finally {
			set_current_screen( 'front' );
		}

		$this->assertTrue( wp_script_is( $handle, 'enqueued' ) );
		$this->assertContains( 'wp-core-data', $script->deps );
		$this->assertContains( 'wp-editor', $script->deps );
		$this->assertContains( 'wp-element', $script->deps );
		$this->assertNotContains( 'react-jsx-runtime', $script->deps );
	}

	/**
	 * Post_Metaインスタンスを取得.
	 *
	 * @return \ystandard\Post_Meta
	 */
	private function get_post_meta_instance() {
		global $wp_filter;

		$callbacks = $wp_filter['init']->callbacks[20];
		foreach ( $callbacks as $callback ) {
			$function = $callback['function'];
			if (
				is_array( $function ) &&
				$function[0] instanceof \ystandard\Post_Meta &&
				'register_post_meta' === $function[1]
			) {
				return $function[0];
			}
		}

		$this->fail( 'Post_Metaインスタンスを取得できません。' );
	}
}
