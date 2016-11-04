<div class="author-info">
	<div class="author-avatar">
		<?php ys_image_the_user_avatar(); ?>
	</div><!-- .author-avatar -->

	<div class="author-description">
		<h2 class="author-title">
			<a class="author-link" href="<?php echo esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ); ?>" rel="author"><?php ys_entry_the_entry_author(); ?></a>
		</h2>

		<p class="author-bio">
			<?php the_author_meta( 'description' ); ?>
		</p><!-- .author-bio -->
	</div><!-- .author-description -->
</div><!-- .author-info -->