<?php
/**
 * フッターテンプレート
 *
 * @package ystandard
 * @author  yosiakatsuki
 * @license GPL-2.0+
 */

?>
	</div>
	<?php do_action( 'ys_before_site_footer' ); ?>
	<footer id="footer" class="footer site-footer">
		<?php do_action( 'ys_site_footer_prepend' ); ?>
		<div class="container">
			<?php
			/**
			 * ウィジェット
			 */
			ys_get_template_part( 'template-parts/footer/footer-widget' );
			/**
			 * フッターナビゲーション
			 */
			ys_get_template_part( 'template-parts/footer/footer-nav' );
			/**
			 * Copyright
			 */
			ys_get_template_part( 'template-parts/footer/footer-copy' );
			?>
		</div>
		<?php do_action( 'ys_site_footer_append' ); ?>
	</footer>
	<?php
	wp_footer();
	do_action( 'ys_body_append' );
	?>
</body>
</html>
