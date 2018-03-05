<?php
/**
 * @package ystandard
 * @author yosiakatsuki
 * @license GPL-2.0+
 */
get_header(); ?>
<div class="container">
	<div class="content-area content__wrap">
		<main id="main" class="site-main content__main">
		<?php do_action( 'ys_site_main_prepend' ) ?>
		<?php if ( have_posts() ) : ?>
			<header class="page-header archive__header">
				<?php
					the_archive_title( '<h2 class="page-title archive__title clear-headline">', '</h2>' );
					the_archive_description( '<div class="taxonomy-description archive__dscr color__font-sub">', '</div>' );
				?>
			</header><!-- .page-header -->
			<div class="archive__list">
				<?php
					while ( have_posts() ) : the_post();
						get_template_part( 'template-parts/content/archive', ys_get_option( 'ys_archive_type' ) );
					endwhile;
				?>
			</div><!-- .archive__list -->
			<?php
				/**
				 * ページネーション
				 */
				get_template_part( 'template-parts/pagination/pagination' );
				?>
		<?php
			else :
				get_template_part( 'template-parts/content/none' );
			endif;
		?>
		<?php do_action( 'ys_site_main_append' ) ?>
		</main><!-- .site-main -->
		<?php get_sidebar(); ?>
	</div><!-- .content-area -->
</div><!-- .container -->
<?php get_footer(); ?>
