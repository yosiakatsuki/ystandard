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

get_header(); ?>
	<div class="container">
		<div class="content__wrap">
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
		</div>
	</div>
<?php get_footer(); ?>
