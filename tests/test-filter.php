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
}