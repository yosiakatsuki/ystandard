<article id="post-<?php the_ID(); ?>" <?php post_class(); ?> itemscope itemtype="http://schema.org/BlogPosting" itemref="masthead">
	<meta itemscope id="EntityOfPageid-<?php the_ID(); ?>" itemprop="mainEntityOfPage" itemType="https://schema.org/WebPage" itemid="<?php echo the_permalink(); ?>"/>

	<header class="entry-header">

		<a class="post-thumbnail-container" href="<?php the_permalink(); ?>">
			<figure class="post-thumbnail" itemprop="image" itemscope itemtype="https://schema.org/ImageObject">
				<?php ys_template_the_post_thumbnail('thumbplist400'); ?>
			</figure><!-- .post-thumbnail -->
			<div class="entry-more">
				<p class="entry-more-text"><?php ys_template_the_entry_more_text(); ?></p>
			</div>
		</a>

		<div class="entry-meta">
			<div class="entry-meta-cat entry-meta-left"><?php ys_template_the_post_categorys(); ?></div>
			<div class="entry-date-container entry-meta-right"><?php ys_template_the_entry_date(false); ?></div>
		</div>

		<?php the_title( sprintf( '<h2 class="entry-title" itemprop="headline name"><a href="%s" rel="bookmark" itemprop="url">', esc_url( get_permalink() ) ), '</a></h2>' ); ?>

	</header><!-- .entry-header -->

	<div class="entry-excerpt" itemprop="articleBody">
		<?php
			the_excerpt();
		?>
	</div><!-- .entry-content -->
	<div class="entry-meta entry-meta-author"><?php ys_template_the_entry_author(false); ?></div>

	<meta itemprop="author" content="<?php the_author(); ?>" />
</article><!-- #post-## -->