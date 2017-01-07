<?php
	$setting = ys_settings();
	$show_sidebar = true;
	if(ys_is_amp()) {
		$show_sidebar = false;
	} elseif(ys_is_mobile() && $setting['ys_show_sidebar_mobile'] == 0) {
		$show_sidebar = false;
	}
	if ( $show_sidebar ) :
?>
<aside id="secondary" class="sidebar sidebar-right widget-area" role="complementary">
	<?php if ( is_active_sidebar( 'sidebar-right' )) : ?>
		<div class="sidebar-wrapper">
			<?php dynamic_sidebar( 'sidebar-right' ); ?>
		</div>
	<?php endif; ?>
</aside><!-- .sidebar .widget-area -->
<?php endif; ?>