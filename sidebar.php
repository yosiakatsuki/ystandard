<?php if ( is_active_sidebar( 'sidebar-main' )  ) : ?>
	<aside id="secondary" class="sidebar widget-area" role="complementary">
		<?php dynamic_sidebar( 'sidebar-main' ); ?>
	</aside><!-- .sidebar .widget-area -->
<?php endif; ?>
