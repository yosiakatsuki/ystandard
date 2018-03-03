<?php
/**
 * 404ページテンプレート
 * 
 * @package ystandard
 * @author yosiakatsuki
 * @license GPL-2.0+
 */
get_header(); ?>
<div class="container">
	<div class="content-area content__wrap">
		<main id="main" class="site-main content__main">
		<?php
				get_template_part( 'template-parts/content/none' );
		?>
		</main><!-- .site-main -->
		<?php get_sidebar(); ?>
	</div><!-- .content-area -->
</div><!-- .container -->
<?php 
get_footer();
