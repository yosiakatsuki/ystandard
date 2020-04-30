<?php
/**
 * モバイルフッターメニューテンプレート
 *
 * @package ystandard
 * @author  yosiakatsuki
 * @license GPL-2.0+
 */

if ( ! has_nav_menu( 'mobile-footer' ) ) {
	return;
}
?>
<nav class="footer-mobile-nav">
	<div class="container">
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
