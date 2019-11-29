<?php
/**
 * 記事一覧テンプレート
 *
 * @package ystandard
 * @author  yosiakatsuki
 * @license GPL-2.0+
 */

ys_get_header(); ?>
<div class="container">
	<div class="content__container">
		<?php if ( have_posts() ) : ?>
			<?php get_template_part( 'template-parts/archive/content' ); ?>
		<?php else : ?>
			<?php get_template_part( 'template-parts/404/content' ); ?>
		<?php endif; ?>
		<?php get_sidebar(); ?>
	</div>
</div><!-- .container -->
<?php get_footer(); ?>
