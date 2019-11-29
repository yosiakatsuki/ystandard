<?php
/**
 * 固定ページテンプレート
 *
 * @package ystandard
 * @author  yosiakatsuki
 * @license GPL-2.0+
 */

ys_get_header(); ?>
	<div class="container">
		<div class="content__container">
			<?php
			while ( have_posts() ) :
				the_post();
				get_template_part( 'template-parts/page/content' );
			endwhile;
			?>
			<?php get_sidebar(); ?>
		</div>
	</div><!-- .container -->
<?php get_footer(); ?>