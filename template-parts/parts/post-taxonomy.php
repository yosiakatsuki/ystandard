<?php
/**
 * タクソノミー表示
 *
 * @package ystandard
 * @author  yosiakatsuki
 * @license GPL-2.0+
 */


/**
 * TODO:ショートコード化
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
<div class="entry__footer-taxonomy">
	<?php if ( ! empty( $category ) ) : ?>
		<div class="entry__footer-category flex">
			<span class="entry__footer-tax-title clear-headline"><i class="far fa-folder entry__footer-tax-icon"></i></span>
			<p class="entry__footer-tax-container color__font-sub"><?php ys_the_array_implode( $category, ' /' ); ?></p>
		</div><!-- .entry__footer-category -->
	<?php endif; ?>
	<?php if ( $tags ) : ?>
		<div class="entry__footer-tag flex">
			<span class="entry__footer-tax-title clear-headline"><i class="fas fa-tags entry__footer-tax-icon"></i></span>
			<p class="entry__footer-tax-container color__font-sub"><?php echo $tags; ?></p>
		</div><!-- .entry__footer-tag -->
	<?php endif; ?>
</div><!-- .footer-taxonomy -->