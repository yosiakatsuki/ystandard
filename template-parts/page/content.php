<?php
/**
 * 固定ページテンプレート
 *
 * @package ystandard
 * @author  yosiakatsuki
 * @license GPL-2.0+
 */

?>
<div class="flex__col content__wrap">
	<main id="main" class="site-main content__main">
		<?php do_action( 'ys_content_main_prepend' ); ?>
		<article id="post-<?php the_ID(); ?>" <?php post_class( array( 'singular-article' ) ); ?>>
			<?php get_template_part( 'template-parts/page/header' ); ?>
			<div class="entry-content entry__content">
				<?php
				the_content();
				get_template_part( 'template-parts/page/pagination' );
				?>
			</div><!-- .entry-content -->
			<?php get_template_part( 'template-parts/page/footer' ); ?>
		</article>
		<?php do_action( 'ys_content_main_append' ); ?>
	</main><!-- .site-main -->
</div>
