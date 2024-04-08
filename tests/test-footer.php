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
		$expected = [];
		$this->assertSame( $expected, $footer->add_css_var_footer_sub( [] ) );
	}

	/**
	 * サブフッター 背景設定テスト
	 */
	function test_sub_footer_css_var_background() {
		update_option( 'ys_color_sub_footer_bg', '#dddddd' );
		$footer   = new \ystandard\Footer();
		$expected = [
			'sub-footer--background' => '#dddddd',
		];
		$this->assertSame( $expected, $footer->add_css_var_footer_sub( [] ) );
	}

	/**
	 * サブフッター 文字色設定テスト
	 */
	function test_sub_footer_css_var_text_color() {
		update_option( 'ys_color_sub_footer_text', '#000000' );
		$footer   = new \ystandard\Footer();
		$expected = [
			'sub-footer--text-color' => '#000000',
		];
		$this->assertSame( $expected, $footer->add_css_var_footer_sub( [] ) );
	}

	/**
	 * サブフッター 文字色・背景色設定テスト
	 */
	function test_sub_footer_css_var_text_and_background_color() {
		update_option( 'ys_color_sub_footer_bg', '#dddddd' );
		update_option( 'ys_color_sub_footer_text', '#000000' );
		$footer   = new \ystandard\Footer();
		$expected = [
			'sub-footer--background' => '#dddddd',
			'sub-footer--text-color' => '#000000',
		];
		$this->assertSame( $expected, $footer->add_css_var_footer_sub( [] ) );
	}

	/**
	 * サブフッター 余白設定テスト
	 */
	function test_sub_footer_css_var_padding_empty() {
		update_option( 'ys_sub_footer_padding', '' );
		$footer   = new \ystandard\Footer();
		$expected = [];
		$this->assertSame( $expected, $footer->add_css_var_footer_sub( [] ) );
	}

	/**
	 * サブフッター 余白設定テスト
	 */
	function test_sub_footer_css_var_padding_zero() {
		update_option( 'ys_sub_footer_padding', '0' );
		$footer   = new \ystandard\Footer();
		$expected = [
			'sub-footer--padding' => 0,
		];
		$this->assertSame( $expected, $footer->add_css_var_footer_sub( [] ) );
	}

	/**
	 * サブフッター 余白設定テスト
	 */
	function test_sub_footer_css_var_padding_30() {
		update_option( 'ys_sub_footer_padding', '30' );
		$footer   = new \ystandard\Footer();
		$expected = [
			'sub-footer--padding' => '30px',
		];
		$this->assertSame( $expected, $footer->add_css_var_footer_sub( [] ) );
	}

}
