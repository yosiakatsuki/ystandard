<?php
/**
 * モバイルフッターメニューテンプレート
 *
 * @package ystandard
 * @author  yosiakatsuki
 * @license GPL-2.0+
 */

if ( has_nav_menu( 'mobile-footer' ) ) : ?>
	<nav class="footer-mobile-nav">
		<div class="container">
			<?php
			wp_nav_menu(
				array(
					'theme_location' => 'mobile-footer',
					'menu_class'     => 'mobile-footer-nav__list li-clear flex flex--j-between',
					'container'      => false,
					'fallback_cb'    => '',
					'depth'          => 1,
					'walker'         => new YS_Walker_Mobile_Footer_Menu(),
				)
			);
			?>
		</div>
	</nav>
<?php endif; ?>