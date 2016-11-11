<?php
get_header(); ?>

<?php
	// パンくず
	ys_breadcrumb();
?>

<div id="primary" class="content-area">
	<main id="main" class="site-main" role="main">
		<?php
		// Start the loop.
		while ( have_posts() ) : the_post();

			// Include the single post content template.
			get_template_part( 'template-parts/content', 'single' );

			// If comments are open or we have at least one comment, load up the comment template.
			if ( !ys_is_amp() && ( comments_open() || get_comments_number() ) ) {
				comments_template();
			}

			// 次の投稿・前の投稿を入れる
			the_post_navigation( array(
				'next_text' => '<span class="meta-nav" aria-hidden="true">次の記事</span> ' .
					'<span class="post-title">%title</span>',
				'prev_text' => '<span class="meta-nav" aria-hidden="true">前の記事</span> ' .
					'<span class="post-title">%title</span>',
			) );

			// End of the loop.
		endwhile;
		?>

	</main><!-- .site-main -->

</div><!-- .content-area -->

<?php get_sidebar(); ?>
<?php get_footer(); ?>