<?php
/**
 * モバイルフッターメニューテンプレート
 *
 * @package ystandard
 * @author  yosiakatsuki
 * @license GPL-2.0+
 */

defined( 'ABSPATH' ) || die();

if ( ! has_nav_menu( 'mobile-footer' ) ) {
	return;
}
?>
<nav id="footer-mobile-nav" class="footer-mobile-nav">
	<div class="footer-mobile-nav-container">
		<?php
		wp_nav_menu(
			[
				'theme_location' => 'mobile-footer',
				'menu_class'     => 'mobile-footer-nav__list',
				'container'      => false,
				'fallback_cb'    => '',
				'depth'          => 1,
				'walker'         => new YS_Walker_Mobile_Footer_Menu(),
			]
		);
		?>
	</div>
</nav>
