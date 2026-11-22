<?php
/**
 * Class Drawer_Menu_Test
 *
 * @package ystandard
 */

/**
 * ドロワーメニューのテスト.
 */
class Drawer_Menu_Test extends WP_UnitTestCase {

	/**
	 * テスト終了処理.
	 */
	public function tear_down() {
		remove_filter( 'ys_has_global_nav', '__return_true' );
		parent::tear_down();
	}

	/**
	 * 開くボタンにダイアログ操作用の属性が付くことを確認する.
	 */
	public function test_open_button_has_dialog_attributes() {
		$button = \ystandard\Drawer_Menu::get_toggle_button();

		$this->assertStringContainsString( 'type="button"', $button );
		$this->assertStringContainsString( 'aria-label="メニューを開く"', $button );
		$this->assertStringContainsString( 'aria-controls="drawer-nav"', $button );
		$this->assertStringContainsString( 'aria-expanded="false"', $button );
	}

	/**
	 * 閉じるボタンが開くボタンの状態属性を持たないことを確認する.
	 */
	public function test_close_button_has_close_attributes() {
		$button = \ystandard\Drawer_Menu::get_toggle_button(
			[
				'type'  => 'close',
				'id'    => 'drawer-nav__toggle',
				'class' => 'global-nav__toggle drawer-nav__close',
			]
		);

		$this->assertStringContainsString( 'type="button"', $button );
		$this->assertStringContainsString( 'aria-label="メニューを閉じる"', $button );
		$this->assertStringNotContainsString( 'aria-controls=', $button );
		$this->assertStringNotContainsString( 'aria-expanded=', $button );
	}

	/**
	 * ドロワーメニューがdialog要素として出力されることを確認する.
	 */
	public function test_drawer_menu_uses_dialog_element() {
		add_filter( 'ys_has_global_nav', '__return_true' );

		ob_start();
		get_template_part( 'template-parts/navigation/drawer-nav' );
		$output = ob_get_clean();

		$this->assertStringContainsString( '<dialog id="drawer-nav"', $output );
		$this->assertStringContainsString( 'aria-label="サイトメニュー"', $output );
		$this->assertStringContainsString( '</dialog>', $output );
	}

	/**
	 * メニュー最大幅が数値のみの場合にpxを補完して出力することを確認する.
	 */
	public function test_drawer_menu_width_appends_px_to_number() {
		update_option( 'ys_drawer_menu_width', '720' );

		$css_vars = apply_filters( 'ys_get_css_custom_properties_args', [] );

		$this->assertSame( '720px', $css_vars['--ystd--drawer-menu--width'] );
	}

	/**
	 * メニュー最大幅が単位付きの場合はそのまま出力することを確認する.
	 */
	public function test_drawer_menu_width_keeps_unit() {
		update_option( 'ys_drawer_menu_width', '40rem' );

		$css_vars = apply_filters( 'ys_get_css_custom_properties_args', [] );

		$this->assertSame( '40rem', $css_vars['--ystd--drawer-menu--width'] );
	}

	/**
	 * 保存済みの値にCSS宣言を追加する文字が含まれる場合は出力しないことを確認する.
	 */
	public function test_drawer_menu_css_vars_ignore_invalid_values() {
		update_option( 'ys_drawer_menu_width', '100px;}body{display:none' );
		update_option( 'ys_drawer_menu_font_size', '16px;}body{display:none' );
		update_option( 'ys_drawer_menu_sub_menu_font_size', '14px;}body{display:none' );

		$css_vars = apply_filters( 'ys_get_css_custom_properties_args', [] );

		$this->assertArrayNotHasKey( '--ystd--drawer-menu--width', $css_vars );
		$this->assertArrayNotHasKey( '--ystd--drawer-menu--font-size', $css_vars );
		$this->assertArrayNotHasKey( '--ystd--drawer-menu--sub-menu--font-size', $css_vars );
	}

	/**
	 * 文字サイズが数値のみの場合にpxを補完して出力することを確認する.
	 */
	public function test_drawer_menu_font_size_appends_px_to_number() {
		update_option( 'ys_drawer_menu_font_size', '18' );
		update_option( 'ys_drawer_menu_sub_menu_font_size', '0.9em' );

		$css_vars = apply_filters( 'ys_get_css_custom_properties_args', [] );

		$this->assertSame( '18px', $css_vars['--ystd--drawer-menu--font-size'] );
		$this->assertSame( '0.9em', $css_vars['--ystd--drawer-menu--sub-menu--font-size'] );
	}

	/**
	 * メニュー最大幅が未入力の場合は出力しないことを確認する.
	 */
	public function test_drawer_menu_width_is_not_output_when_empty() {
		$css_vars = apply_filters( 'ys_get_css_custom_properties_args', [] );

		$this->assertArrayNotHasKey( '--ystd--drawer-menu--width', $css_vars );
	}
}
