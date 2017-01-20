<?php
get_header(); ?>

<div id="primary" class="content-area">
	<main id="main" class="site-main" role="main">
		<?php
		while ( have_posts() ) : the_post();

			get_template_part( 'template-parts/content', 'single' );

			// CTA
			ys_template_the_entry_foot_cta();

			// 書いた人
			ys_template_the_biography();

			// カテゴリー・タグ
			ys_template_the_taxonomy_list();

			// 関連記事
			ys_template_the_related_post();

			// コメント
			if ( !ys_is_amp() && ( comments_open() || get_comments_number() ) ) {
				comments_template();
			}

			// 前の記事・次の記事
			ys_template_the_post_paging();

		endwhile;
		?>

	</main><!-- .site-main -->

</div><!-- .content-area -->

<?php get_sidebar(); ?>
<?php get_footer(); ?>