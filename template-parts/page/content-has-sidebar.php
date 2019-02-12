<?php
/**
 * 固定ページテンプレート
 *
 * @package ystandard
 * @author  yosiakatsuki
 * @license GPL-2.0+
 */

?>
<main id="main" class="site-main content__main">
	<?php do_action( 'ys_content_main_prepend' ); ?>
	<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
		<header class="entry-header entry__header">
			<?php
			do_action( 'ys_before_entry_title' );
			the_title( '<h1 class="entry-title singular__title page__title">', '</h1>' );
			do_action( 'ys_after_entry_title' );

			if ( ys_is_active_post_thumbnail() ) :
				?>
				<figure class="post-thumbnail singular__thumbnail page__thumbnail text--center">
					<?php
					the_post_thumbnail(
						'post-thumbnail',
						array(
							'id'    => 'entry__thumbnail-image',
							'class' => 'entry__thumbnail-image',
						)
					);
					?>
				</figure><!-- .post-thumbnail -->
			<?php endif; ?>
			<?php
			get_template_part( 'template-parts/entry/entry-header' );
			?>
		</header><!-- .entry-header -->
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