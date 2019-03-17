<?php
/**
 * Template Name:1カラム(タイトル・アイキャッチ画像・パンくずなし)
 * Template Post Type:post,page
 * Description:ワンカラムテンプレート
 *
 * @package ystandard
 * @author  yosiakatsuki
 * @license GPL-2.0+
 */

get_header(); ?>
	<div class="container">
		<div class="content-area content__wrap">
			<main id="main" class="site-main content__main one-col-no-title">
				<?php do_action( 'ys_site_main_prepend' ); ?>
				<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
					<?php
					while ( have_posts() ) :
						the_post();
						?>
						<div class="entry-content entry__content--no-title">
							<?php
							the_content();
							wp_link_pages(
								array(
									'before'      => '<nav class="page-links pagination flex flex--j-center">',
									'after'       => '</nav>',
									'link_before' => '<span class="page-links__item pagination__item flex flex--c-c">',
									'link_after'  => '</span>',
									'pagelink'    => '<span class="page-links__text">%</span>',
									'separator'   => '',
								)
							);
							?>
						</div><!-- .entry-content -->
						<?php
					endwhile;
					?>
					<footer class="entry__footer">
						<?php
						// 記事下部分.
						get_template_part( 'template-parts/entry/entry-footer' );
						?>
					</footer><!-- .entry__footer -->
				</article>
				<?php do_action( 'ys_site_main_append' ); ?>
			</main><!-- .site-main -->
			<?php get_sidebar(); ?>
		</div><!-- .content-area -->
	</div><!-- .container -->
<?php get_footer(); ?>