<?php
/**
 * Class ConditionalTagTest
 *
 * @package ystandard
 */

/**
 * AMP用テスト
 */
class ConditionalTagTest extends WP_UnitTestCase {

	/**
	 * Test: ys_is_top_page
	 */
	function test_ys_is_top_page_home_1() {
		$post_id = $this->factory->post->create();
		$this->go_to( home_url( '/' ) );
		$this->assertTrue( \ystandard\Template::is_top_page() );
	}

	/**
	 * Test: ys_is_top_page 2
	 */
	function test_ys_is_top_page_home_2() {
		$post_id = $this->factory->post->create();
		$post_id = $this->factory->post->create();
		$post_id = $this->factory->post->create();
		$post_id = $this->factory->post->create();
		/**
		 * ページ設定
		 */
		update_option( 'posts_per_page', 1 );
		/**
		 * 2ページ目へ移動
		 */
		$this->go_to( home_url( '/' ) );
		$this->go_to( get_pagenum_link( 2 ) );
		$this->assertFalse( \ystandard\Template::is_top_page() );
	}

	/**
	 * Test: ys_is_top_page
	 */
	function test_ys_is_top_page_front() {
		$post_id = $this->factory->post->create(
			[
				'post_type' => 'page',
			]
		);
		update_option( 'show_on_front', 'page' );
		update_option( 'page_on_front', $post_id );
		$this->go_to( home_url( '/' ) );
		$this->assertTrue( \ystandard\Template::is_top_page() );
	}

	/**
	 * Test: ys_is_no_title_template
	 */
	function test_ys_is_no_title_template_select() {
		$post_id = $this->factory->post->create( [ 'post_type' => 'page' ] );
		update_post_meta( $post_id, '_wp_page_template', 'page-template/template-blank.php' );
		$this->go_to( "/?page_id=$post_id" );
		$this->assertTrue( ys_is_no_title_template() );

		update_post_meta( $post_id, '_wp_page_template', 'page-template/template-blank-wide.php' );
		$this->assertTrue( ys_is_no_title_template() );
	}

	/**
	 * Test: ys_is_no_title_template
	 */
	function test_ys_is_no_title_template_no_select() {
		$post_id = $this->factory->post->create( [ 'post_type' => 'page' ] );
		$this->go_to( "/?page_id=$post_id" );
		$this->assertFalse( ys_is_no_title_template() );
	}

	/**
	 * Test:Template::is_single_front_page
	 */
	function test_single_top_page_site_default() {
		$post_id = $this->factory->post->create( [ 'post_type' => 'page' ] );
		$post_id = $this->factory->post->create();
		$this->go_to( '/' );
		$this->assertFalse( ystandard\Template::is_single_front_page() );
	}

	/**
	 * Test:Template::is_single_front_page
	 */
	function test_single_top_page_set_front_page() {
		$post_id = $this->factory->post->create( [ 'post_type' => 'page' ] );
		update_option( 'show_on_front', 'page' );
		update_option( 'page_on_front', $post_id );
		$this->go_to( "/?page_id=$post_id" );
		$this->assertTrue( ystandard\Template::is_single_front_page() );
	}

	/**
	 * Test:Template::is_single_front_page
	 */
	function test_single_top_page_set_front_go_to_home() {
		$post_id      = $this->factory->post->create( [ 'post_type' => 'page' ] );
		$post_id_home = $this->factory->post->create( [ 'post_type' => 'page' ] );
		update_option( 'show_on_front', 'page' );
		update_option( 'page_on_front', $post_id );
		update_option( 'page_for_posts', $post_id_home );
		$this->go_to( "/?page_id=$post_id_home" );
		$this->assertFalse( ystandard\Template::is_single_front_page() );
	}
}
