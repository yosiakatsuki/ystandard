<?php
/**
 * Class ConditionalTagTest
 *
 * @package ystandard
 */

/**
 * AMP用テスト
 */
class HelperBooleanTest extends WP_UnitTestCase {

	function test_to_bool_true_true() {
		$this->assertTrue( ystandard\Utils\Convert::to_bool( true ) );
	}

	function test_to_bool_false_false() {
		$this->assertFalse( ystandard\Utils\Convert::to_bool( false ) );
	}

	function test_to_bool_1_true() {
		$this->assertTrue( ystandard\Utils\Convert::to_bool( 1 ) );
	}

	function test_to_bool_0_false() {
		$this->assertFalse( ystandard\Utils\Convert::to_bool( 0 ) );
	}

	function test_to_bool_string_true_true() {
		$this->assertTrue( ystandard\Utils\Convert::to_bool( 'true' ) );
	}

	function test_to_bool_string_false_false() {
		$this->assertFalse( ystandard\Utils\Convert::to_bool( 'false' ) );
	}

	function test_to_bool_empty_false() {
		$this->assertFalse( ystandard\Utils\Convert::to_bool( '' ) );
	}

	function test_to_bool_string_false() {
		$this->assertFalse( ystandard\Utils\Convert::to_bool( 'abcde' ) );
	}
}
