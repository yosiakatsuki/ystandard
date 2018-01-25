<?php if ( ys_is_show_sidebar() ) : ?>
<aside id="secondary" class="sidebar sidebar-right widget-area">
		<div id="sidebar-wrapper" class="sidebar-wrapper">
			<?php if ( is_active_sidebar( 'sidebar-right' ) ): ?>
				<div id="sidebar-right" class="clearfix">
					<?php dynamic_sidebar( 'sidebar-right' ); ?>
				</div>
			<?php endif; ?>
			<?php if ( is_active_sidebar( 'sidebar-fixed' ) ): ?>
				<div id="sidebar-fixed" class="clearfix">
					<?php dynamic_sidebar( 'sidebar-fixed' ); ?>
				</div>
			<?php endif; ?>
		</div>
</aside><!-- .sidebar .widget-area -->
<?php endif; ?>