<?php
/**
 * 固定ページテンプレート
 *
 * @package ystandard
 * @author  yosiakatsuki
 * @license GPL-2.0+
 */

get_header(); ?>
	<div class="container">
		<div class="flex flex--row">
			<div class="content-area flex__col--1">
				<?php
				while ( have_posts() ) :
					the_post();
					get_template_part( 'template-parts/page/content', ys_get_page_template() );
				endwhile;
				?>
				<?php get_sidebar(); ?>
			</div><!-- .content-area -->
		</div>
	</div><!-- .container -->
<?php get_footer(); ?>