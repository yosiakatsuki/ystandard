<?php
/**
 * Class CustomizerTest
 *
 * @package ystandard
 */

/**
 * Class CustomizerTest
 */
class CustomizerTest extends WP_UnitTestCase {

	/**
	 * get_priority
	 */
	function test_get_priority() {
		$priority = \ystandard\Customizer::get_priority( 'ys_seo' );
		$this->assertSame( $priority, 1110 );

		$priority = \ystandard\Customizer::get_priority( 'ys_none' );
		$this->assertSame( $priority, 1000 );
	}

}
