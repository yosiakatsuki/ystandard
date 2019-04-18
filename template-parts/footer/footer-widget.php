<?php
/**
 * フッターウィジェットテンプレート
 *
 * @package ystandard
 * @author  yosiakatsuki
 * @license GPL-2.0+
 */

if ( ys_is_active_footer_widgets() ) : ?>
	<div class="footer-widget flex flex--row footer__section">
		<?php if ( is_active_sidebar( 'footer-left' ) ) : ?>
			<div class="footer-widget__container footer-widget--left flex__col--1 flex__col--lg-3">
				<?php dynamic_sidebar( 'footer-left' ); ?>
			</div>
		<?php endif; ?>
		<?php if ( is_active_sidebar( 'footer-center' ) ) : ?>
			<div class="footer-widget__container footer-widget--center flex__col--1 flex__col--lg-3">
				<?php dynamic_sidebar( 'footer-center' ); ?>
			</div>
		<?php endif; ?>
		<?php if ( is_active_sidebar( 'footer-right' ) ) : ?>
			<div class="footer-widget__container footer-widget--right flex__col--1 flex__col--lg-3">
				<?php dynamic_sidebar( 'footer-right' ); ?>
			</div>
		<?php endif; ?>
	</div>
<?php endif; ?>