<meta itemprop="author" content="<?php the_author(); ?>" />
<meta id="EntityOfPageid-<?php the_ID(); ?>" itemscope itemprop="mainEntityOfPage" itemtype="https://schema.org/Webpage" itemid="<?php the_permalink(); ?>" content="">

<div class="post-thumbnail-container">
	<figure class="post-thumbnail post-list-thumbnail-center" itemprop="image" itemscope itemtype="https://schema.org/ImageObject">
		<?php ys_template_the_post_thumbnail('yslistthumb',array(250,150)); ?>
	</figure><!-- .post-thumbnail -->
	<div class="entry-more">
		<p class="entry-more-text"><?php ys_template_the_entry_more_text(); ?></p>
	</div>
</div>

<div class="entry-detail">
	<div class="entry-meta">
		<?php
			$cat = get_the_category();
			if($cat) : ?>
			<div class="entry-meta-cat entry-meta-left"><?php ys_template_the_post_categorys(1,false); ?></div>
		<?php endif; ?>
		<div class="entry-date-container entry-meta-right"><?php ys_template_the_entry_date(false); ?></div>
	</div>

	<?php
		the_title('<h3 class="entry-title" itemprop="headline name">', '</h3>' );
	?>

	<div class="entry-excerpt" itemprop="articleBody">
		<?php
			the_excerpt();
		?>
	</div><!-- .entry-content -->

	<div class="entry-meta entry-meta-author"><?php ys_template_the_entry_author(false); ?></div>
</div>