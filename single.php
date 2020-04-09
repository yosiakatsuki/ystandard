<?php
/**
 * 投稿詳細ページテンプレート
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
				ys_get_template_part( 'template-parts/single/content' );
			endwhile;
			?>
			<?php get_sidebar(); ?>
		</div>
	</div><!-- .container -->
<?php get_footer(); ?>
