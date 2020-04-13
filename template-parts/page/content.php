<?php
/**
 * 固定ページテンプレート
 *
 * @package ystandard
 * @author  yosiakatsuki
 * @license GPL-2.0+
 */

?>
<main id="main" class="content__main">
	<?php do_action( 'ys_content_main_prepend' ); ?>
	<article id="post-<?php the_ID(); ?>" <?php post_class( [ 'singular-article' ] ); ?>>
		<?php ys_get_template_part( 'template-parts/page/header' ); ?>
		<div class="entry-content">
			<?php
			the_content();
			ys_get_template_part( 'template-parts/single/pagination' );
			?>
		</div>
		<?php ys_get_template_part( 'template-parts/page/footer' ); ?>
	</article>
	<?php do_action( 'ys_content_main_append' ); ?>
</main>
