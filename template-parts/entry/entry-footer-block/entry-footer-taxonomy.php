<?php
	/**
	 * タクソノミー表示
	 */
?>
<aside class="entry__footer-taxonomy">
	<div class="entry__footer-category flex">
		<h2 class="entry__footer-tax-title"><i class="fa fa-folder-o entry__footer-tax-icon" aria-hidden="true"></i>Category :</h2>
		<p class="entry__footer-tax-container color__font-sub"><?php ys_the_category_list( ' /' ); ?></p>
	</div><!-- .entry__footer-category -->
	<?php
		$tags = ys_get_the_tag_list( ' /' );
		if( $tags ): ?>
		<div class="entry__footer-tag flex">
			<h2 class="entry__footer-tax-title"><i class="fa fa-tag entry__footer-tax-icon" aria-hidden="true"></i>Tags :</h2>
			<p class="entry__footer-tax-container color__font-sub"><?php echo $tags; ?></p>
		</div><!-- .entry__footer-tag -->
	<?php endif; ?>
</aside><!-- .footer-taxonomy -->