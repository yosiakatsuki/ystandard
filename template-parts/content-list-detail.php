<meta itemscope id="EntityOfPageid-<?php the_ID(); ?>" itemprop="mainEntityOfPage" itemType="https://schema.org/WebPage" itemid="<?php echo the_permalink(); ?>"/>
<meta itemprop="author" content="<?php the_author(); ?>" />

<div class="post-thumbnail-container">
	<figure class="post-thumbnail" itemprop="image" itemscope itemtype="https://schema.org/ImageObject">
		<?php ys_template_the_post_thumbnail('yslistthumb',array(250,150)); ?>
	</figure><!-- .post-thumbnail -->
	<div class="entry-more">
		<p class="entry-more-text"><?php ys_template_the_entry_more_text(); ?></p>
	</div>
</div>

<div class="entry-detail">
	<div class="entry-meta">
		<div class="entry-meta-cat entry-meta-left"><?php ys_template_the_post_categorys(1,false); ?></div>
		<div class="entry-date-container entry-meta-right"><?php ys_template_the_entry_date(false); ?></div>
	</div>

	<?php the_title('<h2 class="entry-title" itemprop="headline name">', '</h2>' ); ?>

	<div class="entry-excerpt" itemprop="articleBody">
		<?php
			the_excerpt();
		?>
	</div><!-- .entry-content -->

	<div class="entry-meta entry-meta-author"><?php ys_template_the_entry_author(false); ?></div>
</div>