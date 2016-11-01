<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<header class="entry-header">
		<?php if ( is_sticky() && is_home() && ! is_paged() ) : ?>
			<span class="sticky-post">おすすめ！</span>
		<?php endif; ?>

		<?php the_title( sprintf( '<h2 class="entry-title"><a href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a></h2>' ); ?>
	</header><!-- .entry-header -->

	<div class="entry-content">
		<?php

			the_content();

		?>
	</div><!-- .entry-content -->

	<footer class="entry-footer">
		<?php //twentysixteen_entry_meta(); ?>
	</footer><!-- .entry-footer -->
</article><!-- #post-## -->