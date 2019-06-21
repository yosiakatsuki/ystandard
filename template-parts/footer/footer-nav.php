<?php
/**
 * フッターメニューテンプレート
 *
 * @package ystandard
 * @author  yosiakatsuki
 * @license GPL-2.0+
 */

if ( has_nav_menu( 'footer' ) ) : ?>
	<nav class="footer-nav footer-section">
		<?php
		wp_nav_menu(
			array(
				'theme_location' => 'footer',
				'menu_class'     => 'footer__nav-list li-clear flex flex--wrap',
				'container'      => false,
				'fallback_cb'    => '',
				'depth'          => 1,
			)
		);
		?>
	</nav><!-- .footer-nav -->
<?php endif; ?>