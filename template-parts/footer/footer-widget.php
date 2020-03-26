<?php
/**
 * フッターウィジェットテンプレート
 *
 * @package ystandard
 * @author  yosiakatsuki
 * @license GPL-2.0+
 */

if ( ! ys_is_active_footer_widgets() ) {
	return;
}
?>
<div class="footer-widget">
	<div class="container">
		<div class="footer-widget__container">
			<?php if ( is_active_sidebar( 'footer-left' ) ) : ?>
				<div class="footer-widget__column">
					<?php dynamic_sidebar( 'footer-left' ); ?>
				</div>
			<?php endif; ?>
			<?php if ( is_active_sidebar( 'footer-center' ) ) : ?>
				<div class="footer-widget__column">
					<?php dynamic_sidebar( 'footer-center' ); ?>
				</div>
			<?php endif; ?>
			<?php if ( is_active_sidebar( 'footer-right' ) ) : ?>
				<div class="footer-widget__column">
					<?php dynamic_sidebar( 'footer-right' ); ?>
				</div>
			<?php endif; ?>
		</div>
	</div>
</div>
