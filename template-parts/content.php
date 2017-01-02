<article id="post-<?php the_ID(); ?>" <?php post_class(); ?> itemscope itemtype="http://schema.org/BlogPosting" itemref="masthead">
	<meta itemscope id="EntityOfPageid-<?php the_ID(); ?>" itemprop="mainEntityOfPage" itemType="https://schema.org/WebPage" itemid="<?php echo the_permalink(); ?>"/>

	<header class="entry-header">
		<?php if ( is_sticky() && is_home() && ! is_paged() ) : ?>
			<span class="sticky-post">おすすめ！</span>
		<?php endif; ?>

		<?php the_title( sprintf( '<h2 class="entry-title" itemprop="headline name"><a href="%s" rel="bookmark" itemprop="url">', esc_url( get_permalink() ) ), '</a></h2>' ); ?>

		<div class="entry-meta entry-date-container">
			<?php ys_template_the_entry_date(false); ?>
		</div><!-- .entry-meta -->

		<?php if(has_post_thumbnail()): ?>
			<figure class="post-thumbnail">
				<a href="<?php the_permalink(); ?>" itemprop="image" itemscope itemtype="https://schema.org/ImageObject">
					<?php ys_template_the_post_thumbnail(); ?>
				</a>
			</figure><!-- .post-thumbnail -->
		<?php endif; ?>

		<div class="entry-meta">
			<div class="entry-meta-cat entry-meta-left"><?php ys_template_the_post_categorys(); ?></div>
			<div class="entry-meta-author entry-meta-right"><?php ys_template_the_entry_author(false); ?></div>
		</div>
	</header><!-- .entry-header -->



	<div class="entry-content" itemprop="articleBody">
		<?php
			the_excerpt();
		?>
	</div><!-- .entry-content -->

	<footer class="entry-footer">
		<p class="entry-more">
			<a class="more-link" href="<?php the_permalink(); ?>">この記事の続きを読む »</a>
		</p>
	</footer><!-- .entry-footer -->
	<meta itemprop="author" content="<?php the_author(); ?>" />
</article><!-- #post-## -->