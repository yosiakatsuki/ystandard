<?php
/**
 * フッターメニューテンプレート
 *
 * @package ystandard
 * @author  yosiakatsuki
 * @license GPL-2.0+
 */

if ( ! has_nav_menu( 'footer' ) ) {
	return;
}
?>
<nav class="footer-nav">
	<div class="container">
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
