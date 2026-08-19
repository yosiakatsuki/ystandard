<?php
/**
 * Class BlogCardTest
 *
 * @package ystandard
 */

/**
 * yStandard Blocks連携の有無を判定するためのテスト用クラス.
 */
class Blog_Card_Blocks_Stub {

	/**
	 * Blocks側へ委譲されたことを示すHTMLを返す.
	 *
	 * @return string
	 */
	public function render() {
		return '<div class="ystdb-card-stub">Blocks output</div>';
	}
}

/**
 * Class BlogCardTest
 */
class BlogCardTest extends WP_UnitTestCase {

	/**
	 * テスト終了処理.
	 */
	public function tear_down() {
		remove_all_filters( 'ys_cache_count_key__blog_card' );
		remove_all_filters( 'ys_cache_delete_key__blog_card' );
		parent::tear_down();
	}

	/**
	 * 既存ショートコードがテーマ本体のHTMLを出力することを確認.
	 */
	public function test_shortcode_uses_theme_renderer() {
		// Blocks有効時に旧委譲処理が復活していないことを同じ条件で確認する.
		if ( ! class_exists( 'ystandard_blocks\Card_Block' ) ) {
			class_alias( Blog_Card_Blocks_Stub::class, 'ystandard_blocks\Card_Block' );
		}

		$post_id = self::factory()->post->create(
			[
				'post_title'   => 'ブログカードテスト',
				'post_excerpt' => 'ブログカードの概要',
			]
		);
		$url       = get_permalink( $post_id );
		$blog_card = new \ystandard\Blog_Card();
		$html      = $blog_card->do_shortcode(
			[
				'url'        => $url,
				'show_image' => false,
			]
		);

		$this->assertTrue( shortcode_exists( 'ys_blog_card' ) );
		$this->assertIsCallable( $GLOBALS['shortcode_tags']['ys_blog_card'] );
		$this->assertSame( $post_id, url_to_postid( $url ) );
		$this->assertNotSame( '', $html );
		$this->assertStringContainsString( 'class="ys-blog-card"', $html );
		$this->assertStringContainsString( 'ブログカードテスト', $html );
		$this->assertStringContainsString( 'ブログカードの概要', $html );
		$this->assertStringNotContainsString( 'ystdb-card-stub', $html );
	}

	/**
	 * 自動変換とエディター用Embed処理が削除されていることを確認.
	 */
	public function test_embed_handler_methods_are_removed() {
		$this->assertFalse( method_exists( \ystandard\Blog_Card::class, 'embed_register_handler' ) );
		$this->assertFalse( method_exists( \ystandard\Blog_Card::class, 'blog_card_handler' ) );
		$this->assertFalse( method_exists( \ystandard\Blog_Card::class, 'get_admin_blog_card' ) );
		$this->assertFalse( method_exists( \ystandard\Blog_Card::class, 'customize_register' ) );
		$this->assertFalse( class_exists( \ystandard\Embed::class ) );
		$this->assertFalse( function_exists( 'ys_embed_content' ) );

		global $wp_embed;
		$this->assertStringNotContainsString( 'ys_blog_card', wp_json_encode( $wp_embed->handlers ) );
	}

	/**
	 * キャッシュ管理がBlocks側のキーへ置き換わらないことを確認.
	 */
	public function test_cache_management_uses_theme_cache_key() {
		$args = [ 'url' => 'https://example.com/blog-card-cache-test' ];
		\ystandard\Cache::set_cache(
			\ystandard\Blog_Card::CACHE_KEY,
			[ 'title' => 'キャッシュテスト' ],
			$args,
			1,
			true
		);

		$replace_cache_key = function () {
			return 'ystdb_card';
		};
		add_filter( 'ys_cache_count_key__blog_card', $replace_cache_key, 10, 3 );
		add_filter( 'ys_cache_delete_key__blog_card', $replace_cache_key, 10, 3 );

		$admin      = ( new ReflectionClass( \ystandard\Admin_Menu::class ) )->newInstanceWithoutConstructor();
		$get_count  = new ReflectionMethod( $admin, 'get_cache_count' );
		$delete     = new ReflectionMethod( $admin, 'delete_cache_data' );
		$cache_data = \ystandard\Cache::get_cache( \ystandard\Blog_Card::CACHE_KEY, $args );

		$get_count->setAccessible( true );
		$delete->setAccessible( true );

		$this->assertSame( 'キャッシュテスト', $cache_data['title'] );
		$this->assertSame( 1, (int) $get_count->invoke( $admin, \ystandard\Blog_Card::CACHE_KEY ) );
		$this->assertSame( 1, $delete->invoke( $admin, \ystandard\Blog_Card::CACHE_KEY ) );
		$this->assertSame( 0, (int) $get_count->invoke( $admin, \ystandard\Blog_Card::CACHE_KEY ) );
		\ystandard\Cache::delete_cache( \ystandard\Blog_Card::CACHE_KEY, $args );
	}
}
