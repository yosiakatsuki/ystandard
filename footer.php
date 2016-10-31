
		</div><!-- .site-content -->

		<footer id="footer" class="site-footer" role="contentinfo">
			<div class="wrap">
				<?php if ( has_nav_menu( 'footer' ) ) : ?>
					<nav class="Footer-navigation" role="navigation">
						<?php
							wp_nav_menu( array(
								'theme_location' => 'footer',
								'menu_class'     => 'footer-menu',
								'depth'          => 1
							 ) );
						?>
					</nav><!-- .footer-navigation -->
				<?php endif; ?>

				<div class="site-info">
					<span class="copy">Copyright &copy; <?php echo date_i18n('Y') ?> <a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a>All Rights Reserved.</span>
				</div><!-- .site-info -->
			</div><!-- .wrap -->
		</footer><!-- .site-footer -->
	</div><!-- .site-inner -->
</div><!-- .site -->

<?php wp_footer(); ?>
</body>
</html>