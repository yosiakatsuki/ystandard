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

	<?php
	// サブフッター.
	get_template_part( 'template-parts/footer/sub-footer' );
	?>

	<?php do_action( 'ys_before_footer_main' ); ?>

	<?php if ( ys_is_active_footer_main_contents() ) : ?>
		<div class="footer-main">
			<div class="footer-container">
				<?php
				// ウィジェット.
				get_template_part( 'template-parts/footer/footer-widget' );
				// フッターナビゲーション.
				get_template_part( 'template-parts/footer/footer-nav' );
				?>
			</div>
		</div>
	<?php endif; ?>

	<?php
	// Copyright.
	get_template_part( 'template-parts/footer/footer-copyright' );
	?>

	<?php do_action( 'ys_site_footer_append' ); ?>
</footer>
<?php
do_action( 'ys_after_site_footer' );
wp_footer();
do_action( 'ys_body_append' );
?>
</body>
</html>
