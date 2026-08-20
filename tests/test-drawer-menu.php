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
}
