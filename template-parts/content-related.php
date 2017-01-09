<a class="entry-container" href="<?php the_permalink(); ?>" title="<?php the_title(); ?>" rel="bookmark" itemprop="url">
	<div id="post-<?php the_ID(); ?>" <?php post_class( array('post-list','clearfix') ); ?> itemscope itemtype="http://schema.org/BlogPosting" itemref="masthead">
		<meta itemscope id="EntityOfPageid-<?php the_ID(); ?>" itemprop="mainEntityOfPage" itemType="https://schema.org/WebPage" itemid="<?php echo the_permalink(); ?>"/>
		<meta itemprop="author" content="<?php the_author(); ?>" />

			<div class="post-thumbnail-container">
				<figure class="post-thumbnail" itemprop="image" itemscope itemtype="https://schema.org/ImageObject">
					<?php ys_template_the_post_thumbnail('yslistthumb'); ?>
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

				<?php the_title('<h3 class="entry-title" itemprop="headline name">', '</h3>' ); ?>

				<div class="entry-excerpt" itemprop="articleBody">
					<?php
						the_excerpt();
					?>
				</div><!-- .entry-content -->

				<div class="entry-meta entry-meta-author"><?php ys_template_the_entry_author(false); ?></div>
			</div>
	</div><!-- #post-## -->
</a>