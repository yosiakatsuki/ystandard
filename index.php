<?php
/**
 * @package ystandard
 * @author yosiakatsuki
 * @license GPL-2.0+
 */
get_header(); ?>
<div class="container">
	<section class="content-area content__wrap row">
		<main id="main" class="site-main content__main">
		<?php if ( have_posts() ) : ?>
			<header class="page-header archive__header">
				<?php
					the_archive_title( '<h2 class="page-title archive__title">', '</h2>' );
					the_archive_description( '<div class="taxonomy-description archive__dscr color__font-sub">', '</div>' );
				?>
			</header><!-- .page-header -->
			<div class="archive__list">
				<?php
					while ( have_posts() ) : the_post();
						get_template_part( 'template-parts/content/archive' );
					endwhile;
					/**
					 * ページネーション
					 */
					get_template_part( 'template-parts/pagination/pagination' );
				?>
			</div><!-- .archive__list -->
		<?php
			else :
				get_template_part( 'template-parts/content/none' );
			endif;
		?>
		</main><!-- .site-main -->
		<?php get_sidebar(); ?>
	</section><!-- .content-area -->
</div><!-- .container -->
<?php get_footer(); ?>