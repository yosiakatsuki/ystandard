<?php
/**
 * Template Name:1カラム(ワイド)
 * Template Post Type:post,page
 * Description:ワンカラムテンプレート(ワイド)
 *
 * @package ystandard
 * @author  yosiakatsuki
 * @license GPL-2.0+
 */

get_header(); ?>
	<div class="container">
		<?php
		while ( have_posts() ) :
			the_post();
			if ( is_page() ) {
				ys_get_template_part( 'template-parts/page/content' );
			} else {
				ys_get_template_part( 'template-parts/single/content' );
			}
		endwhile;
		?>
		<?php // get_sidebar. ?>
	</div><!-- .container -->
<?php get_footer(); ?>
