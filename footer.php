<?php
/**
 * フッターテンプレート
 *
 * @package ystandard
 * @author yosiakatsuki
 * @license GPL-2.0+
 */

?>
	</div><!-- .site-content -->
	<?php
	/**
	 * パンくずリスト
	 */
	if ( 'footer' === ys_get_option( 'ys_breadcrumbs_position' ) ) {
		get_template_part( 'template-parts/parts/breadcrumbs' );
	}
	?>
	<?php do_action( 'ys_before_site_footer' ); ?>
	<footer id="footer" class="site-footer site__footer">
		<div class="container">
			<?php
				/**
				 * SNSフォロー
				 */
				get_template_part( 'template-parts/footer/footer-sns' );
				/**
				 * ウィジェット
				 */
				get_template_part( 'template-parts/footer/footer-widget' );
				/**
				 * フッターナビゲーション
				 */
				get_template_part( 'template-parts/footer/footer-nav' );
				/**
				 * Copyright
				 */
				get_template_part( 'template-parts/footer/footer-copy' );
			?>
		</div><!-- .container -->
	</footer><!-- .site-footer -->
	<?php get_template_part( 'template-parts/footer/footer-mobile-nav' ); ?>
</div><!-- .site -->
<?php
if ( ys_is_ystd_amp() ) {
	/**
	 * AMP
	 */
	ys_amp_footer();
} else {
	/**
	 * AMP以外
	 */
	wp_footer();
}
?>
<?php do_action( 'ys_body_append' ); ?>
</body>
</html>