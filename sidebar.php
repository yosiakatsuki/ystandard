<?php
/**
 * サイドバーテンプレート
 *
 * @package ystandard
 * @author  yosiakatsuki
 * @license GPL-2.0+
 */

if ( ! ys_is_active_sidebar() ) {
	return;
}
?>
<aside id="secondary" class="sidebar sidebar-widget widget-area">
	<?php if ( is_active_sidebar( 'sidebar-widget' ) ) : ?>
		<div id="sidebar-widget" class="sidebar__widget">
			<?php dynamic_sidebar( 'sidebar-widget' ); ?>
		</div>
	<?php endif; ?>
	<?php if ( is_active_sidebar( 'sidebar-fixed' ) ) : ?>
		<div id="sidebar-fixed" class="sidebar__fixed">
			<?php dynamic_sidebar( 'sidebar-fixed' ); ?>
		</div>
	<?php endif; ?>
</aside><!-- .sidebar .widget-area -->
