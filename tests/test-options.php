<?php
/**
 * Class ConditionalTagTest
 *
 * @package ystandard
 */

/**
 * AMP用テスト
 */
class OptionsTest extends WP_UnitTestCase {

	/**
	 * ys_get_option
	 */
	function test_ys_get_option_by_default() {
		$this->assertSame( ys_get_option( 'ys_design_header_type', 'row1' ), 'row1' );
	}

	/**
	 * ys_get_option
	 */
	function test_ys_get_option() {
		update_option( 'ys_design_header_type', 'center' );
		$this->assertSame( ys_get_option( 'ys_design_header_type' ), 'center' );
	}

	/**
	 * ys_get_option_by_bool
	 */
	function test_ys_get_option_by_bool_true() {
		update_option( 'ys_show_post_thumbnail', '1' );
		$this->assertTrue( ys_get_option_by_bool( 'ys_show_post_thumbnail' ) );
	}

	/**
	 * ys_get_option_by_bool
	 */
	function test_ys_get_option_by_bool_false() {
		update_option( 'ys_show_post_thumbnail', '' );
		$this->assertFalse( ys_get_option_by_bool( 'ys_show_post_thumbnail' ) );
	}
}
