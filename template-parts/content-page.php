<article id="post-<?php the_ID(); ?>" <?php post_class(); ?> itemscope itemtype="http://schema.org/BlogPosting" itemref="masthead" >
	<meta itemscope id="EntityOfPageid-<?php the_ID(); ?>" itemscope itemprop="mainEntityOfPage" itemtype="https://schema.org/Webpage" itemid="<?php echo the_permalink(); ?>">

	<header class="entry-header">
		<?php the_title( '<h1 class="entry-title" itemprop="headline name">', '</h1>' ); ?>

		<div class="entry-meta entry-date-container">
			<?php ys_template_the_entry_date(false); ?>
		</div><!-- .entry-meta -->

		<?php if(has_post_thumbnail()): ?>
			<figure class="post-thumbnail" itemprop="image" itemscope itemtype="https://schema.org/ImageObject">
				<?php ys_template_the_post_thumbnail(); ?>
			</figure><!-- .post-thumbnail -->
		<?php endif; ?>
	</header><!-- .entry-header -->

	<div class="entry-content"  itemprop="articleBody">
		<?php

		the_content();

		ys_template_the_link_pages();

		?>
	</div><!-- .entry-content -->

	<footer class="entry-footer">
		<?php
			get_template_part( 'template-parts/biography' );
		 ?>
	</footer><!-- .entry-footer -->

</article><!-- #post-## -->