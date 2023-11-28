<?php
/**
 * メニュー関連
 *
 * @package ystandard
 * @author  yosiakatsuki
 * @license GPL-3.0+
 */

namespace ystandard;

defined( 'ABSPATH' ) || die();

/**
 * Class Nav_menu
 *
 * @package ystandard
 */
class Nav_menu {

	/**
	 * Nav_menu constructor.
	 */
	public function __construct() {
		add_action( 'after_setup_theme', [ $this, 'register_nav_menus' ] );
	}


	public function register_nav_menus() {
		// メニュー有効化.
		register_nav_menus(
			[
				'global'        => __( 'グローバルナビゲーション', 'ystandard' ),
				'footer'        => __( 'フッターメニュー', 'ystandard' ),
				'mobile-footer' => __( 'モバイルフッター', 'ystandard' ),
			]
		);
	}
}

new Nav_menu();
