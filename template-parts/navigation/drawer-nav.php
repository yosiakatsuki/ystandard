<?php
/**
 * ドロワーナビゲーションテンプレート
 *
 * 開閉メニューの内容部分。基本動作はグローバルメニューと同じ
 *
 * @package ystandard
 * @author  yosiakatsuki
 * @license GPL-2.0+
 */

defined( 'ABSPATH' ) || die();

if ( ! ys_has_global_nav() ) {
	return;
}
?>
<div id="drawer-nav" class="<?php ys_global_nav_class( 'drawer-nav' ); ?>">
	<div class="drawer-nav__close-container">
		<?php
		/**
		 * ドロワーメニュー開閉ボタン
		 */
		ys_global_nav_toggle_button(
			[
				'id'    => 'drawer-nav__toggle',
				'class' => 'global-nav__toggle drawer-nav__close',
			]
		);
		?>
	</div>
	<?php

	/**
	 * グローバルナビゲーション（共通）・ドロワーメニュー　メニュー先頭のフック
	 * 開閉ボタンの後に表示
	 */
	do_action( 'ys_global_nav_prepend' );
	do_action( 'ys_drawer_nav_prepend' );
	?>

	<nav class="drawer-nav__container">
		<?php
		/**
		 * グローバルナビゲーション（共通）・ドロワーメニュー　メニュー直前のフック
		 *
		 * ドロワーメニュー内の検索フォーム出力はアクションフックで出力
		 * @see inc/menu/class-drawer-menu.php
		 */
		do_action( 'ys_before_global_nav_menu' );
		do_action( 'ys_before_drawer_nav_menu' );
		/**
		 * ドロワーメニューの出力(内容はグローバルナビと共通)
		 */
		wp_nav_menu(
			[
				'theme_location' => 'global',
				'menu_class'     => 'drawer-nav__menu',
				'menu_id'        => 'drawer-nav__menu',
				'container'      => false,
				'depth'          => 2,
				'fallback_cb'    => '',
				'walker'         => ys_global_nav_walker(),
			]
		);
		/**
		 * グローバルナビゲーション（共通）・ドロワーメニュー　メニュー直後のフック
		 *
		 */
		do_action( 'ys_after_global_nav_menu' );
		do_action( 'ys_after_drawer_nav_menu' );
		?>
	</nav>

	<?php
	/**
	 * グローバルナビゲーション（共通）・ドロワーメニュー　メニュー最後のフック
	 */
	do_action( 'ys_global_nav_append' );
	do_action( 'ys_drawer_nav_append' );
	?>
</div>

