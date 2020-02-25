<?php
/**
 * Class ConditionalTagTest
 *
 * @package ystandard
 */

/**
 * AMP用テスト
 */
class FilterTest extends WP_UnitTestCase {

	/**
	 * ys_excerpt_length
	 */
	function test_ys_excerpt_length() {
		update_option( 'ys_option_excerpt_length', 80 );
		$this->assertSame( ys_excerpt_length( 100 ), 80 );
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
