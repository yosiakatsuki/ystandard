<?php if ( is_active_sidebar( 'sidebar-main' )  ) : ?>
	<aside id="secondary" class="sidebar sidebar-main widget-area" role="complementary">
		<div class="sidebar-wrapper">
			<?php dynamic_sidebar( 'sidebar-main' ); ?>
		</div>
	</aside><!-- .sidebar .widget-area -->
<?php endif; ?>