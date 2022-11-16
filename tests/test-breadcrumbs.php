<?php
/**
 * Class Breadcrumbs Test
 *
 * @package ystandard
 */

use ystandard\Breadcrumbs;

/**
 * Class Breadcrumbs Test
 */
class BreadcrumbsTest extends WP_UnitTestCase {

	/**
	 * Test: is_show_breadcrumbs
	 */
	function test_is_show_breadcrumbs_header() {
		$breadcrumbs = new Breadcrumbs();
		$post_id = $this->factory->post->create();
		$this->go_to( get_the_permalink($post_id) );

		update_option( 'ys_breadcrumbs_position', 'header' );

		$this->assertTrue($breadcrumbs->is_show_breadcrumbs());
	}

	/**
	 * Test: is_show_breadcrumbs
	 */
	function test_is_show_breadcrumbs_footer() {
		$breadcrumbs = new Breadcrumbs();
		$post_id = $this->factory->post->create();
		$this->go_to( get_the_permalink($post_id) );

		update_option( 'ys_breadcrumbs_position', 'footer' );

		$this->assertTrue($breadcrumbs->is_show_breadcrumbs());
	}
	/**
	 * Test: is_show_breadcrumbs
	 */
	function test_is_show_breadcrumbs_front_page() {
		$breadcrumbs = new Breadcrumbs();
		$post_id = $this->factory->post->create(
			[
				'post_type' => 'page',
			]
		);
		update_option( 'show_on_front', 'page' );
		update_option( 'page_on_front', $post_id );
		$this->go_to( home_url( '/' ) );

		update_option( 'ys_breadcrumbs_position', 'footer' );

		$this->assertFalse($breadcrumbs->is_show_breadcrumbs());
	}

	/**
	 * Test: is_show_breadcrumbs
	 */
	function test_is_show_breadcrumbs_blank_template() {
		$breadcrumbs = new Breadcrumbs();
		$post_id = $this->factory->post->create(
			[
				'post_type' => 'page',
			]
		);
		$this->go_to( get_the_permalink($post_id) );
		update_post_meta($post_id,'_wp_page_template','page-template/template-blank.php');
		update_option( 'ys_breadcrumbs_position', 'footer' );

		$this->assertFalse($breadcrumbs->is_show_breadcrumbs());
	}
	/**
	 * Test: is_show_breadcrumbs
	 */
	function test_is_show_breadcrumbs_blank_template_show_breadcrumb() {
		$breadcrumbs = new Breadcrumbs();
		$post_id = $this->factory->post->create(
			[
				'post_type' => 'page',
			]
		);
		$this->go_to( get_the_permalink($post_id) );
		update_post_meta($post_id,'_wp_page_template','page-template/template-blank.php');
		update_option( 'ys_breadcrumbs_position', 'footer' );
		update_option( 'ys_show_breadcrumb_blank_template', 1 );

		$this->assertTrue($breadcrumbs->is_show_breadcrumbs());
	}
	/**
	 * Test: is_show_breadcrumbs
	 */
	function test_is_show_breadcrumbs_blank_template_show_breadcrumb_header() {
		$breadcrumbs = new Breadcrumbs();
		$post_id = $this->factory->post->create(
			[
				'post_type' => 'page',
			]
		);
		$this->go_to( get_the_permalink($post_id) );
		update_post_meta($post_id,'_wp_page_template','page-template/template-blank.php');
		update_option( 'ys_breadcrumbs_position', 'header' );
		update_option( 'ys_show_breadcrumb_blank_template', 1 );

		$this->assertFalse($breadcrumbs->is_show_breadcrumbs());
	}

}
