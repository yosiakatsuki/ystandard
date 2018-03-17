<?php
/**
 * 記事下ウィジェットテンプレート
 *
 * @package ystandard
 * @author yosiakatsuki
 * @license GPL-2.0+
 */

if ( ys_is_active_entry_footer_widget() ) : ?>
<aside class="widget-entry-footer">
	<?php dynamic_sidebar( 'entry-footer' ); ?>
</aside>
<?php endif; ?>