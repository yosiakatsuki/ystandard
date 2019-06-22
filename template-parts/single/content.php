<?php
/**
 * 投稿詳細ページテンプレート
 *
 * @package ystandard
 * @author  yosiakatsuki
 * @license GPL-2.0+
 */

?>
<main id="main" class="site-main content__main flex__col">
	<?php do_action( 'ys_content_main_prepend' ); ?>
	<article id="post-<?php the_ID(); ?>" <?php post_class( array( 'singular-article' ) ); ?>>
		<?php get_template_part( 'template-parts/single/header' ); ?>
		<div class="entry-content entry__content">
			<?php
			the_content();
			get_template_part( 'template-parts/single/pagination' );
			?>
		</div><!-- .entry-content -->
		<?php get_template_part( 'template-parts/single/footer' ); ?>
	</article>
	<?php do_action( 'ys_content_main_append' ); ?>
</main><!-- .site-main -->