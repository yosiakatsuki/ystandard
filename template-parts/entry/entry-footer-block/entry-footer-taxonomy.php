<?php
/**
 * タクソノミー表示
 *
 * @package ystandard
 * @author  yosiakatsuki
 * @license GPL-2.0+
 */

if ( ! ys_get_option( 'ys_show_post_category' ) ) {
	return;
}
$category = ys_get_the_category();
$tags     = ys_get_the_tag_list( ' /' );
if ( empty( $category ) && empty( $tags ) ) {
	return;
}
?>
<aside class="entry__footer-taxonomy">
	<?php if ( ! empty( $category ) ) : ?>
	<div class="entry__footer-category flex">
		<h2 class="entry__footer-tax-title clear-headline"><i class="fa fa-folder-o entry__footer-tax-icon" aria-hidden="true"></i>Category :</h2>
		<p class="entry__footer-tax-container color__font-sub"><?php ys_the_array_implode( $category, ' /' ); ?></p>
	</div><!-- .entry__footer-category -->
	<?php endif; ?>
	<?php if ( $tags ) : ?>
		<div class="entry__footer-tag flex">
			<h2 class="entry__footer-tax-title clear-headline"><i class="fa fa-tag entry__footer-tax-icon" aria-hidden="true"></i>Tags :</h2>
			<p class="entry__footer-tax-container color__font-sub"><?php echo $tags; ?></p>
		</div><!-- .entry__footer-tag -->
	<?php endif; ?>
</aside><!-- .footer-taxonomy -->