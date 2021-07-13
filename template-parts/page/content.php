<?php
/**
 * 固定ページ メインコンテンツ テンプレート
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
		<?php ys_get_template_part( 'template-parts/page/header' ); ?>
		<div class="entry-content">
			<?php
			the_content();
			ys_get_template_part( 'template-parts/page/pagination' );
			?>
		</div>
		<?php ys_get_template_part( 'template-parts/page/footer' ); ?>
	</article>
	<?php do_action( 'ys_content_main_append' ); ?>
</main>
