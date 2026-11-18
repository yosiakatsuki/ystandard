<?php
/**
 * フッターメニューテンプレート
 *
 * @package ystandard
 * @author  yosiakatsuki
 * @license GPL-2.0+
 */

defined( 'ABSPATH' ) || die();

// メニュー未割り当て時は空のナビゲーション領域を作らない.
if ( ! has_nav_menu( 'footer' ) ) {
	return;
}
?>
<nav class="footer-nav" aria-label="<?php esc_attr_e( 'フッターメニュー', 'ystandard' ); ?>">
	<div class="footer-nav__container">
		<?php
		wp_nav_menu(
			[
				'theme_location' => 'footer',
				'menu_class'     => 'footer-nav__menu',
				'container'      => false,
				'fallback_cb'    => '',
				'depth'          => 1,
			]
		);
		?>
	</div>
</nav>
