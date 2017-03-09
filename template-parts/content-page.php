<article id="post-<?php the_ID(); ?>" <?php post_class(); ?> itemscope itemtype="http://schema.org/BlogPosting" itemref="masthead biography">
	<meta id="EntityOfPageid-<?php the_ID(); ?>" itemscope itemprop="mainEntityOfPage" itemtype="https://schema.org/Webpage" itemid="<?php the_permalink(); ?>" content="">

	<header class="entry-header">
		<?php
		// ヒーローエリア
			ys_template_the_post_hero();

			the_title( '<h1 class="entry-title" itemprop="headline name">', '</h1>' );
		?>

		<div class="entry-meta entry-date-container">
			<?php ys_template_the_entry_date(); ?>
		</div><!-- .entry-meta -->

		<?php if(has_post_thumbnail() && ys_get_setting('ys_hide_post_thumbnail') == 0): ?>
			<figure class="post-thumbnail" itemprop="image" itemscope itemtype="https://schema.org/ImageObject">
				<?php ys_template_the_post_thumbnail(); ?>
			</figure><!-- .post-thumbnail -->
		<?php endif; ?>
	</header><!-- .entry-header -->

	<div class="entry-content" itemprop="articleBody">
		<?php

			the_content();

			ys_template_the_link_pages();

		?>
	</div><!-- .entry-content -->

</article><!-- #post-## -->