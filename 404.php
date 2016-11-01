<?php
get_header(); ?>

	<div id="primary" class="content-area">
		<main id="main" class="site-main" role="main">

			<section class="error-404 not-found">
				<header class="page-header">
					<?php the_archive_title( '<h2 class="page-title">', '</h2>' ); ?>
				</header><!-- .page-header -->

				<div class="page-content">


					<?php get_search_form(); ?>
				</div><!-- .page-content -->
			</section><!-- .error-404 -->

		</main><!-- .site-main -->

	</div><!-- .content-area -->

<?php get_sidebar(); ?>
<?php get_footer(); ?>