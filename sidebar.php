<?php
/**
 * サイドバーテンプレート
 *
 * @package ystandard
 * @author  yosiakatsuki
 * @license GPL-2.0+
 */

if ( ys_is_active_sidebar_widget() ) : ?>
	<aside id="secondary" class="sidebar sidebar-widget widget-area">
		<div id="sidebar-wrapper" class="sidebar-wrapper">
			<?php if ( is_active_sidebar( 'sidebar-widget' ) ) : ?>
				<div id="sidebar-widget" class="sidebar__widget clearfix">
					<?php dynamic_sidebar( 'sidebar-widget' ); ?>
				</div>
			<?php endif; ?>
			<?php if ( is_active_sidebar( 'sidebar-fixed' ) ) : ?>
				<div id="sidebar-fixed" class="sidebar__fixed clearfix">
					<?php dynamic_sidebar( 'sidebar-fixed' ); ?>
				</div>
			<?php endif; ?>
		</div>
	</aside><!-- .sidebar .widget-area -->
<?php endif; ?>