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
		<div class="content__wrap">
			<div class="flex flex--row">
				<?php
				while ( have_posts() ) :
					the_post();
					get_template_part( 'template-parts/page/content' );
				endwhile;
				?>
				<?php get_sidebar(); ?>
			</div>
		</div>
	</div><!-- .container -->
<?php get_footer(); ?>