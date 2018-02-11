<article id="post-<?php the_ID(); ?>" <?php post_class( array( 'entry-list', 'clearfix' ) ); ?>>
	<div class="entry-list__thumbnail entry-list__mask-wrap">
		<a href="<?php the_permalink(); ?>" class="entry-list__link">
			<?php if( has_post_thumbnail() ): ?>
				<figure class="entry-list__figure">
					<?php the_post_thumbnail(); ?>
				</figure>
			<?php else: ?>
				<div class="entry-list__no-img flex flex--c-c">
					<i class="fa fa-picture-o" aria-hidden="true"></i>
				</div><!-- .entry-list__no-img -->
			<?php endif; ?>
			<div class="entry-list__mask flex flex--c-c">
				<p class="entry-list__mask-text "><?php ys_the_entry_read_more_text(); ?></p>
			</div><!-- .entry-list__mask -->
		</a>
	</div>
	<div class="entry-list__detail">
		<div class="entry__meta entry-list__meta color__font-sub flex flex--j-between">
			<p class="entry-list__cat"><i class="fa fa-folder-o" aria-hidden="true"></i><?php ys_the_entry_category( false ); ?></p><!-- .entry-list__cat -->
			<p class="entry-list__date"><i class="fa fa-calendar" aria-hidden="true"></i><time datetime="<?php the_time( 'Y-m-d' ); ?>"><?php the_time( get_option( 'date_format' ) ); ?></time></p><!-- .entry-list__date -->
		</div>
		<?php
			the_title(
				'<h3 class="entry-title entry-list__title"><a class="entry-list__link" href="' . get_the_permalink() . '">',
				'</a></h3>'
			);
		?>
		<div class="entry-excerpt entry-list__excerpt color__font-sub">
			<?php the_excerpt(); ?>
		</div><!-- .entry-list__excerpt -->
		<?php get_template_part( 'template-parts/entry/entry-author' ); ?>
	</div><!-- .entry-list__detail -->
</article><!-- #post-## -->