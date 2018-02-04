<?php
	if ( has_nav_menu( 'footer' ) ) : ?>
	<nav class="footer-nav">
		<?php
			wp_nav_menu( array(
				'theme_location' => 'footer',
				'menu_class'     => 'footer__nav-list',
				'container'      => false,
				'fallback_cb'    => '',
				'depth'          => 1
			 ) );
		?>
	</nav><!-- .footer-nav -->
<?php endif; ?>