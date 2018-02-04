<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<header class="entry-header entry__header">
		<?php
			the_title( '<h1 class="entry-title entry__title">', '</h1>' );
		?>
		<?php if( ys_is_active_post_thumbnail() ): ?>
			<figure class="post-thumbnail entry__thumbnail">
				<?php the_post_thumbnail() ?>
			</figure><!-- .post-thumbnail -->
		<?php endif; ?>
		<?php get_template_part( 'template-parts/entry/entry-meta' ); ?>
	</header><!-- .entry-header -->
	<div class="entry-content entry__content">
		<?php
			the_content();
			wp_link_pages( array(
						'before'      => '<div class="page-links">',
						'after'       => '</div>',
						'link_before' => '<span class="page-text">',
						'link_after'  => '</span>',
						'pagelink'    => '%',
						'separator'   => '',
					) );
		?>
	</div><!-- .entry-content -->
	<footer class="entry__footer">
		<?php
			//記事下部分
			get_template_part( 'template-parts/entry/entry-footer' );
		?>
	</footer><!-- .entry__footer -->
</article>