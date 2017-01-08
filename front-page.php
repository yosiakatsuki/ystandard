<?php

get_header(); ?>


	<div id="primary" class="content-area">
		<main id="main" class="site-main" role="main">

		<?php if ( have_posts() ) : ?>

			<?php
			while ( have_posts() ) : the_post();

				get_template_part( 'template-parts/content', '' );

			endwhile;

			// ページネーション
			ys_pagination();

		else :
			get_template_part( 'template-parts/content', 'none' );

		endif;
		?>

		</main><!-- .site-main -->
	</div><!-- .content-area -->

<?php get_sidebar(); ?>
<?php get_footer(); ?>