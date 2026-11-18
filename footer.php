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
	<?php
	do_action( 'ys_site_footer_prepend' );
	// フッターコンテンツ.
	get_template_part( 'template-parts/footer/footer-content' );
	// フッターナビ.
	get_template_part( 'template-parts/footer/footer-nav' );
	// Copyright.
	get_template_part( 'template-parts/copyright/copyright' );
	do_action( 'ys_site_footer_append' );
	?>
</footer>
<?php
do_action( 'ys_after_site_footer' );
wp_footer();
do_action( 'ys_body_append' );
?>
</body>
</html>
