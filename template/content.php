<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

	<header class="entry-header">
		<?php if ( is_sticky() && is_home() && ! is_paged() ) : ?>
			<span class="sticky-post">おすすめ！</span>
		<?php endif; ?>

		<?php the_title( sprintf( '<h2 class="entry-title"><a href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a></h2>' ); ?>
	</header><!-- .entry-header -->

	<?php if(has_post_thumbnail()): ?>
		<div class="post-thumbnail">
			<?php ys_image_the_post_thumbnail(); ?>
		</div><!-- .post-thumbnail -->
	<?php endif; ?>

	<div class="entry-meta">
		<div class="entry-meta-author">
			<?php ys_entry_the_entry_author(); ?>
		</div>
		<div class="entry-meta-date">
			<?php ys_entry_the_entry_date(false); ?>
		</div>
		<div class="entry-meta-cat">
			<?php ys_category_the_post_categorys(); ?>
		</div>
	</div><!-- .entry-meta -->

	<div class="entry-content">
		<?php

			the_content();

		?>
	</div><!-- .entry-content -->

	<footer class="entry-footer">
		<?php //twentysixteen_entry_meta(); ?>
	</footer><!-- .entry-footer -->
</article><!-- #post-## -->