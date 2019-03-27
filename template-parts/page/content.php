<?php
/**
 * 固定ページテンプレート(1カラム)
 *
 * @package ystandard
 * @author  yosiakatsuki
 * @license GPL-2.0+
 */

?>
<main id="main" class="site-main content__main flex__col">
	<?php do_action( 'ys_content_main_prepend' ); ?>
	<article id="post-<?php the_ID(); ?>" <?php post_class( 'content__article' ); ?>>
		<?php get_template_part( 'template-parts/page/header' ); ?>
		<div class="entry-content entry__content">
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
		<footer class="entry__footer">
			<?php
			// 記事下部分.
			get_template_part( 'template-parts/entry/entry-footer' );
			?>
		</footer><!-- .entry__footer -->
	</article>
	<?php do_action( 'ys_content_main_append' ); ?>
</main><!-- .site-main -->