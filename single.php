<?php
get_header(); ?>
<div class="container">
	<div class="content-area content__wrap">
		<main id="main" class="site-main content__main">
			<?php
			while ( have_posts() ) : the_post();
				get_template_part( 'template-parts/content/single' );
				/**
				 * 関連記事
				 */
				//ys_template_the_related_post();
				/**
				 * コメント
				 */
				// if ( ! ys_is_amp() && ( comments_open() || get_comments_number() ) ) {
				// 	comments_template();
				// }
				/**
				 * 前の記事・次の記事
				 */
				//ys_template_the_post_paging();
			endwhile;
			?>
		</main><!-- .site-main -->
		<?php get_sidebar(); ?>
	</div><!-- .content-area -->
</div><!-- .container -->
<?php get_footer(); ?>