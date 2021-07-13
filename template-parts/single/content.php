<?php
/**
 * 投稿詳細ページテンプレート
 *
 * @package ystandard
 * @author  yosiakatsuki
 * @license GPL-2.0+
 */

defined( 'ABSPATH' ) || die();
?>
<main id="main" class="content__main site-main">
	<?php do_action( 'ys_content_main_prepend' ); ?>
	<article id="post-<?php the_ID(); ?>" <?php post_class( [ 'singular-article' ] ); ?>>
		<?php ys_get_template_part( 'template-parts/single/header' ); ?>
		<div class="entry-content">
			<?php
			the_content();
			ys_get_template_part( 'template-parts/single/pagination' );
			?>
		</div>
		<?php ys_get_template_part( 'template-parts/single/footer' ); ?>
	</article>
	<?php do_action( 'ys_content_main_append' ); ?>
</main>
