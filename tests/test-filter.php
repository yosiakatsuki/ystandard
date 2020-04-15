<?php
/**
 * Class FilterTest
 *
 * @package ystandard
 */

/**
 * Class FilterTest
 */
class FilterTest extends WP_UnitTestCase {

	/**
	 * ys_get_custom_excerpt
	 */
	function test_ys_excerpt_by_content() {
		$args    = [
			'post_type'    => 'post',
			'post_content' => 'あいうえお',
			'post_excerpt' => '',
		];
		$post_id = $this->factory->post->create( $args );
		$this->go_to( "/?p=$post_id" );

		update_option( 'ys_option_excerpt_length', 80 );
		$except = ys_get_custom_excerpt();
		$this->assertSame( $except, 'あいうえお' );

		update_option( 'ys_option_excerpt_length', 1 );
		$except = ys_get_custom_excerpt();
		$this->assertSame( $except, 'あ …' );
	}
	/**
	 * ys_excerpt_length
	 */
	function test_ys_excerpt_by_excerpt() {
		$args    = [
			'post_type'    => 'post',
			'post_content' => 'あいうえお',
			'post_excerpt' => 'かきくけこ',
		];
		$post_id = $this->factory->post->create( $args );
		$this->go_to( "/?p=$post_id" );

		update_option( 'ys_option_excerpt_length', 80 );
		$except = ys_get_custom_excerpt();
		$this->assertSame( $except, 'かきくけこ' );
	}

	/**
	 * ys_option_default
	 */
	function test_ys_option_default_filter() {
		add_filter( 'ys_get_option_default_ys_color_site_bg', function ( $default ) {
			return '#123456';
		} );
		delete_option( 'ys_color_site_bg' );
		$this->assertSame( ys_get_option( 'ys_color_site_bg', '#ffffff' ), '#123456' );
	}
}
