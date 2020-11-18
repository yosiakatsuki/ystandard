<?php
/**
 * Class NoindexTest
 *
 * @package ystandard
 */

/**
 * Class NoindexTest
 */
class NoindexTest extends WP_UnitTestCase {

	/**
	 * 変数
	 *
	 * @var \ystandard\No_Index
	 */
	public $noindex;

	/**
	 * NoindexTest constructor.
	 */
	public function __construct() {
		parent::__construct();
		$this->noindex = new \ystandard\No_Index();
	}

	/**
	 * Home URLのnoindex
	 */
	function test_home_page_noindex() {
		$post_id = $this->factory->post->create();
		$this->go_to( home_url( '/' ) );
		$this->assertFalse( $this->noindex->is_noindex() );
	}

	/**
	 * FrontPageのnoindex
	 */
	function test_front_page_noindex() {
		$post_id = $this->factory->post->create(
			[
				'post_type' => 'page',
			]
		);
		update_option( 'show_on_front', 'page' );
		update_option( 'page_on_front', $post_id );
		$this->go_to( home_url( '/' ) );
		$this->assertFalse( $this->noindex->is_noindex() );
	}

	/**
	 * 投稿ページのnoindex
	 */
	public function test_post_noindex() {
		$post_id = self::factory()->post->create(
			[
				'post_type'    => 'post',
				'post_content' => 'あいうえお',
			]
		);
		$this->go_to( get_permalink( $post_id ) );

		$this->assertFalse( $this->noindex->is_noindex() );
	}

	/**
	 * 投稿ページのnoindex
	 */
	public function test_post_disable_noindex() {
		$args    = [
			'post_type'    => 'post',
			'post_content' => 'あいうえお',
		];
		$post_id = self::factory()->post->create( $args );
		update_post_meta( $post_id, 'ys_noindex', '1' );
		$this->go_to( get_permalink( $post_id ) );

		$this->assertTrue( $this->noindex->is_noindex() );
	}

	/**
	 * 固定ページnoindex(false)
	 */
	function test_page_noindex() {
		$post_id = self::factory()->post->create(
			[
				'post_type'    => 'post',
				'post_content' => 'あいうえお',
			]
		);
		$this->go_to( get_permalink( $post_id ) );

		$this->assertFalse( $this->noindex->is_noindex() );
	}

	/**
	 * 固定ページnoindex(true)
	 */
	function test_page_disable_noindex() {
		$post_id = self::factory()->post->create(
			[
				'post_type'    => 'post',
				'post_content' => 'あいうえお',
			]
		);
		update_post_meta( $post_id, 'ys_noindex', '1' );
		$this->go_to( get_permalink( $post_id ) );

		$this->assertTrue( $this->noindex->is_noindex() );
	}

	/**
	 * 404ページのnoindex
	 */
	function test_404_noindex() {
		$post_id = $this->factory->post->create(
			[
				'post_type'    => 'post',
				'post_content' => 'あいうえお',
			]
		);
		$this->go_to( "/?p=${post_id}1234567890" );
		$this->assertTrue( $this->noindex->is_noindex() );
	}

	/**
	 * カテゴリー一覧ページのnoindex(true)
	 */
	function test_category_noindex() {
		update_option( 'ys_archive_noindex_category', '1' );
		$cat_id  = $this->factory->category->create( [ 'name' => 'Foo' ] );
		$post_id = $this->factory->post->create(
			[
				'post_type'     => 'post',
				'post_content'  => 'あいうえお',
				'post_category' => [ $cat_id ],
			]
		);
		$this->go_to( get_category_link( $cat_id ) );
		$this->assertTrue( $this->noindex->is_noindex() );
	}

	/**
	 * カテゴリー一覧ページのnoindex(false)
	 */
	function test_category_disable_noindex() {
		update_option( 'ys_archive_noindex_category', '0' );
		$cat_id  = $this->factory->category->create( [ 'name' => 'Foo' ] );
		$post_id = $this->factory->post->create(
			[
				'post_type'     => 'post',
				'post_content'  => 'あいうえお',
				'post_category' => [ $cat_id ],
			]
		);
		$this->go_to( get_category_link( $cat_id ) );
		$this->assertFalse( $this->noindex->is_noindex() );
	}

	/**
	 * タグ一覧ページのnoindex(true)
	 */
	function test_tag_noindex() {
		update_option( 'ys_archive_noindex_tag', '1' );
		$term_id = $this->factory->term->create(
			[
				'name'     => 'tag',
				'taxonomy' => 'post_tag',
			]
		);
		$post_id = $this->factory->post->create(
			[
				'post_type'    => 'post',
				'post_content' => 'あいうえお',
				'tags_input'   => [ $term_id ],
			]
		);
		$this->go_to( get_term_link( $term_id, 'post_tag' ) );
		$this->assertTrue( $this->noindex->is_noindex() );
	}

	/**
	 * カテゴリー一覧ページのnoindex(false)
	 */
	function test_tag_disable_noindex() {
		update_option( 'ys_archive_noindex_tag', '0' );
		$term_id = $this->factory->term->create(
			[
				'name'     => 'tag',
				'taxonomy' => 'post_tag',
			]
		);
		$post_id = $this->factory->post->create(
			[
				'post_type'    => 'post',
				'post_content' => 'あいうえお',
				'tags_input'   => [ $term_id ],
			]
		);
		$this->go_to( get_term_link( $term_id, 'post_tag' ) );
		$this->assertFalse( $this->noindex->is_noindex() );
	}

	/**
	 * 2ページ目以降のnoindex(false)
	 */
	function test_paged_noindex() {
		update_option( 'ys_archive_noindex_paged', '1' );
		$term_id = $this->factory->term->create(
			[
				'name'     => 'tag',
				'taxonomy' => 'post_tag',
			]
		);
		for ( $i = 0; $i < 5; $i ++ ) {
			$post_id = $this->factory->post->create(
				[
					'post_type'    => 'post',
					'post_content' => 'あいうえお',
					'tags_input'   => [ $term_id ],
				]
			);
		}
		update_option( 'posts_per_page', 1 );
		$this->go_to( home_url( '/' ) );
		$this->go_to( get_pagenum_link( 2 ) );
		$this->assertTrue( $this->noindex->is_noindex() );
		$this->go_to( get_term_link( $term_id, 'post_tag' ) );
		$this->go_to( get_pagenum_link( 2 ) );
		$this->assertTrue( $this->noindex->is_noindex() );
	}

	/**
	 * 2ページ目以降のnoindex(false)
	 */
	function test_paged_disable_noindex() {
		update_option( 'ys_archive_noindex_paged', '0' );
		$term_id = $this->factory->term->create(
			[
				'name'     => 'tag',
				'taxonomy' => 'post_tag',
			]
		);
		for ( $i = 0; $i < 5; $i ++ ) {
			$post_id = $this->factory->post->create(
				[
					'post_type'    => 'post',
					'post_content' => 'あいうえお',
					'tags_input'   => [ $term_id ],
				]
			);
		}
		update_option( 'posts_per_page', 1 );
		$this->go_to( home_url( '/' ) );
		$this->go_to( get_pagenum_link( 2 ) );
		$this->assertFalse( $this->noindex->is_noindex() );
		$this->go_to( get_term_link( $term_id, 'post_tag' ) );
		$this->go_to( get_pagenum_link( 2 ) );
		$this->assertTrue( $this->noindex->is_noindex() );
		update_option( 'ys_archive_noindex_tag', '0' );
		$this->go_to( get_term_link( $term_id, 'post_tag' ) );
		$this->go_to( get_pagenum_link( 2 ) );
		$this->assertFalse( $this->noindex->is_noindex() );
	}
}
