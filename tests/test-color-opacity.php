<?php
/**
 * Class Color_Opacity_Test
 *
 * @package ystandard
 */

/**
 * Class Color_Opacity_Test
 */
class Color_Opacity_Test extends WP_UnitTestCase {

	/**
	 * テスト終了処理.
	 */
	public function tear_down() {
		delete_option( 'ys_color_mobile_footer_bg' );
		delete_option( 'ys_global_nav_sub_menu_background_color' );
		delete_option( 'ys_global_nav_sub_menu_background_opacity' );
		parent::tear_down();
	}

	/**
	 * モバイルフッターの既存色は従来の不透明度で出力することを確認.
	 */
	public function test_mobile_footer_keeps_legacy_opacity_for_six_digit_hex() {
		update_option( 'ys_color_mobile_footer_bg', '#123456' );

		$css_vars = ( new \ystandard\Mobile_Footer() )->add_css_var( [] );

		$this->assertSame( 'rgb(18,52,86,0.95)', $css_vars['mobile-footer--background'] );
	}

	/**
	 * モバイルフッターで色に含まれる不透明度を使用することを確認.
	 */
	public function test_mobile_footer_uses_alpha_hex() {
		update_option( 'ys_color_mobile_footer_bg', '#12345680' );

		$css_vars = ( new \ystandard\Mobile_Footer() )->add_css_var( [] );

		$this->assertSame( '#12345680', $css_vars['mobile-footer--background'] );
	}

	/**
	 * 3桁HEXをRGBへ変換できることを確認.
	 */
	public function test_mobile_footer_converts_short_hex() {
		update_option( 'ys_color_mobile_footer_bg', '#123' );

		$css_vars = ( new \ystandard\Mobile_Footer() )->add_css_var( [] );

		$this->assertSame( 'rgb(17,34,51,0.95)', $css_vars['mobile-footer--background'] );
	}

	/**
	 * グローバルメニューで既存の不透明度設定を維持することを確認.
	 */
	public function test_global_nav_keeps_legacy_opacity_for_six_digit_hex() {
		update_option( 'ys_global_nav_sub_menu_background_color', '#123456' );
		update_option( 'ys_global_nav_sub_menu_background_opacity', '0.4' );

		$css_vars = ( new \ystandard\Global_Nav() )->css_vars( [] );

		$this->assertSame( 'rgba(18, 52, 86, 0.4)', $css_vars['--ystd--global-nav--sub-menu--background'] );
	}

	/**
	 * グローバルメニューで色に含まれる不透明度を優先することを確認.
	 */
	public function test_global_nav_prefers_alpha_hex() {
		update_option( 'ys_global_nav_sub_menu_background_color', '#12345680' );
		update_option( 'ys_global_nav_sub_menu_background_opacity', '0.4' );

		$css_vars = ( new \ystandard\Global_Nav() )->css_vars( [] );

		$this->assertSame( '#12345680', $css_vars['--ystd--global-nav--sub-menu--background'] );
	}

	/**
	 * 背景色が空の場合は従来どおり白へ不透明度を適用することを確認.
	 */
	public function test_global_nav_uses_white_when_only_legacy_opacity_is_set() {
		update_option( 'ys_global_nav_sub_menu_background_opacity', '0.4' );

		$css_vars = ( new \ystandard\Global_Nav() )->css_vars( [] );

		$this->assertSame( 'rgba(255, 255, 255, 0.4)', $css_vars['--ystd--global-nav--sub-menu--background'] );
	}
}
