<?php
/**
 * Template Name:1カラム
 * Template Post Type:post,page
 * Description:ワンカラムテンプレート
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
				if ( is_page() ) {
					get_template_part( 'template-parts/content/page', ys_get_page_template() );
				} else {
					get_template_part( 'template-parts/content/single', ys_get_single_template() );
				}
			endwhile;
			?>
			<?php do_action( 'ys_site_main_append' ); ?>
		</main><!-- .site-main -->
		<?php // get_sidebar. ?>
	</div><!-- .content-area -->
</div><!-- .container -->
<?php get_footer(); ?>