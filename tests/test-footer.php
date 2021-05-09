<?php
/**
 * Class Footer_Test
 *
 * @package ystandard
 */

/**
 * Class Footer_Test
 */
class Footer_Test extends WP_UnitTestCase {

	/**
	 * サブフッター 初期値テスト
	 */
	function test_sub_footer_css_var() {
		$footer   = new \ystandard\Footer();
		$expected = [
			'sub-footer-bg'   => '#f1f1f3',
			'sub-footer-text' => '#222222',
		];
		$this->assertSame( $expected, $footer->add_css_var_footer_sub( [] ) );
	}
	/**
	 * サブフッター 初期値テスト
	 */
	function test_sub_footer_css_var_bg_empty() {
		update_option( 'ys_color_sub_footer_bg', '' );
		$footer   = new \ystandard\Footer();
		$expected = [
			'sub-footer-bg'   => 'transparent',
			'sub-footer-text' => '#222222',
		];
		$this->assertSame( $expected, $footer->add_css_var_footer_sub( [] ) );
	}

	/**
	 * サブフッター 余白設定テスト
	 */
	function test_sub_footer_css_var_padding_empty() {
		update_option( 'ys_color_sub_footer_padding', '' );
		$footer   = new \ystandard\Footer();
		$expected = [
			'sub-footer-bg'   => '#f1f1f3',
			'sub-footer-text' => '#222222',
		];
		$this->assertSame( $expected, $footer->add_css_var_footer_sub( [] ) );
	}

	/**
	 * サブフッター 余白設定テスト
	 */
	function test_sub_footer_css_var_padding_zero() {
		update_option( 'ys_color_sub_footer_padding', '0' );
		$footer   = new \ystandard\Footer();
		$expected = [
			'sub-footer-bg'      => '#f1f1f3',
			'sub-footer-text'    => '#222222',
			'sub-footer-padding' => 0,
		];
		$this->assertSame( $expected, $footer->add_css_var_footer_sub( [] ) );
	}

	/**
	 * サブフッター 余白設定テスト
	 */
	function test_sub_footer_css_var_padding_30() {
		update_option( 'ys_color_sub_footer_padding', '30' );
		$footer   = new \ystandard\Footer();
		$expected = [
			'sub-footer-bg'      => '#f1f1f3',
			'sub-footer-text'    => '#222222',
			'sub-footer-padding' => '30px',
		];
		$this->assertSame( $expected, $footer->add_css_var_footer_sub( [] ) );
	}

}
