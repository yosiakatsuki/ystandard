<?php
get_header(); ?>

<?php
	// パンくず
	ys_breadcrumb();
?>

	<div id="primary" class="content-area">
		<main id="main" class="site-main" role="main">

		<?php if ( have_posts() ) : ?>

			<header class="page-header">
				<?php
					the_archive_title( '<h2 class="page-title">', '</h2>' );
					the_archive_description( '<div class="taxonomy-description">', '</div>' );
				?>
			</header><!-- .page-header -->

			<?php
			// Start the Loop.
			while ( have_posts() ) : the_post();

				get_template_part( 'template/content', get_post_format() );

			// End the loop.
			endwhile;

			// ページネーション
			ys_pagenation();

		// If no content, include the "No posts found" template.
		else :
			get_template_part( 'template/content', 'none' );

		endif;
		?>

		</main><!-- .site-main -->
	</div><!-- .content-area -->

<?php get_sidebar(); ?>
<?php get_footer(); ?>