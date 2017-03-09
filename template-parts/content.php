<a class="entry-container" href="<?php the_permalink(); ?>" title="<?php the_title(); ?>" rel="bookmark">
	<article id="post-<?php the_ID(); ?>" <?php post_class( array('post-list','clearfix') ); ?> itemscope itemtype="http://schema.org/BlogPosting" itemref="masthead">
		<meta itemprop="url" content="<?php the_permalink(); ?>" />
		<?php get_template_part('template-parts/content','list-detail'); ?>
	</article><!-- #post-## -->
</a>