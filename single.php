<?php
/**
 * @package ystandard
 * @author yosiakatsuki
 * @license GPL-2.0+
 */
get_header(); ?>
<div class="container">
	<div class="content-area content__wrap">
		<main id="main" class="site-main content__main">
			<?php
			while ( have_posts() ) : the_post();
				get_template_part( 'template-parts/content/single' );
			endwhile;
			?>
		</main><!-- .site-main -->
		<?php get_sidebar(); ?>
	</div><!-- .content-area -->
</div><!-- .container -->
<?php get_footer(); ?>
