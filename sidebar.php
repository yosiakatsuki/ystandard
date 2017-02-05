<?php
	$show_sidebar = true;
	if(ys_is_amp()) {
		$show_sidebar = false;
	} elseif(ys_is_mobile() && ys_get_setting('ys_show_sidebar_mobile') == 0) {
		$show_sidebar = false;
	}
	if ( $show_sidebar ) :
?>
<aside id="secondary" class="sidebar sidebar-right widget-area" role="complementary">
	<?php if ( is_active_sidebar( 'sidebar-right' ) || is_active_sidebar( 'sidebar-fixed' ) ) : ?>
		<div class="sidebar-wrapper">
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
	<?php endif; ?>
</aside><!-- .sidebar .widget-area -->
<?php endif; ?>