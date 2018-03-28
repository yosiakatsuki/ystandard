<?php
/**
 * フッターウィジェットテンプレート
 *
 * @package ystandard
 * @author yosiakatsuki
 * @license GPL-2.0+
 */

if ( ys_is_active_footer_widgets() ) : ?>
<div class="footer__widget flex--pc footer__section">
	<?php if ( is_active_sidebar( 'footer-left' ) ) : ?>
		<div class="footer__widget-container footer__widget--left">
			<?php dynamic_sidebar( 'footer-left' ); ?>
		</div>
	<?php endif; ?>
	<?php if ( is_active_sidebar( 'footer-center' ) ) : ?>
		<div class="footer__widget-container footer__widget--center">
			<?php dynamic_sidebar( 'footer-center' ); ?>
		</div>
	<?php endif; ?>
	<?php if ( is_active_sidebar( 'footer-right' ) ) : ?>
		<div class="footer__widget-container footer__widget--right">
			<?php dynamic_sidebar( 'footer-right' ); ?>
		</div>
	<?php endif; ?>
</div>
<?php endif; ?>