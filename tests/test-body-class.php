<?php

/**
 * Body_classのテスト
 */
class Body_Class_Test extends WP_UnitTestCase {

	const CLASS_HAS_BACKGROUND = 'has-background';

	/**
	 * 背景設定のリセット
	 *
	 * @return void
	 */
	private function reset_background_option() {
		set_theme_mod( 'background_image', '' );
		update_option( 'ys_color_site_bg', '' );
		update_option( 'ys_color_content_bg', '' );
	}

	/**
	 * 背景ありモードテスト - 初期状態
	 * @return void
	 */
	function test_has_background_default() {
		$classes = get_body_class();
		// 初期状態では背景なしモード
		$this->assertFalse( in_array( self::CLASS_HAS_BACKGROUND, $classes, true ) );
	}

	/**
	 * 背景ありモードテスト - サイト背景色のみあり
	 * @return void
	 */
	function test_has_background_site_bg() {
		//設定リセット.
		$this->reset_background_option();
		update_option( 'ys_color_site_bg', '#dddddd' );
		// body クラス取得.
		$classes = get_body_class();
		// 背景色のみ設定であれば背景なしモード
		$this->assertFalse( in_array( self::CLASS_HAS_BACKGROUND, $classes, true ) );
	}

	/**
	 * 背景ありモードテスト - コンテンツ背景あり
	 * @return void
	 */
	function test_has_background_contents_background() {
		//設定リセット.
		$this->reset_background_option();
		update_option( 'ys_color_content_bg', '#dddddd' );
		// body クラス取得.
		$classes = get_body_class();
		// サイト背景画像のみであれば背景なしモード
		$this->assertTrue( in_array( self::CLASS_HAS_BACKGROUND, $classes, true ) );
	}

	/**
	 * 背景ありモードテスト - コンテンツ背景あり - サイト背景と一緒
	 * @return void
	 */
	function test_has_background_contents_background_same_site_bg() {
		//設定リセット.
		$this->reset_background_option();
		update_option( 'ys_color_content_bg', '#dddddd' );
		update_option( 'ys_color_site_bg', '#dddddd' );
		// body クラス取得.
		$classes = get_body_class();
		// サイト背景画像のみであれば背景なしモード
		$this->assertFalse( in_array( self::CLASS_HAS_BACKGROUND, $classes, true ) );
	}

	/**
	 * 背景ありモードテスト - コンテンツ背景あり - サイト背景と違う
	 * @return void
	 */
	function test_has_background_contents_background_and_site_bg() {
		//設定リセット.
		$this->reset_background_option();
		update_option( 'ys_color_content_bg', '#dddddd' );
		update_option( 'ys_color_site_bg', '#aaaaaa' );
		// body クラス取得.
		$classes = get_body_class();
		// サイト背景画像のみであれば背景なしモード
		$this->assertTrue( in_array( self::CLASS_HAS_BACKGROUND, $classes, true ) );
	}

	/**
	 * 背景ありモードテスト - サイト背景画像のみあり
	 * @return void
	 */
	function test_has_background_background_image() {
		//設定リセット.
		$this->reset_background_option();
		set_theme_mod( 'background_image', get_template_directory_uri() . '/assets/images/publisher-logo/default-publisher-logo.png' );
		// body クラス取得.
		$classes = get_body_class();
		// サイト背景画像のみであれば背景なしモード
		$this->assertFalse( in_array( self::CLASS_HAS_BACKGROUND, $classes, true ) );
	}

	/**
	 * 背景ありモードテスト - サイト背景画像のみあり
	 * @return void
	 */
	function test_has_background_background_image_has_content_bg() {
		//設定リセット.
		$this->reset_background_option();
		set_theme_mod( 'background_image', get_template_directory_uri() . '/assets/images/publisher-logo/default-publisher-logo.png' );
		update_option( 'ys_color_content_bg', '#dddddd' );
		// body クラス取得.
		$classes = get_body_class();
		// サイト背景画像のみであれば背景なしモード
		$this->assertTrue( in_array( self::CLASS_HAS_BACKGROUND, $classes, true ) );
	}
}
