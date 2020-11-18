<?php
/**
 * Class OptionsTest
 *
 * @package ystandard
 */

/**
 * Class OptionsTest
 */
class OptionsTest extends WP_UnitTestCase {

	/**
	 * Test: ys_get_option
	 */
	function test_ys_get_option_by_default() {
		$this->assertSame( ys_get_option( 'ys_design_header_type', 'row1' ), 'row1' );
	}

	/**
	 * Test: ys_get_option
	 */
	function test_ys_get_option() {
		update_option( 'ys_design_header_type', 'center' );
		$this->assertSame( ys_get_option( 'ys_design_header_type', 'row1' ), 'center' );
	}
}
