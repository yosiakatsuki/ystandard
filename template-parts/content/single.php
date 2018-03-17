<?php
/**
 * 個別投稿テンプレート
 *
 * @package ystandard
 * @author yosiakatsuki
 * @license GPL-2.0+
 */

?>
<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<header class="entry-header entry__header">
		<?php
			the_title( '<h1 class="entry-title entry__title">', '</h1>' );
		?>
		<?php if ( ys_is_active_post_thumbnail() ) : ?>
			<figure class="post-thumbnail entry__thumbnail">
				<?php
				the_post_thumbnail( 'post-thumbnail', array(
					'id'    => 'entry__thumbnail__image',
					'class' => 'entry__thumbnail__image',
				) );
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
</article><!-- #post-## -->
<?php
	/**
	 * 関連記事
	 */
	get_template_part( 'template-parts/entry/entry-related' );
	/**
	 * コメント
	 */
	get_template_part( 'template-parts/entry/entry-comments' );
	/**
	 * 前の記事・次の記事
	 */
	get_template_part( 'template-parts/entry/entry-paging' );