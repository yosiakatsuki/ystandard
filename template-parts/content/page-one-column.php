<?php
/**
 * 固定ページテンプレート(1カラム)
 *
 * @package ystandard
 * @author yosiakatsuki
 * @license GPL-2.0+
 */

?>
<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<header class="entry-header entry__header">
		<div class="entry__header--one-col section--full flex flex--a-end">
			<?php
			do_action( 'ys_before_entry_title' );
			the_title( '<h1 class="entry-title entry__title entry__title--one-col">', '</h1>' );
			do_action( 'ys_after_entry_title' );

			if ( ys_is_active_post_thumbnail() ) :
			?>
			<figure class="post-thumbnail entry__thumbnail">
				<?php
				the_post_thumbnail( 'post-thumbnail', array(
					'id'    => 'entry__thumbnail-image',
					'class' => 'entry__thumbnail-image',
				) );
				?>
			</figure><!-- .post-thumbnail -->
			<?php endif; ?>
		</div><!-- .entry__header-one-col -->
		<?php
			get_template_part( 'template-parts/entry/entry-header' );
		?>
	</header><!-- .entry-header -->
	<div class="entry-content entry__content">
		<?php
			the_content();
			wp_link_pages( array(
				'before'      => '<nav class="page-links pagination flex flex--j-center">',
				'after'       => '</nav>',
				'link_before' => '<span class="page-links__item pagination__item flex flex--c-c">',
				'link_after'  => '</span>',
				'pagelink'    => '<span class="page-links__text">%</span>',
				'separator'   => '',
			) );
		?>
	</div><!-- .entry-content -->
	<footer class="entry__footer">
		<?php
			// 記事下部分.
			get_template_part( 'template-parts/entry/entry-footer' );
		?>
	</footer><!-- .entry__footer -->
</article>