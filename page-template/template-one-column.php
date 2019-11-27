<?php
/**
 * Template Name:1カラム
 * Template Post Type:post,page
 * Description:ワンカラムテンプレート
 *
 * @package ystandard
 * @author  yosiakatsuki
 * @license GPL-2.0+
 */

ys_get_header(); ?>
	<div class="container">
		<?php
		while ( have_posts() ) :
			the_post();
			if ( is_page() ) {
				get_template_part( 'template-parts/page/content' );
			} else {
				get_template_part( 'template-parts/single/content' );
			}
		endwhile;
		?>
		<?php // get_sidebar. ?>
	</div><!-- .container -->
<?php get_footer(); ?>