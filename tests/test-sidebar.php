<?php
/**
 * Class SidebarTest
 *
 * @package ystandard
 */

/**
 * Class SidebarTest
 */
class SidebarTest extends WP_UnitTestCase {

	/**
	 * テスト終了処理.
	 */
	public function tear_down() {
		foreach ( [
			'ys_hide_sidebar_mobile',
			'ys_hide_post_sidebar_mobile',
			'ys_hide_post_archive_sidebar_mobile',
			'ys_hide_page_sidebar_mobile',
			'ys_hide_book_archive_sidebar_mobile',
		] as $option_name ) {
			delete_option( $option_name );
		}
		// テストで登録したカスタム投稿タイプを次のテストへ残さない.
		if ( post_type_exists( 'book' ) ) {
			unregister_post_type( 'book' );
		}
		foreach ( [ 'book_genre', 'shared_topic' ] as $taxonomy ) {
			// テストで登録したタクソノミーだけを後処理する.
			if ( taxonomy_exists( $taxonomy ) ) {
				unregister_taxonomy( $taxonomy );
			}
		}
		unset( $GLOBALS['wp_customize'] );
		parent::tear_down();
	}

	/**
	 * 新設定が未保存の場合は旧設定を引き継ぐことを確認.
	 */
	public function test_mobile_sidebar_setting_falls_back_to_legacy_option() {
		$post_id = self::factory()->post->create();
		$this->go_to( get_permalink( $post_id ) );
		update_option( 'ys_hide_sidebar_mobile', 1 );

		$this->assertTrue( \ystandard\Sidebar::is_hidden_on_mobile() );
	}

	/**
	 * 新設定のfalseが旧設定のtrueより優先されることを確認.
	 */
	public function test_explicit_new_setting_overrides_legacy_option() {
		$post_id = self::factory()->post->create();
		$this->go_to( get_permalink( $post_id ) );
		update_option( 'ys_hide_sidebar_mobile', 1 );
		update_option( 'ys_hide_post_sidebar_mobile', 0 );

		$this->assertFalse( \ystandard\Sidebar::is_hidden_on_mobile() );
	}

	/**
	 * 詳細ページの設定が投稿タイプごとに独立することを確認.
	 */
	public function test_singular_mobile_sidebar_setting_is_post_type_specific() {
		$post_id = self::factory()->post->create();
		$page_id = self::factory()->post->create( [ 'post_type' => 'page' ] );
		update_option( 'ys_hide_post_sidebar_mobile', 1 );
		update_option( 'ys_hide_page_sidebar_mobile', 0 );

		$this->go_to( get_permalink( $post_id ) );
		$this->assertTrue( \ystandard\Sidebar::is_hidden_on_mobile() );

		$this->go_to( get_permalink( $page_id ) );
		$this->assertFalse( \ystandard\Sidebar::is_hidden_on_mobile() );
	}

	/**
	 * 詳細ページとアーカイブの設定が独立することを確認.
	 */
	public function test_archive_mobile_sidebar_setting_is_independent() {
		self::factory()->post->create();
		update_option( 'ys_hide_post_sidebar_mobile', 0 );
		update_option( 'ys_hide_post_archive_sidebar_mobile', 1 );
		$this->go_to( home_url( '/' ) );

		$this->assertTrue( is_home() );
		$this->assertTrue( \ystandard\Sidebar::is_hidden_on_mobile() );
	}

	/**
	 * カスタム投稿タイプアーカイブの設定を使用することを確認.
	 */
	public function test_custom_post_type_archive_uses_own_setting() {
		register_post_type(
			'book',
			[
				'public'      => true,
				'has_archive' => true,
			]
		);
		self::factory()->post->create( [ 'post_type' => 'book' ] );
		update_option( 'ys_hide_book_archive_sidebar_mobile', 1 );
		$this->go_to( home_url( '/?post_type=book' ) );

		$this->assertTrue( is_post_type_archive( 'book' ) );
		$this->assertTrue( \ystandard\Sidebar::is_hidden_on_mobile() );
	}

	/**
	 * 1つの投稿タイプに属するタクソノミーで対象設定を使用することを確認.
	 */
	public function test_single_post_type_taxonomy_uses_archive_setting() {
		register_post_type( 'book', [ 'public' => true ] );
		register_taxonomy(
			'book_genre',
			'book',
			[
				'public'  => true,
				'rewrite' => false,
			]
		);
		wp_insert_term( '小説', 'book_genre', [ 'slug' => 'novel' ] );
		update_option( 'ys_hide_book_archive_sidebar_mobile', 1 );
		$this->go_to( home_url( '/?taxonomy=book_genre&term=novel' ) );

		$this->assertTrue( is_tax( 'book_genre', 'novel' ) );
		$this->assertTrue( \ystandard\Sidebar::is_hidden_on_mobile() );
	}

	/**
	 * 複数投稿タイプに属するタクソノミーは旧設定を使用することを確認.
	 */
	public function test_multiple_post_type_taxonomy_uses_legacy_setting() {
		register_post_type( 'book', [ 'public' => true ] );
		register_taxonomy(
			'shared_topic',
			[ 'post', 'book' ],
			[
				'public'  => true,
				'rewrite' => false,
			]
		);
		wp_insert_term( '共通', 'shared_topic', [ 'slug' => 'common' ] );
		update_option( 'ys_hide_sidebar_mobile', 1 );
		update_option( 'ys_hide_post_archive_sidebar_mobile', 0 );
		update_option( 'ys_hide_book_archive_sidebar_mobile', 0 );
		$this->go_to( home_url( '/?taxonomy=shared_topic&term=common' ) );

		$this->assertTrue( is_tax( 'shared_topic', 'common' ) );
		$this->assertTrue( \ystandard\Sidebar::is_hidden_on_mobile() );
	}

	/**
	 * 投稿タイプを判定できないページは旧設定を使用することを確認.
	 */
	public function test_unknown_context_uses_legacy_option() {
		update_option( 'ys_hide_sidebar_mobile', 1 );
		update_option( 'ys_hide_post_archive_sidebar_mobile', 0 );
		$this->go_to( home_url( '/not-found-page/' ) );

		$this->assertTrue( is_404() );
		$this->assertTrue( \ystandard\Sidebar::is_hidden_on_mobile() );
	}

	/**
	 * カスタマイザープレビューが共通の設定判定を使用することを確認.
	 */
	public function test_customizer_preview_uses_current_post_type_setting() {
		$post_id = self::factory()->post->create();
		$this->go_to( get_permalink( $post_id ) );
		update_option( 'ys_hide_sidebar_mobile', 1 );

		$GLOBALS['wp_customize'] = new WP_Customize_Manager();
		$GLOBALS['wp_customize']->start_previewing_theme();
		$customizer = ( new ReflectionClass( \ystandard\Customizer::class ) )->newInstanceWithoutConstructor();
		$css        = $customizer->preview_inline_css( '' );

		$this->assertStringContainsString( '.is-customize-preview .sidebar {display:none;}', $css );

		update_option( 'ys_hide_post_sidebar_mobile', 0 );
		$css = $customizer->preview_inline_css( '' );
		$this->assertStringNotContainsString( '.is-customize-preview .sidebar {display:none;}', $css );
	}

	/**
	 * 既存の事前判定フィルターが最優先されることを確認.
	 */
	public function test_sidebar_pre_filter_keeps_highest_priority() {
		$callback = '__return_true';
		add_filter( 'ys_pre_is_active_sidebar', $callback );
		update_option( 'ys_hide_sidebar_mobile', 1 );

		$this->assertTrue( \ystandard\Widget::is_active_sidebar() );

		remove_filter( 'ys_pre_is_active_sidebar', $callback );
	}
}
