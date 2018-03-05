<?php
/**
 * 投稿詳細ページテンプレート
 *
 * @package ystandard
 * @author yosiakatsuki
 * @license GPL-2.0+
 */

get_header(); ?>
<div class="container">
	<div class="content-area content__wrap">
		<main id="main" class="site-main content__main">
			<?php do_action( 'ys_site_main_prepend' ); ?>
			<?php
			while ( have_posts() ) :
				the_post();
				get_template_part( 'template-parts/content/single' );
			endwhile;
			?>
			<?php do_action( 'ys_site_main_append' ); ?>
		</main><!-- .site-main -->
		<?php get_sidebar(); ?>
	</div><!-- .content-area -->
</div><!-- .container -->
<?php get_footer(); ?>
