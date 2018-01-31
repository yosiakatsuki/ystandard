<?php
get_header(); ?>
<div class="container">
	<div class="content-area content row">
		<main id="main" class="site-main content__main">
			<?php
			while ( have_posts() ) : the_post();
				get_template_part( 'template-parts/content/page' );
			endwhile;
			?>
		</main><!-- .site-main -->
		<?php get_sidebar(); ?>
	</div><!-- .content-area -->
</div><!-- .container -->
<?php get_footer(); ?>