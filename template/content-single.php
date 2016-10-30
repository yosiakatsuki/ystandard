<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

	<div class="entry-meta">
		<?php
			ys_entry_the_entry_date();
			ys_category_the_post_categorys();
		?>
	</div><!-- .entry-meta -->

	<header class="entry-header">
		<?php the_title( '<h1 class="entry-title">', '</h1>' ); ?>
	</header><!-- .entry-header -->

	<?php if(has_post_thumbnail()): ?>
		<div class="post-thumbnail">
			<?php ys_image_the_post_thumbnail(); ?>
		</div><!-- .post-thumbnail -->
	<?php endif; ?>

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
