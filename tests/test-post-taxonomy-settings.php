<?php
/**
 * 投稿詳細ページのタクソノミー設定テスト.
 *
 * @package ystandard
 */

/**
 * Class PostTaxonomySettingsTest
 */
class PostTaxonomySettingsTest extends WP_UnitTestCase {

	/**
	 * テスト終了処理.
	 */
	public function tear_down() {
		foreach ( [
			'ys_post_header_taxonomy',
			'ys_post_footer_taxonomy_category',
			'ys_post_footer_taxonomy_post_tag',
		] as $option_name ) {
			delete_option( $option_name );
		}
		wp_reset_postdata();
		parent::tear_down();
	}

	/**
	 * カテゴリーとタグを持つ投稿を作成する.
	 *
	 * @return array
	 */
	private function create_post_with_taxonomies() {
		$post_id  = self::factory()->post->create();
		$category = self::factory()->category->create_and_get( [ 'name' => 'テストカテゴリー' ] );
		$tag      = self::factory()->tag->create_and_get( [ 'name' => 'テストタグ' ] );
		wp_set_post_terms( $post_id, [ $category->term_id ], 'category' );
		wp_set_post_terms( $post_id, [ $tag->term_id ], 'post_tag' );
		$this->go_to( get_permalink( $post_id ) );
		$GLOBALS['post'] = get_post( $post_id );
		setup_postdata( $GLOBALS['post'] );

		return [ $post_id, $category, $tag ];
	}

	/**
	 * 本文上部ではカスタマイザーで選択したタクソノミーだけを表示することを確認する.
	 */
	public function test_header_taxonomy_uses_selected_customizer_setting() {
		[ , $category, $tag ] = $this->create_post_with_taxonomies();
		update_option( 'ys_post_header_taxonomy', 'category' );

		$output = \ystandard\Post_Header::get_post_header_category();

		$this->assertStringContainsString( $category->name, $output );
		$this->assertStringNotContainsString( $tag->name, $output );
	}

	/**
	 * 本文上部の投稿単位設定でテーマ設定を上書きできることを確認する.
	 */
	public function test_header_taxonomy_uses_post_setting_override() {
		[ $post_id, $category ] = $this->create_post_with_taxonomies();
		update_option( 'ys_post_header_taxonomy', 'none' );
		update_post_meta(
			$post_id,
			\ystandard\Post_Meta::META_KEY,
			[
				'version'         => 1,
				'header_taxonomy' => 'on',
			]
		);

		$this->assertStringContainsString( $category->name, \ystandard\Post_Header::get_post_header_category() );

		update_post_meta(
			$post_id,
			\ystandard\Post_Meta::META_KEY,
			[
				'version'         => 1,
				'header_taxonomy' => 'off',
			]
		);
		update_option( 'ys_post_header_taxonomy', 'category' );

		$this->assertSame( '', \ystandard\Post_Header::get_post_header_category() );
	}

	/**
	 * 本文下部ではカスタマイザーで選択したタクソノミーだけを返すことを確認する.
	 */
	public function test_footer_taxonomy_uses_customizer_settings() {
		$this->create_post_with_taxonomies();
		update_option( 'ys_post_footer_taxonomy_category', 1 );
		update_option( 'ys_post_footer_taxonomy_post_tag', 0 );

		$data = \ystandard\Taxonomy::get_the_taxonomies_data();

		$this->assertArrayHasKey( 'category', $data );
		$this->assertArrayNotHasKey( 'post_tag', $data );
	}

	/**
	 * 本文下部の投稿単位設定でテーマ設定を上書きできることを確認する.
	 */
	public function test_footer_taxonomy_uses_post_setting_override() {
		[ $post_id ] = $this->create_post_with_taxonomies();
		update_option( 'ys_post_footer_taxonomy_category', 0 );
		update_option( 'ys_post_footer_taxonomy_post_tag', 0 );
		update_post_meta(
			$post_id,
			\ystandard\Post_Meta::META_KEY,
			[
				'version'         => 1,
				'footer_taxonomy' => 'on',
			]
		);

		$data = \ystandard\Taxonomy::get_the_taxonomies_data();
		$this->assertArrayHasKey( 'category', $data );
		$this->assertArrayHasKey( 'post_tag', $data );

		update_post_meta(
			$post_id,
			\ystandard\Post_Meta::META_KEY,
			[
				'version'         => 1,
				'footer_taxonomy' => 'off',
			]
		);

		$this->assertSame( [], \ystandard\Taxonomy::get_the_taxonomies_data() );
	}
}
