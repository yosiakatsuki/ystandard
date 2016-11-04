<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<header class="entry-header">
		<?php the_title( '<h1 class="entry-title">', '</h1>' ); ?>

		<div class="entry-meta entry-date-container">
			<?php ys_entry_the_entry_date(false); ?>
		</div><!-- .entry-meta -->

		<?php if(has_post_thumbnail()): ?>
			<figure class="post-thumbnail">
				<?php ys_image_the_post_thumbnail(); ?>
			</figure><!-- .post-thumbnail -->
		<?php endif; ?>
	</header><!-- .entry-header -->

	<div class="entry-content">
		<?php

		the_content();

		wp_link_pages( array(
			'before'      => '<div class="page-links"><span class="page-links-title">' . 'Pages:' . '</span>',
			'after'       => '</div>',
			'link_before' => '<span>',
			'link_after'  => '</span>',
			'pagelink'    => '<span class="screen-reader-text">' . 'Page' . ' </span>%',
			'separator'   => '<span class="screen-reader-text">, </span>',
		) );
		?>
	</div><!-- .entry-content -->

	<footer class="entry-footer">
		<?php
			if ( '' !== get_the_author_meta( 'description' ) ) {
				//get_template_part( 'template-parts/biography' );
			}
		 ?>
	</footer><!-- .entry-footer -->

</article><!-- #post-## -->